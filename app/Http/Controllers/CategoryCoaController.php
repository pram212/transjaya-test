<?php

namespace App\Http\Controllers;

use App\Models\CategoryCoa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CategoryCoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {

            $model = CategoryCoa::query();

            return DataTables::of($model)
                ->addColumn('action', function ($model) {
                    return view('master.category.partials.action-button', ['categoryCoa' => $model]);
                })
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('master.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.category.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
        ]);
        try {
            DB::beginTransaction();
            CategoryCoa::create([
                'name' => $request->name
            ]);
            DB::commit();

            return redirect()->route('master.kategori.index')->with([
                'success' => 'Kategori berhasil disimpan'
            ]);
        } catch (Exception $ex) {
            return back()->with(['error' => 'Something went wrong' . $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CategoryCoa $categoryCoa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryCoa $categoryCoa)
    {
        return view('master.category.form', compact('categoryCoa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryCoa $categoryCoa)
    {
        $request->validate([
            'name' => ['required'],
        ]);

        try {
            DB::beginTransaction();
            $categoryCoa->update(['name' => $request->name]);
            return redirect()
                ->route('master.kategori.index')
                ->with([
                    'success' => 'Kategori berhasil disimpan'
                ]);

            DB::commit();

            return redirect()->route('master.kategori.index')->with([
                'success' => 'Kategori berhasil disimpan'
            ]);
        } catch (Exception $ex) {
            return back()->with(['error' => 'Something went wrong' . $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryCoa $categoryCoa)
    {
        $categoryCoa->delete();
        return response()->json(['Data berhasil dihapus']);
    }
}
