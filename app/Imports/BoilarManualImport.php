<?php

namespace App\Imports;

use App\Models\BoilerManual;
use Maatwebsite\Excel\Concerns\ToModel;

class BoilarManualImport implements ToModel
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
            'boiler_brand_id' => $this->boilerBrandId,
            'gc_no' => $row[1],
            'url' => $row[2],
            'model' => $row[3],
            'fuel_type' => $row[4],
            'year_of_manufacture' => $row[5],
        ]);
    }
}
