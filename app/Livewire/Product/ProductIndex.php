<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockMovement;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;

class ProductIndex extends Component
{
    use WithPagination;

    protected $products;
    public $product_id;
    public $product_code;
    public $name;
    public $price;
    public $stock;
    public $isEditMode = false;
    public $search;

    public function boot()
    {
        $this->loadProducts();
    }

    public function resetForm()
    {
        $this->product_id = null;
        $this->product_code = '';
        $this->name = '';
        $this->price = '';
        $this->stock = '';
        $this->isEditMode = false;
        $this->resetValidation();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->product_code = $product->product_code;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->isEditMode = true;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Produk berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function save()
    {
        $rules = [
            'product_code' => ['required', 'string', $this->isEditMode
                ? Rule::unique('products', 'product_code')->ignore($this->product_id)
                : 'unique:products,product_code'],
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];

        $validated = $this->validate($rules);

        if ($this->isEditMode) {
            $product = Product::findOrFail($this->product_id);
            $oldStock = $product->stock;

            $product->update($validated);

            $stockDiff = $validated['stock'] - $oldStock;
            if ($stockDiff !== 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'quantity' => $stockDiff,
                    'type' => 'adjustment',
                    'note' => 'Perubahan stok manual',
                ]);
            }

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil diupdate.',
                'icon' => 'success',
            ]);
        } else {
            $product = Product::create($validated);

            if ($validated['stock'] > 0) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'quantity' => $validated['stock'],
                    'type' => 'initial',
                    'note' => 'Stok awal produk',
                ]);
            }

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Produk berhasil ditambahkan.',
                'icon' => 'success',
            ]);
        }

        // $this->resetForm();
        $this->resetPage();
    }

    public function addStock($id, $quantity)
    {
        $product = Product::findOrFail($id);
        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            $this->dispatch('swal', [
                'title' => 'Error',
                'text' => 'Jumlah stok harus lebih dari 0.',
                'icon' => 'error',
            ]);
            return;
        }

        $product->increment('stock', $quantity);

        StockMovement::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'type' => 'in',
            'note' => 'Penambahan stok manual',
        ]);

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Stok berhasil ditambahkan.',
            'icon' => 'success',
        ]);
    }

    #[On('load-products')]
    public function loadProducts()
    {
        $this->products = Product::when(!empty($this->search), fn ($q) => $q->where('name', 'like', '%' . $this->search . '%'))
                                ->latest()
                                ->paginate(10);
    }

    public function render()
    {
        return view('livewire.product.product-index', [
            'products' => $this->products,
        ]);
    }
}
