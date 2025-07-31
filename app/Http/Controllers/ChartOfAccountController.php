<?php

namespace App\Http\Controllers;

use App\Models\ChartOfAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ChartOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {

            $model = ChartOfAccount::query()->when(
                request('category_id'),
                fn($query) => $query->where('category_coa_id', request('category_id'))
            )->when(
                request('type'),
                fn($query) => $query->where('type', request('type'))
            );

            return DataTables::of($model)
                ->addColumn('action', function ($model) {
                    return view('master.coa.partials.action-button', ['chartofaccount' => $model]);
                })
                ->addColumn('category', fn($model) => $model->categoryCoa->name)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('master.coa.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.coa.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'code' => ['required', Rule::unique('chart_of_accounts', 'code')],
            'category_coa_id' => ['required', 'exists:category_coas,id'],
        ]);

        try {
            DB::beginTransaction();
            ChartOfAccount::create($request->all());
            DB::commit();

            return redirect()->route('master.chartofaccount.index')->with([
                'success' => 'Kategori berhasil disimpan'
            ]);
        } catch (\Throwable $ex) {
            throw $ex;
            return back()->with(['error' => 'Something went wrong' . $ex->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartofaccount)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $chartofaccount)
    {
        return view('master.coa.form', compact('chartofaccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChartOfAccount $chartofaccount)
    {
        $request->validate([
            'name' => ['required'],
            'code' => ['required', Rule::unique('chart_of_accounts', 'code')->ignore($chartofaccount->id)],
            'category_coa_id' => ['required', 'exists:category_coas,id'],
        ]);

        try {
            DB::beginTransaction();
            $chartofaccount->update($request->all());
            DB::commit();
            return redirect()
                ->route('master.chartofaccount.index')
                ->with([
                    'success' => 'Kategori berhasil disimpan'
                ]);
        } catch (\Exception $ex) {
            return back()->with(['error' => 'Something went wrong' . $ex->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartofaccount)
    {
        $chartofaccount->delete();
        return response()->json(['Data berhasil dihapus']);
    }
}
