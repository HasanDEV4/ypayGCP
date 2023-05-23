<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class NewSignupExport implements FromCollection
{

    protected $id;

 function __construct($id) {
        $this->id = $id;
 }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return User::select('full_name','email','phone_no','created_at')->whereIn('id',$this->id)->get();
    }
}
