<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('faq.index');
    }

    public function show(Request $request)
    {
      $faqs = Faq::query();
      return DataTables::of($this->filter($request, $faqs))
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
              'question' => 'required',
              'text'     => 'required',
              'status'   => 'required',
          ]);

          if ($validator->fails()) {
              return response()->json(['error' => $validator->errors()]);
          }

          $faq           = new Faq();
          $faq->question = $request->question;
          $faq->text     = $request->text;
          $faq->status   = $request->status;
          $faq->user_id  = auth()->user()->id;
          $faq->save();

          return response()->json(['success'=> true, 'message' => 'FAQ Created successfully!']);

      } catch (\Exception $e) {
          return ['error' => 'Something went wrong'];
      }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\data  $data
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Faq $faq)
    {
      $validator = Validator::make($request->all(), [
              'question' => 'required',
              'text'     => 'required',
              'status'   => 'required',
          ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()/* ->all() */]);
      }

      $faq->question = $request->question;
      $faq->text     = $request->text;
      $faq->status   = $request->status;
      $faq->save();

      return response()->json(['success'=> true, 'message' => 'Faq updated successfully!']);
    }

    public function filter($request, $departments)
    {
        try {

            if (isset($request->department)) {
              $departments = $departments->where('name', 'like', '%' . $request->department . '%');
            }


            if (isset($request->from)) {
              $departments = $departments->whereDate('created_at', '>=', Carbon::parse($request->from) );
            }


            if (isset($request->to)) {
                $departments = $departments->whereDate('created_at', '<=', Carbon::parse($request->to));
            }

            if (isset($request->status)) {
                $departments = $departments->where('status', $request->status);
            }

            return $departments;

        } catch (\Exception $e) {
            return ['error' => 'Something went wrong'];
        }
    }
}
