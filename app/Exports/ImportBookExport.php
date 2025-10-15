<?php

namespace App\Exports;

use App\Models\ImportBook;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ImportBookExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnFormatting
{
    protected $params;
    protected $i;
    public function __construct($params)
    {
        $this->params = $params;
        $this->i = 1;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return  $this->params;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã phiếu', 
            'Khách hàng',
            'Nhập xưởng',
            'Ngày nhập',
            'Tổng tiền',
            'Nợ cũ',
            'Chiết khấu',
            'Thanh toán',
            'Còn nợ',
            'Ghi chú',
        ];
    }

    public function map($row): array
    {
        return [
            $this->i++,
            $row->code,
            $row->customer_name,
            $row->workshop_title,
            $row->date_at,
            $row->totalbill,
            $row->olddebt,
            $row->discount,
            $row->payment,
            $row->debt,
            $row->note
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF808080'],
            ]
        ]);

        // Auto-size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    public function columnFormats(): array
    {
        $columns = range('F', 'J');
        $formats = [];
        foreach ($columns as $column) {
            $formats[$column] = '#,##0" đ"';
        }
        return $formats;
    }
}
