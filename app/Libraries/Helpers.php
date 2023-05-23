<?php

namespace App\Libraries\Helpers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Arr;
use Carbon\Carbon;
function s3ImageUpload($file, $path) {
    $image = Storage::disk('s3')->put($path, file_get_contents($file));
    $image_url = Storage::disk('s3')->url($path);
    return $image_url;
}
function is_Date($str){ 
    $str=str_replace('/', '-', $str);
    return is_numeric(strtotime($str));
}
function s3ImageUploadApi($file, $path) {
    $data = explode( ',', $file );
    if(isset($data[1]))
    $image = Storage::disk('s3')->put($path, base64_decode($data[1]));
    else
    $image= Storage::disk('s3')->put($path, base64_decode($data[0]));;
    $image_url = Storage::disk('s3')->url($path);
    return $image_url;
}

function generateAssetAllocation($data = [], $response = false)
{
    $month = (int) date('m');
    $year = (int) ($month > 7 ? date('Y') : date('Y') - 1);
    $allocationItem = [
        'return' => 0,
        'peer_average' => 0,
        'rank' => 0
    ];
    $allocationData = [
        'return' => [],
        'annualized_return' => [],
        'fyreturn' => []
    ];

    foreach (['month_1', 'month_3', 'month_6', 'ytd'] as $head) {
        $allocationData['return'] = $allocationData['return']
            ? $allocationData['return'] + [$head => $allocationItem]
            : [$head => $allocationItem];
    }

    foreach (['year_1', 'year_3', 'year_5'] as $head) {
        $allocationData['annualized_return'] = $allocationData['annualized_return']
            ? $allocationData['annualized_return'] + [$head => $allocationItem]
            : [$head => $allocationItem];
    }

    foreach (range($year - 3, $year) as $head) {
        $allocationData['fyreturn'] = $allocationData['fyreturn']
            ? $allocationData['fyreturn'] + [$head => $allocationItem]
            : [$head => $allocationItem];
    }

    if (count($data)) {
        foreach ($data as $dtk => $dtv) {
            $key = is_string($dtk) ? $dtk : @$dtv['key'];
            $value = is_string($dtk) ? $dtv : @$dtv['value'];
            if (Arr::has($allocationData, $key)) {
                Arr::set($allocationData, $key, $value);
            }
        }
        if($response) {
            foreach($allocationData as $atk => $atv) {
                $allocationData[$atk] = [];
                foreach($atv as $catk => $catv) {
                    $allocationData[$atk][][$catk] = $catv;
                }
            }
        }
    }

    return $allocationData;
}

function sendNotification($fcm_token, $data, $user_id, $type){
        $url       = 'https://fcm.googleapis.com/fcm/send';
        $FcmToken  = [$fcm_token];
        // $serverKey = 'AAAActxjvSw:APA91bEXhzT5gTTqoJOIuMqhE0KXwcyFMGy5KQuk3yyB5iBiMvE2SiM5iZcs7Hq_V9V67pCJD_ICphQowXJMy6inPc3N0HEIeuFxp0PHr66Ix4PCTGfv5faX6YzFMwARG1ElvpAn0O89';
        $serverKey = 'AAAAP0lM3xM:APA91bHIcQJbQO90zNLWWGTBoH95nNr2ccUrYZafcLz14XiyJDyDsJT33DzZWn7bLOO7Zcp_25G4AUOKt11dKLAOS9sj_jFLWMhI9L-r-1g2ngAcOwXvPahRiGf5Fy7ZnVrisd9BWvt-';
  
        $noti = [
            "registration_ids" => $FcmToken,
            "notification" => [
                 "title" => $type,
                 "body"  => $data['message'],
                 "sound" => "default"
            ],
            "priority" => "high",
            "data"     => $data
        ];
        $encodedData = json_encode($noti);

        $notification          = new \App\Models\Notification;
        $notification->user_id = $user_id;
        $notification->data    = $encodedData;
        $notification->type    = $type;
        $notification->save();

        if (!@$fcm_token) {
            return true;
        }
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        curl_close($ch);
        return true;
}

function userInvestmentSum($user_id)
{
    $my_investment = \App\Models\Investment::where('user_id', $user_id)->where('status', 1)->with('fund', 'redemption', 'conversions')->get();
    $my_investment = $my_investment->toArray();
    foreach ($my_investment as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_investment[$key]);
                    continue;
                }
            }
        } else if ($value['conversions']) {
            foreach ($value['conversions'] as $key3 => $value3) {
                if($value3['status'] == 1){
                    unset($my_investment[$key]);
                    continue;
                }
            }
        }
    }
    $sum_1 = array_sum(array_map(function($item) {
        if($item['status'] == 1 && ($item['nav'] && $item['unit'] && $item['fund']['nav'])){
            // return (@$item['amount']*$item['fund']['nav'])/$item['nav'];
            // return ($item['unit']*$item['fund']['nav']);
            return round(((float) $item['unit'] * (float) $item['fund']['nav']), 2);
        }
        else if($item['status'] == 1){
            return @$item['amount'];
        }
        else{
            return 0;
        }
    }, $my_investment));

    $my_conversion = \App\Models\Conversion::where('user_id', $user_id)->where('status', 1)->with('fund', 'redemption', 'children')->get();
    $my_conversion = $my_conversion->toArray();
    foreach ($my_conversion as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_conversion[$key]);
                    continue;
                }
            }
        } else if ($value['children']) {
            foreach ($value['children'] as $key3 => $value3) {
                if($value3['status'] == 1){
                    unset($my_conversion[$key]);
                    continue;
                }
            }
        }
    }
    $sum_2 = array_sum(array_map(function($item) {
        if($item['status'] == 1 && ($item['nav'] && $item['unit'] && $item['fund']['nav'])){
            return round(((float) $item['unit'] * (float) $item['fund']['nav']), 2);
        }
        else if($item['status'] == 1){
            return @$item['amount'];
        }
        else{
            return 0;
        }
    }, $my_conversion));

    $my_dividend = \App\Models\Dividend::where('user_id', $user_id)->where('status', 1)->with('fund', 'redemption')->get();
    $my_dividend = $my_dividend->toArray();
    foreach ($my_dividend as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_dividend[$key]);
                    continue;
                }
            }
        } 
        // else if ($value['conversions']) {
        //     foreach ($value['conversions'] as $key3 => $value3) {
        //         if($value3['status'] == 1){
        //             unset($my_investment[$key]);
        //             continue;
        //         }
        //     }
        // }
    }
    $sum_3 = array_sum(array_map(function($item) {
        if($item['status'] == 1 && ($item['nav'] && $item['unit'] && $item['fund']['nav'])){
            // return (@$item['amount']*$item['fund']['nav'])/$item['nav'];
            // return ($item['unit']*$item['fund']['nav']);
            return round(((float) $item['unit'] * (float) $item['fund']['nav']), 2);
        }
        else if($item['status'] == 1){
            return @$item['amount'];
        }
        else{
            return 0;
        }
    }, $my_dividend));

    $sum = $sum_1 + $sum_2 + $sum_3;
    return number_format(round($sum, 2));
}

function userUnitSum($user_id)
{
    $my_investment = \App\Models\Investment::where('user_id', $user_id)->where('status', 1)->with('redemption', 'conversions')->get();
    $my_investment = $my_investment->toArray();
    foreach ($my_investment as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_investment[$key]);
                    continue;
                }
            }
        } else if ($value['conversions']) {
            foreach ($value['conversions'] as $key3 => $value3) {
                if($value3['status'] == 1){
                    unset($my_investment[$key]);
                    continue;
                }
            }
        }
    }
    $sum_1 = array_sum(array_map(function($item) {
        if ($item['unit']) {
            return round(((float) $item['unit']), 2);
        } else {
            return 0;
        }
    }, $my_investment));

    $my_conversion = \App\Models\Conversion::where('user_id', $user_id)->where('status', 1)->with('redemption', 'children')->get();
    $my_conversion = $my_conversion->toArray();
    foreach ($my_conversion as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_conversion[$key]);
                }
            }
        } else if ($value['children']) {
            foreach ($value['children'] as $key3 => $value3) {
                if($value3['status'] == 1){
                    unset($my_conversion[$key]);
                    continue;
                }
            }
        }
    }
    $sum_2 = array_sum(array_map(function($item) {
        if ($item['unit']) {
            return round(((float) $item['unit']), 2);
        } else {
            return 0;
        }
    }, $my_conversion));

    $my_dividend = \App\Models\Dividend::where('user_id', $user_id)->where('status', 1)->with('redemption')->get();
    $my_dividend = $my_dividend->toArray();
    foreach ($my_dividend as $key => $value) {
        if ($value['redemption']) {
            foreach ($value['redemption'] as $key2 => $value2) {
                if($value2['status'] == 1){
                    unset($my_dividend[$key]);
                    continue;
                }
            }
        } 
        // else if ($value['conversions']) {
        //     foreach ($value['conversions'] as $key3 => $value3) {
        //         if($value3['status'] == 1){
        //             unset($my_investment[$key]);
        //             continue;
        //         }
        //     }
        // }
    }
    $sum_3 = array_sum(array_map(function($item) {
        if ($item['unit']) {
            return round(((float) $item['unit']), 2);
        } else {
            return 0;
        }
    }, $my_dividend));

    $sum = $sum_1 + $sum_2 + $sum_3;
    return round($sum, 2);
}

function sendEmail($body,$url)
{

    $headers = [
        'Content-Type: application/json',
    ];

    $encodedData = json_encode($body);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);     
    curl_close($ch);
    return true;
}
function cal_percentage($total_investment_amount, $current_balance) {
    $count1 = $total_investment_amount / $current_balance;
    $count2 = $count1 * 100;
    return round($count2,2);
  }
  
function marketingNotification($fcm_token, $fcm_data,$filename, $marketingNoti, $type,$flag){

    $url       = 'https://fcm.googleapis.com/fcm/send';
    $FcmToken  = $fcm_token;
    // $serverKey = 'AAAActxjvSw:APA91bEXhzT5gTTqoJOIuMqhE0KXwcyFMGy5KQuk3yyB5iBiMvE2SiM5iZcs7Hq_V9V67pCJD_ICphQowXJMy6inPc3N0HEIeuFxp0PHr66Ix4PCTGfv5faX6YzFMwARG1ElvpAn0O89';
    $serverKey = 'AAAAP0lM3xM:APA91bHIcQJbQO90zNLWWGTBoH95nNr2ccUrYZafcLz14XiyJDyDsJT33DzZWn7bLOO7Zcp_25G4AUOKt11dKLAOS9sj_jFLWMhI9L-r-1g2ngAcOwXvPahRiGf5Fy7ZnVrisd9BWvt-';

        $noti = [
            "registration_ids" => $FcmToken,
            "notification" => [
                 "title" => $marketingNoti['title'],
                 "body"  => $marketingNoti['message'],
                 "sound" => "default",
                 "image" => '/'.$filename ?? ''
            ],
            "priority" => "high",
            "data"     => [
                "title"   => $marketingNoti['title'],
                "message" => $marketingNoti['message'],
                "sound"   => "default",
                "image"   => '/'.$filename ?? ''
           ],
        ];
    if($flag)
    {
        $noti_data = [];
        foreach($fcm_data->toArray() as $key =>  $value){
            $noti_data[$key]['user_id'] = $value['user_id'];
            $noti_encode = $noti;
            $noti_encode['registration_ids'] = json_decode($value['fcm_token']);
            $noti_data[$key]['data'] = json_encode($noti_encode);
            $noti_data[$key]['type'] = 'marketing';
            $noti_data[$key]['created_at'] = date('Y-m-d H:i:s');
        }
        // echo'<pre>';
        // print_r($noti);
        // die;
        \App\Models\Notification::insert($noti_data);
    }
    $noti = [
        "registration_ids" => $FcmToken,
        "notification" => [
             "title" => $marketingNoti['title'],
             "body"  => $marketingNoti['message'],
             "sound" => "default",
             "image" => $marketingNoti['image'] ?? ''
        ],
        "priority" => "high",
        "data"     => [
            "title"   => $marketingNoti['title'],
            "message" => $marketingNoti['message'],
            "sound"   => "default",
            "image"   => $marketingNoti['image'] ?? ''
       ],
    ];
    $encodedData = json_encode($noti);
    // if (!@fcm_token) {
    //     return true;
    // }

    $headers = [
        'Authorization:key=' . $serverKey,
        'Content-Type: application/json',
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    // Disabling SSL Certificate support temporarly
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);        
    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    // Execute post
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }        
    curl_close($ch);
    return true;
}
