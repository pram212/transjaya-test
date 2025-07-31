<?php

namespace App\Http\Controllers;

use App\Models\CategoryCoa;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function getCategories()
    {
        $results = CategoryCoa::when(request('term'), function ($query) {
                return $query->where('name', 'like', '%' . request('term') . '%');
            })
            ->limit(30)
            ->get();
        return response()->json($results);
    }

    public function getCoa()
    {
        $results = ChartOfAccount::when(request('term'), function ($query) {
                return $query->where('name', 'like', '%' . request('term') . '%')
                            ->orWhere('code', 'like', '%' . request('term') . '%');
            })
            ->limit(30)
            ->get();
        return response()->json($results);
    }
}
