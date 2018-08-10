<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreParticipator;
use App\Participator;
use App\YueDan;
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
    public function store(StoreParticipator $request,$id)
    {
        //token验证
        $token = isset($_GET['api_token'])?$_GET['api_token']:'';
        $user_id = DB::table("users")->where('api_token',$token)->pluck('id')->first();

        if(!$user_id){
            return ['status'=>1,'message'=>'用户已过期或不存在'];
        }

        //跟约角色默认为2
        $data['join_role']=2;
        $data['user_id']=$user_id;
        $data['join_time'] = time();
        //根据约单id查头像分组
        $avatar_group = YueDan::where('id','=',$id)->select('avatar_group')->get();
        //查分组
        $group = Participator::getAvatarGroup($avatar_group);
        //随机生成头像
        $data['avatar_url'] = Participator::getAvatarUrl($group);
        //随机生成名称
        $data['name'] = Participator::getRandName();
        //确认跟约
        $res = Participator::insert($data);
        if ($res) {
            //成功了将数据返回
            $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
            $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
            return response()->json(['sponsor'=>$sponsor,'genyue'=>$genyue]);
        } else {
            return response()->json(['code' => -1, 'message' => '确定跟约失败']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Participator  $participator
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //token验证
        $token = isset($_GET['api_token'])?$_GET['api_token']:'';
        $user_id = DB::table("users")->where('api_token',$token)->pluck('id')->first();

        if(!$user_id){
            return ['status'=>1,'message'=>'用户已过期或不存在'];
        }
        //get方式 跟约人列表（基于某个约单下的跟约者）1为发起者,'2'为跟约人
        $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
        $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
        return response()->json(['sponsor'=>$sponsor,'genyue'=>$genyue]);
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
    public function destroy($id)
    {
        //token验证
        $token = isset($_GET['api_token'])?$_GET['api_token']:'';
        $user_id = DB::table("users")->where('api_token',$token)->pluck('id')->first();

        if(!$user_id){
            return ['status'=>1,'message'=>'用户已过期或不存在'];
        }

        //取消跟约
        $res = DB::delete('delete from participator where user_id=?',[$user_id]);
        if ($res) {
            //成功了将数据返回
            $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
            $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
            return response()->json(['sponsor'=>$sponsor,'genyue'=>$genyue]);
        } else {
            return response()->json(['code' => -1, 'message' => '取消跟约失败']);
        }

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
