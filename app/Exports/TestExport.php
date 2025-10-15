<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TestExport implements FromCollection, WithHeadings, WithMapping 
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Admin::all();
    }

    /**
     * Set header columns
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'id',
            'name',
        ];
    }

    /**
     * Mapping data
     *
     * @return array
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
        ];
    }
}
