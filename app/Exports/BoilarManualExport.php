<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BoilarManualExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Return your collection data here
        return collect([
            [
                'S/N' => 1,
                'GC NO' => 'GC-001',
                'URL' => 'https://example.com',
                'Model' => 'Model 1',
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
            'S/N',
            'GC NO',
            'URL',	
            'Model',	
            'Fuel Type',	
            'Year of Manufacture',
            // Add more headings as needed
        ];
    }
}