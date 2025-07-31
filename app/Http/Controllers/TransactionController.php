<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return TransactionDetail::with(['coa', 'transaction'])->get();
        if (request()->ajax()) {

            $model = TransactionDetail::query()->with(['coa', 'transaction']);

            return DataTables::of($model)
                ->addColumn('action', function ($model) {
                    return view('transaction.partials.action-button', ['transaction' => $model]);
                })
                ->addColumn('coa_code', fn($model) => $model->coa->code)
                ->addColumn('coa_name', fn($model) => $model->coa->name)
                ->addColumn('date', fn($model) => date('d F Y', strtotime($model->transaction->date)))
                ->addColumn('description', fn($model) => $model->transaction->description)
                ->addIndexColumn()
                ->editColumn('debet', fn($model) => number_format($model->debet, 2, ',', '.'))
                ->editColumn('credit', fn($model) => number_format($model->credit, 2, ',', '.'))
                ->rawColumns(['action'])
                ->toJson();
        }

        return view('transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('transaction.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'chart_of_account_id' => 'required|array',
            'chart_of_account_id.*' => 'required|integer|exists:chart_of_accounts,id',
            'debet' => 'required|array',
            'credit' => 'required|array',
        ]);

        // Hitung total debet dan kredit
        $totalDebet = array_sum(array_map('floatval', $request->debet));
        $totalKredit = array_sum(array_map('floatval', $request->credit));

        if ($totalDebet != $totalKredit) {
            return back()->withErrors(['Total debet dan kredit tidak seimbang.']);
        }

        DB::beginTransaction();
        try {
            // Simpan transaksi utama
            $transaction = Transaction::create([
                'date' => $request->date,
                'description' => $request->description
            ]);

            // Loop untuk menyimpan detail
            foreach ($validated['chart_of_account_id'] as $i => $coaId) {
                $transaction->details()->create([
                    'chart_of_account_id' => $coaId,
                    'debet' => $validated['debet'][$i],
                    'credit' => $validated['credit'][$i],
                ]);
            }

            DB::commit();
            return redirect()->route('transaction.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
            // return back()->withErrors(['Gagal menyimpan transaksi: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
