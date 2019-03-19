<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class SchedulingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function insertDomain(){
        $getDomain=DB::connection('mongodb_old')->collection('note')->where('type','domain')
            ->where('index','<',4)->limit(1000)->get();
        foreach ($getDomain as $item){
            $checkDomain=DB::connection('mongodb')->collection('mongo_domain')
                ->where('base_64',base64_encode($item['domain']))->first();
            if(empty($checkDomain['domain'])){
                DB::connection('mongodb')->collection('mongo_domain')
                    ->insertGetId(
                        [
                            'domain' => $item['domain'],
                            'base_64' => base64_encode($item['domain']),
                            'title'=>$item['title'],
                            'description'=>$item['description'],
                            'keywords'=>$item['keywords'],
                            'image'=>$item['image'],
                            'attribute'=>$item['attribute'],
                            'view'=>$item['view'],
                            'status'=>$item['status'],
                            'created_at'=>$item['created_at'],
                            'updated_at'=>$item['updated_at']
                        ]
                    );
                DB::connection('mongodb_old')->collection('note')->where('type','domain')
                    ->where('domain_encode',base64_encode($item['domain']))
                    ->update(['index' => 4]);
                echo 'insert success <br>';
            }else{
                DB::connection('mongodb_old')->collection('note')->where('type','domain')
                    ->where('domain_encode',base64_encode($item['domain']))
                    ->update(['index' => 4]);
                echo 'Domain exist <br>';
            }
        }
    }
}
