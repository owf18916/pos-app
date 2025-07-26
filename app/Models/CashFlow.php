<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [
        'type', 'source', 'date', 'amount', 'description', 'sale_id',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public static function getBalance(string $source = 'cash', $date = null): int
    {
        $query = self::where('source', $source);
        
        if ($date) {
            $query->whereDate('created_at', '<=', $date);
        }

        return $query->get()
            ->reduce(fn ($sum, $trx) => $sum + ($trx->type === 'in' ? $trx->amount : -$trx->amount), 0);
    }
}
