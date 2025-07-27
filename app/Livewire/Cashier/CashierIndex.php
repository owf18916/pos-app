<?php

namespace App\Livewire\Cashier;

use App\Models\Sale;
use App\Models\Product;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\SaleItem;
use Illuminate\Support\Str;
use App\Models\StockMovement;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CashierIndex extends Component
{
    public $search = '';
    public $cart = []; // [product_id => [product, quantity, price, subtotal]]
    public $paid = 0;
    public $discount = 0;
    public $filteredProducts = [];
    public $paymentMethod;

    protected $paymentFailedNotification = [
        'title' => 'Gagal!',
        'text' => 'Pembayaran kurang.',
        'icon' => 'error',
    ];

    protected $paymentRequiredNotification = [
        'title' => 'Pilih Metode Pembayaran',
        'text' => 'Silakan pilih metode pembayaran sebelum lanjut.',
        'icon' => 'warning',
    ];

    public function process()
    {
        $this->filteredProducts = [];

        $product = Product::where('product_code', $this->search)->first();
        if ($product) {
            $this->addToCart($product->id);
            $this->search = '';
            return;
        }

        // Jika tidak cocok barcode, tampilkan hasil pencarian nama
        if (strlen($this->search) > 1) {
            $this->filteredProducts = Product::where('name', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get();
        }
    }

    public function selectProduct($productId)
    {
        $this->addToCart($productId);
        $this->search = '';
        $this->filteredProducts = [];
    }

    public function processScan()
    {
        $product = Product::where('product_code', $this->search)
            ->orWhere()
            ->first();

        if ($product) {
            $this->addToCart($product->id);
        } else {
            $this->dispatch('swal', [
                'title' => 'Produk tidak ditemukan!',
                'text' => 'Barcode tidak cocok dengan data manapun.',
                'icon' => 'error',
            ]);
        }

        $this->search = '';
    }

    public function addToCart($productId)
    {
        $product = Product::findOrFail($productId);

        $cartedQty = isset($this->cart[$productId]) ? $this->cart[$productId]['quantity'] : 0;
        $projectedStock = $product->stock - $cartedQty;

        if($projectedStock < 1) {
            return $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Stok '.$product->name.' tidak mencukupi.',
                'icon' => 'error',
            ]);
        }
        
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'product' => $product,
                'quantity' => 1,
                'price' => $product->price,
                'subtotal' => $product->price,
            ];
        }

        $this->updateCartTotals();
    }

    public function updateCartTotals()
    {
        foreach ($this->cart as $productId => &$item) {
            $item['subtotal'] = $item['quantity'] * $item['price'];
        }
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
    }

    public function getSubtotalBeforeDiscountProperty()
    {
        return collect($this->cart)->sum('subtotal');
    }

    public function getFormattedSubtotalBeforeDiscountProperty()
    {
        return 'Rp ' . number_format($this->subtotalBeforeDiscount, 0, ',', '.');
    }

    public function getTotal()
    {
        $subtotal = collect($this->cart)->sum('subtotal');
        return max(0, $subtotal - $this->discount); // Tidak boleh negatif
    }

    public function getChange()
    {
        return $this->paid - $this->getTotal();
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->paid = 0;
        $this->getChange();

        $this->dispatch('swal', [
            'title' => 'Berhasil',
            'text' => 'Keranjang telah dikosongkan.',
            'icon' => 'success',
        ]);
    }

    public function printPreview()
    {
        if (empty($this->cart)) {
            return;
        }

        if(!$this->checkPayment($this->getTotal())) {
            return $this->dispatch('swal', $this->paymentFailedNotification);
        };

        if (!$this->isPaymentMethodValid()) {
            return $this->dispatch('swal', $this->paymentRequiredNotification);
        }

        $sale = [
            'invoice' => Sale::generateInvoiceNumber(),
            'date' => now()->format('d-m-Y H:i'),
            'items' => collect($this->cart)->map(function ($item) {
                return [
                    'name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'price' => number_format($item['price'], 0, ',', '.'),
                    'subtotal' => number_format($item['subtotal'], 0, ',', '.'),
                ];
            })->values()->all(),
            'subtotal' => number_format($this->subtotalBeforeDiscount, 0, ',', '.'),
            'discount' => number_format($this->discount, 0, ',', '.'),
            'total' => number_format($this->getTotal(), 0, ',', '.'),
            'paid' => number_format($this->paid, 0, ',', '.'),
            'change' => number_format($this->getChange(), 0, ',', '.'),
            'cashier' => Auth::user()->name,
            'payment_method' => ucfirst($this->paymentMethod),
        ];

        $this->dispatch('print-preview', $sale);
    }

    protected function checkPayment($total)
    {
        if ($this->paid < $total) {
            return false;
        } else {
            return true;
        }
    }

    protected function isPaymentMethodValid()
    {
        return in_array($this->paymentMethod, ['cash', 'transfer', 'qris']);
    }

    public function saveTransaction()
    {
        $total = $this->getTotal();
        
        if(!$this->checkPayment($total)) {
            return $this->dispatch('swal', $this->paymentFailedNotification);
        };

        if (!$this->isPaymentMethodValid()) {
            return $this->dispatch('swal', $this->paymentRequiredNotification);
        }

        try {
            DB::beginTransaction();

            $invoiceNumber = Sale::generateInvoiceNumber();
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'payment_method' => $this->paymentMethod,
                'user_id' => Auth::id(),
                'subtotal' => $this->getSubtotalBeforeDiscountProperty(),
                'discount' => $this->discount,
                'total_amount' => $total,
                'paid_amount' => $this->paid,
                'change_amount' => $this->getChange(),
            ]);

            foreach ($this->cart as $item) {
                // Kurangi stok
                $product = Product::findOrFail($item['product']['id']);
                $basePrice = $product->base_price;
                $basePriceAmount = $item['quantity'] * $basePrice;

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'base_price' => $basePrice,
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                    'profit' => $item['subtotal'] - $basePriceAmount,
                ]);

                StockMovement::create([
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'type' => 'penjualan',
                    'note' => 'penjualan atas invoice nomor : '.$sale->invoice_number,
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            $source = match ($this->paymentMethod) {
                'cash' => 'cash',
                'transfer', 'qris' => 'bank',
            };

            CashFlow::create([
                'type' => 'in',
                'source' => $source,
                'amount' => $total,
                'description' => 'Penjualan #' . $sale->invoice_number,
                'date' => now(),
                'sale_id' => $sale->id,
            ]);

            if ($this->discount > 0) {
                CashFlow::create([
                    'type' => 'out',
                    'source' => $source,
                    'amount' => $this->discount,
                    'description' => 'Diskon Penjualan #' . $sale->invoice_number,
                    'date' => now(),
                    'sale_id' => $sale->id,
                ]);
            }

            $this->dispatch('print-struk', [
                'invoice' => $sale->invoice_number,
                'date' => now()->format('d-m-Y H:i'),
                'discount' => number_format($sale->discount_amount ?? 0, 0, ',', '.'),
                'total' => number_format($sale->total_amount, 0, ',', '.'),
                'paid' => number_format($sale->paid_amount, 0, ',', '.'),
                'change' => number_format($sale->change_amount, 0, ',', '.'),
                'items' => $sale->items->map(fn($item) => [
                    'name' => $item->product->name,
                    'quantity' => $item->quantity,
                    'price' => number_format($item->price, 0, ',', '.'),
                    'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                ]),
            ]);

            DB::commit();

            $this->reset(['cart', 'paid', 'paymentMethod']);

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Transaksi berhasil disimpan!',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('save-transaction', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);

            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Kesalahan sistem, hubungi admin / IT',
                'icon' => 'error',
            ]);
        }
    }
    
    #[Title('Cashier')]
    public function render()
    {
        return view('livewire.cashier.cashier-index');
    }
}
