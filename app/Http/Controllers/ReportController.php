<?php

namespace App\Http\Controllers;

use App\Exports\ProfitLossExport;
use App\Models\ChartOfAccount;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function profit(Request $request)
    {
        $start = $request->start ?? date('Y-m-d');
        $end = $request->end ?? date('Y-m-d');

        $income = $this->getProfitLoss(type: 'Pendapatan', start: $start, end: $end);
        $expense = $this->getProfitLoss(type: 'Beban', start: $start, end: $end);

        // Dapatkan semua tanggal unik dari income dan expense
        $allDates = $this->getMergedDates($income, $expense);

        $netIncome = [];

        foreach ($allDates as $date) {
            $incomeTotal = $this->calculateDateTotal($income['data'], $date, false); // false = exclude _total row
            $expenseTotal = $this->calculateDateTotal($expense['data'], $date, false); // false = exclude _total row
            $netIncome[$date] = $incomeTotal - $expenseTotal;
        }

        if($request->export) {
            $fileName = 'laporan-labarugi-' . time() . '.xlsx';
            return Excel::download(new ProfitLossExport($income, $expense, $netIncome, $start, $end, $allDates), $fileName);
        }

        return view('reports.profit-loss', [
            'income' => $income,
            'expense' => $expense,
            'netIncome' => $netIncome,
            'allDates' => $allDates,
        ]);
    }

    private function getProfitLoss($type, $start, $end)
    {
        $querySumTotalAmount = $type == 'Pendapatan'
            ? 'SUM(td.credit - td.debet) as amount'
            : 'SUM(td.debet - td.credit) as amount';

        $pendapatan = DB::table('transaction_details as td')
            ->join('transactions as t', 't.id', 'td.transaction_id')
            ->join('chart_of_accounts as coa', 'coa.id', 'td.chart_of_account_id')
            ->join('category_coas as ccoa', 'ccoa.id', 'coa.category_coa_id')
            ->select(
                'td.id',
                't.date',
                'ccoa.name as category',
                'coa.code as coa_code',
                'coa.name as coa_name',
                DB::raw($querySumTotalAmount),
            )
            ->where('coa.type', $type)
            ->whereDate('t.date', '>=', $start)
            ->whereDate('t.date', '<=', $end)
            ->groupBy('t.date', 'ccoa.name')
            ->oldest('t.date')
            ->get();

        // Transformasi data ke format yang diinginkan
        $categories = [];
        $dates = [];
        $dateTotals = [];     // Untuk menyimpan total per tanggal
        $grandTotal = 0;       // Untuk menyimpan grand total

        // Kumpulkan semua tanggal unik dan kategori
        foreach ($pendapatan as $item) {
            if (!in_array($item->date, $dates)) {
                $dates[] = $item->date;
                $dateTotals[$item->date] = 0; // Inisialisasi total per tanggal
            }

            if (!isset($categories[$item->category])) {
                $categories[$item->category] = [
                    'category' => $item->category,
                    '_total' => 0 // Tambahkan field total per kategori
                ];
                // Inisialisasi semua tanggal dengan 0 untuk kategori ini
                foreach ($dates as $date) {
                    $categories[$item->category][$date] = "0";
                }
            }
        }

        // Isi nilai amount untuk setiap kategori dan tanggal
        foreach ($pendapatan as $item) {
            $amount = (float)$item->amount;
            $categories[$item->category][$item->date] = (string)$amount;

            // Hitung total per kategori
            $categories[$item->category]['_total'] += $amount;

            // Hitung total per tanggal
            $dateTotals[$item->date] += $amount;

            // Hitung grand total
            $grandTotal += $amount;
        }

        // Pastikan semua tanggal ada di setiap kategori
        foreach ($categories as &$categoryData) {
            foreach ($dates as $date) {
                if (!isset($categoryData[$date])) {
                    $categoryData[$date] = "0";
                }
            }
            // Urutkan field
            $temp = ['category' => $categoryData['category']];
            foreach ($dates as $date) {
                $temp[$date] = $categoryData[$date];
            }
            $temp['_total'] = $categoryData['_total']; // Tambahkan total kategori
            $categoryData = $temp;
        }

        // Tambahkan baris total per tanggal
        $dateTotalsRow = ['category' => 'Total'];
        foreach ($dates as $date) {
            $dateTotalsRow[$date] = (string)$dateTotals[$date];
        }
        $dateTotalsRow['_total'] = array_sum($dateTotals); // Total dari total tanggal

        // Konversi ke collection dan tambahkan data tambahan
        $result = collect(array_values($categories))
            ->push($dateTotalsRow); // Tambahkan baris total per tanggal

        return [
            'data' => $result,
            'dates' => $dates
        ];
    }

    private function getMergedDates($income, $expense)
    {
        $incomeDates = $income['dates'];
        $expenseDates = $expense['dates'];

        // Gabungkan dan ambil tanggal unik
        $allDates = array_unique(array_merge($incomeDates, $expenseDates));

        // Urutkan tanggal
        usort($allDates, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return $allDates;
    }

    private function calculateDateTotal($data, $date, $includeTotalRow = false)
    {
        $total = 0;
        foreach ($data as $item) {
            // Skip baris Total jika tidak di-include
            if (!$includeTotalRow && $item['category'] === 'Total') {
                continue;
            }

            if (isset($item[$date])) {
                $total += (float)$item[$date];
            }
        }
        return $total;
    }
}
