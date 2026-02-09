<?php

use App\Models\ApplianceFlueType;
use App\Models\ApplianceLocation;
use App\Models\ApplianceTimeTemperatureHeating;
use App\Models\ApplianceType;
use App\Models\BoilerBrand;
use App\Models\Color;
use App\Models\CommissionDecommissionWorkType;
use App\Models\GasWarningClassification;
use App\Models\PowerflushCirculatorPumpLocation;
use App\Models\PowerflushCylinderType;
use App\Models\PowerflushPipeworkType;
use App\Models\PowerflushSystemType;
use App\Models\RadiatorType;
use App\Models\Record;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('merge')) {
    function merge($arrays)
    {
        $result = [];

        foreach ($arrays as $array) {
            if ($array !== null) {
                if (gettype($array) !== 'string') {
                    foreach ($array as $key => $value) {
                        if (is_integer($key)) {
                            $result[] = $value;
                        } elseif (isset($result[$key]) && is_array($result[$key]) && is_array($value)) {
                            $result[$key] = merge([$result[$key], $value]);
                        } else {
                            $result[$key] = $value;
                        }
                    }
                } else {
                    $result[count($result)] = $array;
                }
            }
        }

        return join(" ", $result);
    }
}

if (!function_exists('uncamelize')) {
    function uncamelize($camel, $splitter = "_")
    {
        $camel = preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter . '$0', $camel));
        return strtolower($camel);
    }
}

if (!function_exists('formatCurrency')) {
    function formatCurrency($number)
    {
        if ($number) {
            $formattedNumber = preg_replace('/\D/', '', strval($number));
            $rest = strlen($formattedNumber) % 3;
            $currency = substr($formattedNumber, 0, $rest);
            $thousand = str_split(substr($formattedNumber, $rest), 3);
            $separator = '';

            if ($thousand) {
                $separator = $rest ? "." : "";
                $currency .= $separator . implode(".", $thousand);
            }

            return $currency;
        } else {
            return "";
        }
    }
}

if (!function_exists('getFileList')) {
    function getFileList($directory, $extensions)
    {
        $files = [];

        if (is_dir($directory)) {
            $scannedFiles = scandir($directory);
            foreach ($scannedFiles as $file) {
                if ($file === '.' || $file === '..') continue;

                $fileExtension = explode(".", $file);
                if (in_array(end($fileExtension), explode(",", $extensions))) {
                    array_push($files, str_replace(base_path() . "/", "", "/" . implode("/", array_filter(explode("/", $directory), "strlen")) . "/" . $file));
                }
            }
        }

        return $files;
    }
}

if (!function_exists('locationName')) {
    function locationName($id, $default = ''){
        return ApplianceLocation::find($id)?->name ?? $default;
    }
}
if (!function_exists('boilerBrandName')) {
    function boilerBrandName($id, $default = ''){
        return BoilerBrand::find($id)?->name ?? $default;
    }
}
if (!function_exists('typeName')) {
    function typeName($id, $default = ''){
        return ApplianceType::find($id)?->name ?? $default;
    }
}
if (!function_exists('flueName')) {
    function flueName($id, $default = ''){
        return ApplianceFlueType::find($id)?->name ?? $default;
    }
}
if (!function_exists('classificationName')) {
    function classificationName($id, $default = ''){
        return GasWarningClassification::find($id)?->name ?? $default;
    }
}
if (!function_exists('heatingName')) {
    function heatingName($id, $default = ''){
        return ApplianceTimeTemperatureHeating::find($id)?->name ?? $default;
    }
}
if (!function_exists('systemTypeName')) {
    function systemTypeName($id, $default = ''){
        return PowerflushSystemType::find($id)?->name ?? $default;
    }
}
if (!function_exists('cylinderName')) {
    function cylinderName($id, $default = ''){
        return PowerflushCylinderType::find($id)?->name ?? $default;
    }
}
if (!function_exists('pipeworkTypeName')) {
    function pipeworkTypeName($id, $default = ''){
        return PowerflushPipeworkType::find($id)?->name ?? $default;
    }
}
if (!function_exists('pumpLocationName')) {
    function pumpLocationName($id, $default = ''){
        return PowerflushCirculatorPumpLocation::find($id)?->name ?? $default;
    }
}
if (!function_exists('rediatorTypeName')) {
    function rediatorTypeName($id, $default = ''){
        return RadiatorType::find($id)?->name ?? $default;
    }
}
if (!function_exists('colorName')) {
    function colorName($id, $default = ''){
        return Color::find($id)?->name ?? $default;
    }
}
if (!function_exists('workTypeName')) {
    function workTypeName($id, $default = ''){
        return CommissionDecommissionWorkType::find($id)?->name ?? $default;
    }
}

if (!function_exists('get_due_inspection_counts')) {

    /**
     * Get monthly due inspection counts excluding already mailed records.
     *
     * @return array
     */
    function get_due_inspection_counts($year = null)
    {
        // determine year
        $year = $year ? (int) $year : now()->year;

        // start & end of that year
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end   = Carbon::create($year, 12, 31)->endOfDay();

        $records = Record::selectRaw('
                YEAR(next_inspection_date) as year,
                MONTH(next_inspection_date) as month,
                COUNT(*) as total
            ')
            ->whereNotNull('next_inspection_date')
            ->whereBetween('next_inspection_date', [$start, $end])
            ->groupByRaw('YEAR(next_inspection_date), MONTH(next_inspection_date)')
            ->whereIn('job_form_id', [6, 9])
            ->get()
            ->keyBy(function ($item) {
                return $item->year . '-' . $item->month;
            });

        $result = [];

        $current = $start->copy();

        while ($current <= $end) {

            $key = $current->year . '-' . $current->month;
            $count = isset($records[$key]) ? $records[$key]->total : 0;

            $result[] = [
                'month'     => $current->format('F'),
                'month_no'  => $current->month,
                'year'      => $current->year,
                'count'     => $count,
            ];

            $current->addMonth();
        }

        return $result;
    }
}


if (!function_exists('get_upcoming_inspection_count')) {

    /**
     * Get count of upcoming inspections from today until end of current year
     *
     * Includes:
     * - All next_inspection_date from today onward
     * - Dates until the end of the year
     * - Includes already sent mails
     *
     * @param int|null $userId
     * @return int
     */
    function get_upcoming_inspection_count($userId = null)
    {
        $today       = now()->startOfDay();
        $endOfYear   = now()->endOfYear();

        $query = \App\Models\Record::whereNotNull('next_inspection_date')
            ->whereDate('next_inspection_date', '>=', $today)
            ->whereDate('next_inspection_date', '<=', $endOfYear)
            ->whereIn('job_form_id', [6, 9]);

        // Optional user filter
        if (!empty($userId)) {
            $query->where('created_by', $userId);
        }

        return $query->count();
    }
}