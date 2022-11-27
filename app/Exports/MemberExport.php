<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MemberExport implements FromView
{
    public function __construct($data)
    {
        $this->data = $data;
    }


    public function view(): View
    {
        return view('pages.backend.master.member.excel', $this->data);
    }
}
