<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;

class MISReport implements FromView,ShouldAutoSize
{
    use Exportable;
    private $data;
    public function __construct($data)
    {
      $this->data=$data;
    }
    public function view():View
    {
       return view('mis_report',[
        'data'=>$this->data
       ]);
    }
}
