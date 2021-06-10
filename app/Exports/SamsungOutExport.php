<?php

namespace App\Exports;

use App\Models\SamsungOut;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;

class SamsungOutExport implements FromCollection
{
    use Exportable;

    protected $selected;

    public function __construct($selected)
    {
        $this->selected = $selected;
    }

    public function collection()
    {
        return SamsungOut::whereIn('id', $this->selected)->get();
    }
}
