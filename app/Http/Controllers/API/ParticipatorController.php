<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreParticipator;
use App\Participator;
use App\User;
use App\YueDan;
use Couchbase\UserSettings;
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
        //token验证
        $token = isset($_GET['api_token'])?$_GET['api_token']:'';
        $user_id = DB::table("users")->where('api_token',$token)->pluck('id')->first();

        if(!$user_id){
            return ['status'=>1,'message'=>'用户已过期或不存在'];
        }
        $id = $request->input('yuedan_id');

        //判断用户是否已经跟约
        $submit = Participator::where('yuedan_id','=',$id)->where('user_id',"=",$user_id)->get();
        //需要将json数据转换判断，不能直接判断
        $cunzai=json_decode($submit);

        if ($cunzai){
            return ['status'=>1,'message'=>'该用户已跟约,不能重复跟约'];
        }
        $data['yuedan_id'] = $id;
        //跟约角色默认为2
        $data['join_role']=2;
        $data['user_id']=$user_id;
        $data['join_time'] = time();
        //根据约单id查头像分组
        $avatar_group = DB::table("yuedan")->where('id','=',$id)->pluck('avatar_group')->first();

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
            //返回跟约人真实数据

            //get方式 跟约人列表（基于某个约单下的跟约者）1为发起者,'2'为跟约人
            $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
            foreach ($sponsor as $key=>$v){
                $time['data']=date("Y/m/d",$v['join_time']);
                $w=date("w",$v['join_time']);
                $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
                $time['week'] = $weekArr[$w];
                $time['hour']=date("h:i",$v['join_time']);
                $v['time']=$time;
                $v['real_information']=User::where('id','=',$user_id)->select(['id','nickname','headimgurl'])->first();
            }

            $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
            foreach ($genyue as $key=>$v){
                $time['data']=date("Y/m/d",$v['join_time']);
                $w=date("w",$v['join_time']);
                $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
                $time['week'] = $weekArr[$w];
                $time['hour']=date("h:i",$v['join_time']);
                $v['time']=$time;
                $v['real_information']=User::where('id','=',$user_id)->select(['id','nickname','headimgurl'])->first();
            }
//            var_dump($real_information);die;
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

        //根据user_id和yuedan_id判断当前这个人否为发起者
        $join_role=DB::table("participator")->where('yuedan_id','=',$id)->where('user_id','=',$user_id)->pluck('join_role')->first();

        //如果不是发起者,则判断是否跟约
        if ((isset($join_role) || !empty($join_role)) && $join_role != 1){
            $is_genyue=1;//1为已跟约
            $is_sponsor=0;//1为是发起者
        }elseif ((isset($join_role) || !empty($join_role)) && $join_role == 1){
            $is_genyue=0;
            $is_sponsor=1;
        }else{
            $is_genyue=0;
            $is_sponsor=0;
        }
        //get方式 跟约人列表（基于某个约单下的跟约者）1为发起者,'2'为跟约人
        $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
        foreach ($sponsor as $key=>$v){
            $time['data']=date("Y/m/d",$v['join_time']);
            $w=date("w",$v['join_time']);
            $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
            $time['week'] = $weekArr[$w];
            $time['hour']=date("h:i",$v['join_time']);
            $v['time']=$time;
        }

        $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
        foreach ($genyue as $key=>$v){
            $time['data']=date("Y/m/d",$v['join_time']);
            $w=date("w",$v['join_time']);
            $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
            $time['week'] = $weekArr[$w];
            $time['hour']=date("h:i",$v['join_time']);
            $v['time']=$time;
        }

        return response()->json(['sponsor'=>$sponsor,'genyue'=>$genyue,'is_genyue'=>$is_genyue,'is_sponsor'=>$is_sponsor]);
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
            //get方式 跟约人列表（基于某个约单下的跟约者）1为发起者,'2'为跟约人
            $sponsor = Participator::where('yuedan_id','=',$id)->where('join_role',1)->get();
            foreach ($sponsor as $key=>$v){
                $time['data']=date("Y/m/d",$v['join_time']);
                $w=date("w",$v['join_time']);
                $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
                $time['week'] = $weekArr[$w];
                $time['hour']=date("h:i",$v['join_time']);
                $v['time']=$time;
            }

            $genyue = Participator::where('yuedan_id','=',$id)->where('join_role',2)->get();
            foreach ($genyue as $key=>$v){
                $time['data']=date("Y/m/d",$v['join_time']);
                $w=date("w",$v['join_time']);
                $weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
                $time['week'] = $weekArr[$w];
                $time['hour']=date("h:i",$v['join_time']);
                $v['time']=$time;
            }

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
