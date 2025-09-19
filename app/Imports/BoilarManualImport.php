<?php

namespace App\Imports;

use App\Models\BoilerManual;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BoilarManualImport implements ToModel, WithHeadingRow
{
    protected $boilerBrandId;

    public function __construct($boilerBrandId)
    {
        $this->boilerBrandId = $boilerBrandId;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new BoilerManual([
            'boiler_new_brand_id' => $this->boilerBrandId,
            'gc_no' => $row['gc_no'],
            'url' => $row['url'],
            'model' => $row['model'],
            'fuel_type' => $row['fuel_type'],
            'year_of_manufacture' => $row['year_of_manufacture'],
        ]);
    }
}
