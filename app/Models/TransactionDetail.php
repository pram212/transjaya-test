<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $fillable = ['chart_of_account_id', 'debet', 'credit', 'transaction_id'];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function coa()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

}
