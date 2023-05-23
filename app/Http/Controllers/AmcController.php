<?php

namespace App\Http\Controllers;

use App\Models\Amc;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use function App\Libraries\Helpers\s3ImageUpload;

class AmcController extends Controller
{

    // function __construct()
    // {
    //      $this->middleware('permission:amc-list');
    //      $this->middleware('permission:amc-create', ['only' => ['store']]);
    //      $this->middleware('permission:amc-edit', ['only' => ['update']]);
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('amc.index');
    }

    public function show(Request $request)
    {
      $amc = Amc::query();
      return DataTables::of($this->filter($request, $amc))
            ->order(function ($q) use ($request) {
                if (count($request->order)) {
                    foreach ($request->order as $order) {
                        $column = @$request->columns[@$order['column']]['data'];
                        $dir = @$order['dir'];
                        if ($column && $dir) {
                            $q->orderBy($column, $dir);
                        }
                    }
                }
            })
            ->addIndexColumn()
            ->make(true);
    }

    

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      try {
          $validator = Validator::make($request->all(), [
              'entity_name'                 => 'required|max:50',
              'address'                     => 'required|max:100',
              'logo'                        => 'required|image',
              'contact_no'                  => 'sometimes|required|max:15',
              'compliant_email'             => 'required|email',
              'company_registration_number' => 'required|max:20|alpha_num',
              'ntn'                         => 'required|max:20',
              'contact_person'              => 'required|max:20',
              'url'                         => 'required',
              'account_title'               => 'required|max:25',
              'iban_number'                 => 'required|max:25|alpha_num',
              'select_csv_send_method'      => 'required',
              'select_data_send_method'     => 'required',
              'units_convertable'           => 'required',
          ],);

          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

        //   if($request->id) {
        //     $amc = Amc::find($request->id);
        //   }

          $amc = new Amc();

          

          $file             = $request->file('logo');
          $fileOriginalName = $file->getClientOriginalName();
          $extension        = $file->getClientOriginalExtension();
          $fileNameToStore  = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
          $path               = "amc/".$fileNameToStore;
          $filename           = s3ImageUpload($file, $path);
          //   $filename         = $file->storeAs('public/uploads', $fileNameToStore);


          $amc->entity_name                 = $request->entity_name;
          $amc->address                     = $request->address;
          $amc->contact_no                  = $request->contact_no;
          $amc->compliant_email             = $request->compliant_email;
          $amc->company_registration_number = $request->company_registration_number;
          $amc->ntn                         = $request->ntn;
          $amc->contact_person              = $request->contact_person;
          $amc->url                         = $request->url;
          $amc->bank_name                   = $request->bank_name;
          $amc->account_title               = $request->account_title;
          $amc->iban_number                 = $request->iban_number;
          $amc->status                      = $request->status;
          $amc->status                      = $request->status;
          $amc->logo                        = $filename;
          $amc->original_name               = $fileOriginalName;
          $amc->units_convertable           = $request->units_convertable;
          $amc->user_id                     = auth()->user()->id;
          $amc->save();
          return response()->json(['success'=> true, 'message' => 'AMC Successfully Created']);

      } catch (\Exception $e) {
          return ['error' => /* 'Something went wrong' */ $e];
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Amc $amc)
    {

      $validator = Validator::make($request->all(), [
              'entity_name'                 => 'required',
              'address'                     => 'required',
              'logo'                        => 'sometimes|image',
              'contact_no'                  => 'required',
              'compliant_email'             => 'required|email',
              'company_registration_number' => 'required',
              'ntn'                         => 'required',
              'contact_person'              => 'required',
              'url'                         => 'required',
              'status'                      => 'required',
              'select_data_send_method'      => 'required',
              'units_convertable'           => 'required',
          ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()]);
      }

        $amc->entity_name                 = $request->entity_name;
        $amc->address                     = $request->address;
        $amc->contact_no                  = $request->contact_no;
        $amc->compliant_email             = $request->compliant_email;
        $amc->company_registration_number = $request->company_registration_number;
        $amc->ntn                         = $request->ntn;
        $amc->contact_person              = $request->contact_person;
        $amc->url                         = $request->url;
        $amc->bank_name                   = $request->bank_name;
        $amc->account_title               = $request->account_title;
        $amc->iban_number                 = $request->iban_number;
        $amc->status                      = $request->status;
        $amc->units_convertable           = $request->units_convertable;
      if($request->select_data_send_method=="CSV")
      {
        $amc->through_csv=1;
        $amc->through_api=0;
        if($request->select_csv_send_method=="Email")
        {
            $amc->through_email=1;
            $amc->through_drive=0;
            $amc->csv_emails=$request->emails;
        }
        else if($request->select_csv_send_method=="G Drive")
        {
            $amc->through_email=0;
            $amc->through_drive=1;
        }
      }
      else if($request->select_data_send_method=="API")
      {
        $amc->through_api=1;
        $amc->through_csv=0;
        $amc->through_email=0;
        $amc->through_drive=0;
      }
      if ($request->file('logo')) {
        $file               = $request->file('logo');
        $fileOriginalName   = $file->getClientOriginalName();
        $extension          = $file->getClientOriginalExtension();
        $fileNameToStore    = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $fileOriginalName)) . '_' . time() . '.' . $extension;
        // $filename           = $file->storeAs('/public/uploads', $fileNameToStore);
        $path               = "amc/".$fileNameToStore;
        $filename           = s3ImageUpload($file, $path);
        $amc->logo          = $filename;
        $amc->original_name = $fileOriginalName;
      }
      $amc->save();
      return response()->json(['success'=> true, 'message' => 'AMC updated successfully!']);
    }

     public function autocomplete(Request $request)
    {
        try {
            $data = [];
            $queryTerm = $request->q;
            $amcs = Amc::where('entity_name', 'like', '%' . $queryTerm . '%')->take(30)->get();
            foreach ($amcs as $amc) {
                $data[] = ['id' => $amc->id, 'text' => $amc->entity_name];
            }
            return $data;
        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }


    public function filter($request, $amc)
    {
        
        try {

            if (isset($request->amcid) && $request->amcid!="null") {
              $amc = $amc->where('id',$request->amcid);
            }


            if (isset($request->complaintEmail) && $request->complaintEmail!="null") {
                
                $amc = $amc->where('compliant_email', 'like', '%' . $request->complaintEmail . '%');
            }


            if (isset($request->contactNumber)&& $request->contactNumber!="null") {
                $amc = $amc->where('contact_no', 'like', '%' . $request->contactNumber . '%');
            }

            
            if (isset($request->crnNumber) && $request->crnNumber!="null") {
                $amc = $amc->where('company_registration_number', 'like', '%' . $request->crnNumber . '%');
            }

            if (isset($request->ntnNumber) && $request->ntnNumber!="null") {
                $amc = $amc->where('ntn', 'like', '%' . $request->ntnNumber . '%');
            }

            if (isset($request->status) && $request->status!="null") {
                $amc = $amc->where('status', $request->status);
            }

            return $amc;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }


    public function AmcAdd()
    {
      return view('amc.add');
    }

    public function amcEdit($id)
    {
      
      $data = Amc::find($id);
      if (!$data) {
        return redirect()->back();
      }
      
      return view('amc.add', compact('data'));
    }

    

}
