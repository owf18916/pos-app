<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'user_id', 'total_amount', 'paid_amount', 'change_amount'
    ];

    public static function generateInvoiceNumber()
    {
        $prefix = "CHN";
        $now = \Carbon\Carbon::now();
        $bulanTahun = $now->format('my'); // ex: 0725 (Juli 2025)

        // Hitung jumlah invoice yang sudah ada bulan ini
        $count = DB::table('sales')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $sequence = str_pad($count + 1, 5, '0', STR_PAD_LEFT); // 00001, 00002, dst.

        return $prefix . $bulanTahun . $sequence;
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
