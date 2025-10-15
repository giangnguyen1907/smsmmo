<?php

namespace App\Exports;

use App\Consts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrderExport implements WithMapping, FromCollection, WithColumnFormatting, WithHeadings, WithStyles
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
        return $this->params;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã đơn hàng',
            'Khách hàng',
            'Số điện thoại',
            'Thời gian',
            'Địa chỉ',
            'Giá trị đơn',
            'Hình thức thanh toán',
            'Trạng thái thanh toán',
            'Tình trạng đơn hàng',
        ];
    }

    public function map($row): array
    {
        $order_info = (string) $row->order_info;

        $jsonData = is_string($row->json_params) ? json_decode($row->json_params) : $row->json_params;
        $province = isset($jsonData->province_name) ? $jsonData->province_name : '';
        $district = isset($jsonData->district_name) ? $jsonData->district_name : '';
        $ward = isset($jsonData->ward_name) ? $jsonData->ward_name : '';
        $address = trim("{$province},{$district},{$ward}", ", ");

        //hình thức thanh toán
        $payment_method = '';
        $array_payment_method = array(0=>'COD',1=>'Ví',2=>'Chuyển khoản',3=>'VNPAY',4=>'Viettel money');
        foreach ($array_payment_method as $key => $item) {
            if ($key == $row->payment_method) {
                $payment_method = $item;
            }
        }

        //trạng thái thanh toán
        $payment_status = '';
        $array_payment_staus = array(0=>'Chưa thanh toán',1=>'Đã thanh toán');
        foreach ($array_payment_staus as $key => $item) {
            if ($key == $row->payment_status) {
                $payment_status = $item;
            }
        }

        // tình trạng đơn hàng
        $order_status = '';
        foreach (Consts::ORDER_STATUS as $key => $item) {
            if ($key == $row->status) {
                $order_status = $item;
            }
        }

        return [
            $this->i++,
            $order_info,
            $row->name,
            $row->phone,
            $row->order_date,
            $address,
            $row->payment,
            $payment_method,
            $payment_status,
            $order_status,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '0', //định dạng số lớn 
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1 . '₫', //định dạng tiền
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF808080'],
            ],
        ]);

        // Auto-size columns
        foreach (range('A', 'K') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // thêm màu cột J (trạng thái đơn hàng)
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $status = $sheet->getCell("J{$row}")->getValue();
            $color = $this->getOrderStatusColor($status);
            $sheet->getStyle("J{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $color],
                ],
            ]);
        }

        // thêm màu cột I (trạng thái thanh toán)
        for ($row = 2; $row <= $highestRow; $row++) {
            $status = $sheet->getCell("I{$row}")->getValue();
            $color = $this->getPaymentStatusColor($status);
            $sheet->getStyle("I{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => $color],
                ],
            ]);
        }
    }

    private function getOrderStatusColor($status)
    {
        switch ($status) {
            case 'Hủy đơn':
                return 'FFFF0000'; // Red
            case 'Chờ duyệt':
                return 'FFFFFF00'; // Yellow
            case 'Hoàn thành':
                return 'FF00FF00'; // Green
            case 'Đang giao':
                return 'FFFFA500'; // Orange
            default:
                return 'FFFFFFFF'; // White
        }
    }

    private function getPaymentStatusColor($status)
    {
        switch ($status) {
            case 'Đã thanh toán':
                return 'FF00FF00'; // Orange
            case 'Chưa thanh toán':
                return 'FFFFA500'; // Green
        }
    }

}
