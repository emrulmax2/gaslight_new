<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BoilerNewManualExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect([
            [
                'Model' => 'Model 1',
                'GC NO' => 'GC-001',
                'URL' => 'https://example.com',
                'Fuel Type' => 'Fuel Type 1',
                'Year of Manufacture' => '2021',
            ],
        ]);
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'Model',	
            'GC NO',
            'URL',	
            'Fuel Type',	
            'Year of Manufacture',
        ];
    }
}
