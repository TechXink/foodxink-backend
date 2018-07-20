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
        var_dump($request->all());
        var_dump($id);
        var_dump($user_id);
        die;
        //Participator::updated(['is_join'=>1]);
        $bool = DB::table("Participator")->where('user_id',1)->update(['is_join'=>1]);
        if($bool || empty($bool)){
            return ['status'=>0,'message'=>'赴约成功'];
        }else{
            return ['status'=>1,'message'=>'赴约失败'];
        }
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
    public function join($id,$user_id)
    {
        //Participator::updated(['is_join'=>1]);
        $bool = DB::table("Participator")->where(['yuedan_id'=>$id,'user_id'=>$user_id])->update(['is_join'=>1]);
        if($bool || empty($bool)){
            return ['status'=>0,'message'=>'赴约成功'];
        }else{
            return ['status'=>1,'message'=>'赴约失败'];
        }
    }
}
