<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ProfitLossExport implements FromView, ShouldAutoSize, WithStyles, WithEvents
{
    protected $income;
    protected $expense;
    protected $netIncome;
    protected $start;
    protected $end;
    protected $allDates;

    public function __construct($income, $expense, $netIncome, $start, $end, $allDates)
    {
        $this->income = $income;
        $this->expense = $expense;
        $this->netIncome = $netIncome;
        $this->start = $start;
        $this->end = $end;
        $this->allDates = $allDates;
    }

    public function view(): View
    {
        return view('exports.profit-loss', [
            'income' => $this->income,
            'expense' => $this->expense,
            'netIncome' => $this->netIncome,
            'start_date' => $this->start,
            'end_date' => $this->end,
            'allDates' => $this->allDates
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header utama
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => 'center']
            ],
            // Header tanggal
            2 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F2F2F2']]
            ],
            // Header Income
            3 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E6FFE6']] // Hijau muda
            ],
            // Header Expense (akan dihitung dinamis)
            // Net Income (akan dihitung dinamis)
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Hitung baris untuk blok Income dan Expense
                $incomeStartRow = 3; // Baris mulai Income
                $incomeRowCount = count($this->income['data']);
                $incomeEndRow = $incomeStartRow + $incomeRowCount;

                $expenseStartRow = $incomeEndRow + 1;
                $expenseRowCount = count($this->expense['data']);
                $expenseEndRow = $expenseStartRow + $expenseRowCount;

                $netIncomeRow = $expenseEndRow + 1;

                $colCount = count($this->allDates) + 1;

                // Warna blok Income (hijau muda)
                $event->sheet->getStyle("A{$incomeStartRow}:" . $event->sheet->getCellByColumnAndRow($colCount, $incomeEndRow)->getCoordinate())
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('E6FFE6');

                // Warna blok Expense (merah muda)
                $event->sheet->getStyle("A{$expenseStartRow}:" . $event->sheet->getCellByColumnAndRow($colCount, $expenseEndRow)->getCoordinate())
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB('FFE6E6');

                // Style header Expense
                $event->sheet->getStyle("A{$expenseStartRow}:" . $event->sheet->getCellByColumnAndRow($colCount, $expenseStartRow)->getCoordinate())
                    ->getFont()
                    ->setBold(true);

                // Style Net Income
                $event->sheet->getStyle("A{$netIncomeRow}:" . $event->sheet->getCellByColumnAndRow($colCount, $netIncomeRow)->getCoordinate())
                    ->getFont()
                    ->setBold(true);

                // Warna cell Net Income berdasarkan nilai
                for ($col = 2; $col <= $colCount; $col++) {
                    $value = $event->sheet->getCellByColumnAndRow($col, $netIncomeRow)->getValue();
                    $color = $value >= 0 ? 'C8E6C9' : 'FFCDD2'; // Hijau untuk laba, merah untuk rugi

                    $event->sheet->getStyleByColumnAndRow($col, $netIncomeRow)
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setRGB($color);
                }
            }
        ];
    }
}
