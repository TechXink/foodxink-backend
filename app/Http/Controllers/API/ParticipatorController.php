<?php

namespace App\Http\Controllers\API;

use App\Participator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ParticipatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Participator::simplePaginate(5);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Participator  $participator
     * @return \Illuminate\Http\Response
     */
    public function show(Participator $participator)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Participator  $participator
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id,$user_id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Participator  $participator
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $id  约饭id
     * @param  $user_id 用户id
     * @return bool
     */
    public function join($id)
    {
        //Participator::updated(['is_join'=>1]);
        $token = isset($_GET['api_token'])?$_GET['api_token']:'';
        $user_id = DB::table("users")->where('api_token',$token)->pluck('id')->first();

        if(!$user_id){
            return ['status'=>1,'message'=>'用户已过期或不存在'];
        }

        $bool = DB::table("Participator")->where(['yuedan_id'=>$id,'user_id'=>$user_id])->update(['is_join'=>1]);
        if($bool || empty($bool)){
            return ['code'=>0,'message'=>'赴约成功'];
        }else{
            return ['code'=>1,'message'=>'赴约失败'];
        }
    }
}
