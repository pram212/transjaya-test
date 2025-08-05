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
        $start = date('Y-m-d', strtotime($request->start)) ?? date('Y-m-d');
        $end = date('Y-m-d', strtotime($request->end)) ?? date('Y-m-d');

        $income = $this->getProfitLoss(type: 'Pendapatan', start: $start, end: $end);

        $expense = $this->getProfitLoss(type: 'Beban', start: $start, end: $end);

        // Dapatkan semua bulan unik dari income dan expense
        $allMonth = $this->getMergedMonth($income, $expense);

        $netIncome = [];

        foreach ($allMonth as $month) {
            $incomeTotal = $this->calculateDateTotal($income['data'], $month, false); // false = exclude _total row
            $expenseTotal = $this->calculateDateTotal($expense['data'], $month, false); // false = exclude _total row
            $netIncome[$month] = $incomeTotal - $expenseTotal;
        }

        if($request->export) {
            $fileName = 'laporan-labarugi-' . time() . '.xlsx';
            return Excel::download(new ProfitLossExport($income, $expense, $netIncome, $start, $end, $allMonth), $fileName);
        }

        // return [$income, $expense, $netIncome, $allMonth];

        return view('reports.profit-loss', [
            'income' => $income,
            'expense' => $expense,
            'netIncome' => $netIncome,
            'allMonth' => $allMonth,
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
                DB::raw("DATE_FORMAT(t.date, '%Y-%m') as month"),
                'ccoa.name as category',
                'coa.code as coa_code',
                'coa.name as coa_name',
                DB::raw($querySumTotalAmount),
            )
            ->where('coa.type', $type)
            ->whereDate('t.date', '>=', $start)
            ->whereDate('t.date', '<=', $end)
            ->groupBy(DB::raw("DATE_FORMAT(t.date, '%Y-%m')"), 'ccoa.name')
            ->oldest(DB::raw("DATE_FORMAT(t.date, '%Y-%m')"))
            ->get();

        // Transformasi data ke format yang diinginkan
        $categories = [];
        $months = [];
        $monthTotals = [];     // Untuk menyimpan total per bulan
        $grandTotal = 0;       // Untuk menyimpan grand total

        // Kumpulkan semua bulan unik dan kategori
        foreach ($pendapatan as $item) {
            if (!in_array($item->month, $months)) {
                $months[] = $item->month;
                $monthTotals[$item->month] = 0; // Inisialisasi total per bulan
            }

            if (!isset($categories[$item->category])) {
                $categories[$item->category] = [
                    'category' => $item->category,
                    '_total' => 0 // Tambahkan field total per kategori
                ];
                // Inisialisasi semua bulan dengan 0 untuk kategori ini
                foreach ($months as $date) {
                    $categories[$item->category][$date] = "0";
                }
            }
        }

        // Isi nilai amount untuk setiap kategori dan bulan
        foreach ($pendapatan as $item) {
            $amount = (float)$item->amount;
            $categories[$item->category][$item->month] = (string)$amount;

            // Hitung total per kategori
            $categories[$item->category]['_total'] += $amount;

            // Hitung total per bulan
            $monthTotals[$item->month] += $amount;

            // Hitung grand total
            $grandTotal += $amount;
        }

        // Pastikan semua bulan ada di setiap kategori
        foreach ($categories as &$categoryData) {
            foreach ($months as $month) {
                if (!isset($categoryData[$month])) {
                    $categoryData[$month] = "0";
                }
            }
            // Urutkan field
            $temp = ['category' => $categoryData['category']];
            foreach ($months as $month) {
                $temp[$month] = $categoryData[$month];
            }
            $temp['_total'] = $categoryData['_total']; // Tambahkan total kategori
            $categoryData = $temp;
        }

        // Tambahkan baris total per bulan
        $monthTotalsRow = ['category' => 'Total'];
        foreach ($months as $date) {
            $monthTotalsRow[$date] = (string)$monthTotals[$date];
        }
        $monthTotalsRow['_total'] = array_sum($monthTotals); // Total dari total bulan

        // Konversi ke collection dan tambahkan data tambahan
        $result = collect(array_values($categories))
            ->push($monthTotalsRow); // Tambahkan baris total per bulan

        return [
            'data' => $result,
            'months' => $months
        ];
    }

    private function getMergedMonth($income, $expense)
    {
        $incomeDates = $income['months'];
        $expenseDates = $expense['months'];

        // Gabungkan dan ambil bulan unik
        $allMonth = array_unique(array_merge($incomeDates, $expenseDates));

        // Urutkan bulan
        usort($allMonth, function ($a, $b) {
            return strtotime($a) - strtotime($b);
        });

        return $allMonth;
    }

    private function calculateDateTotal($data, $month, $includeTotalRow = false)
    {
        $total = 0;
        foreach ($data as $item) {
            // Skip baris Total jika tidak di-include
            if (!$includeTotalRow && $item['category'] === 'Total') {
                continue;
            }

            if (isset($item[$month])) {
                $total += (float)$item[$month];
            }
        }
        return $total;
    }
}
