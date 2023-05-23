<?php

namespace App\Http\Controllers\API\version_3;

use App\Models\Faq;
use App\Models\Policy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;
use Illuminate\Support\Facades\Http;


class HelpController3 extends Controller
{
    public function getFaq()
    {
        $faq = Faq::where('status', 1)->orderBy('id', 'DESC')->get();
        $data = [];

        foreach ($faq as $key => $value) {
            $data[$key]['title']   = $value['question'];
            $data[$key]['content'] = $value['text'];
        }

        return response()->json([
            'data'  => $data,
            'status' => true,
        ], 200);
    }

    public function getPolicy(Request $request)
    {
        $policy = Policy::whereId($request->page)->first();

        return response()->json([
            'data'  => $policy,
            'status' => true,
        ], 200);
    }

    public function searchFaq(Request $request)
    {
        $faq = Faq::where('status', 1)->where('question', 'like', '%' . $request->search . '%')->orderBy('id', 'DESC')->get();

        $data = [];

        foreach ($faq as $key => $value) {
            $data[$key]['title']   = $value['question'];
            $data[$key]['content'] = $value['text'];
        }

        return response()->json([
            'data' => $data,
            'status' => true,
        ],200);
    }


}