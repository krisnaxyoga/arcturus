<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;


class TemplateAgentExportClass implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
         // Kembalikan koleksi kosong atau kosongkan kembali sesuai kebutuhan
         return new Collection();
    }

    public function headings(): array
    {
        // Atur judul kolom pada header template Excel
        return [
            'COMPANY NAME',
            'NAME',
            'EMAIL',
            'PHONE',
            'ADDRESS_LINE 1',
            'ADDRESS_LINE 2',
            'ZIPCODE',
            'COUNTRY',
            'STATE',
            'CITY',
            'WEB URL'
            // Lanjutkan dengan kolom lainnya
        ];
    }

    public function map($row): array
    {
        // Atur contoh nilai kosong atau kosongkan kembali sesuai kebutuhan
        return [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            // Lanjutkan dengan kolom lainnya
        ];
    }
}
