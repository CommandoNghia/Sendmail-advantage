<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return collect([
            ['email'],
            ['nghiadangcapgold@gmail.com'],
            ['nghiadangcapgold2@gmail.com'],
            // Thêm các địa chỉ email khác ở đây
        ]);
    }

    public function headings(): array
    {
        return ['Email'];
    }
}
