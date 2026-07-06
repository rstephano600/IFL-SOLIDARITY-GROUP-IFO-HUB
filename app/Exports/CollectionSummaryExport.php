<?php

namespace App\Exports;

use App\Models\RepaymentSchedule;
use Maatwebsite\Excel\Concerns\FromCollection;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CollectionSummaryExport implements FromView
{
    public $collections;

    public function __construct($collections)
    {
        $this->collections = $collections;
    }

    public function view(): View
    {
        return view('in.loans.collections.summary.export_excel', [
            'collections' => $this->collections
        ]);
    }
}
