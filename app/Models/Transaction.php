<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['date', 'description'];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}
