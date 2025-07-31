<?php

namespace App\Http\Controllers;

use App\Models\CategoryCoa;
use Illuminate\Http\Request;

class OptionsController extends Controller
{
    public function getCategories()
    {
        $results = CategoryCoa::when(request('term'), function ($query) {
                return $query->where('name', 'like', '%' . request('term') . '%');
            })
            ->when(request('regency_id'), function ($query) {
                return $query->where('regency_id', request('regency_id') );
            })
            ->limit(30)
            ->get();
        return response()->json($results);
    }
}
