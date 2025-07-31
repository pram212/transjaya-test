<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    /** @use HasFactory<\Database\Factories\ChartOfAccountFactory> */
    use HasFactory;

    protected $fillable = ['code', 'name', 'category_coa_id', 'type'];

    public function categoryCoa()
    {
        return $this->belongsTo(CategoryCoa::class);
    }
}
