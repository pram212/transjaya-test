<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryCoa extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryCoaFactory> */
    use HasFactory;
    protected $fillable = ['name'];

    public function coa()
    {
        return $this->hasMany(ChartOfAccount::class);
    }
}
