<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreYuedanPost;
use App\YueDan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class YueDanController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return YueDan::simplePaginate(5);
        //return YueDan::paginate(5);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\jsonResponse
     */
    public function store(StoreYuedanPost $request)
    {
        //
        $data = $request->all();
        $data['create_time'] = time();

        // 验证 https://docs.golaravel.com/docs/4.1/validation/
        $yueDan = new YueDan();

        $res = $yueDan->create($data);
        if ($res) {
            return response()->json(['code' => 0, 'message'=>'success']);

        } else {
            return response()->json(['code' => -1, 'message' => '数据保存失败']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\YueDan  $yueDan
     * @return \Illuminate\Http\Response
     */
    public function show(YueDan $yueDan, $id)
    {
        return YueDan::find($id);
        //return response()->json(['id'=>$id]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\YueDan  $yueDan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, YueDan $yueDan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\YueDan  $yueDan
     * @return \Illuminate\Http\Response
     */
    public function destroy(YueDan $yueDan)
    {
        //
    }

    /*
     * @function：发现更多
     */
    public function more(){
        //return YueDan::simplePaginate(5);
        //return YueDan::where('create_time','>',DATE_SUB(NOW(),'INTERVAL 1 HOUR'));
        $yuedanInfo = DB::select(' select id,title,eat_time,latitude,longitude,image from yuedan where create_time > unix_timestamp(DATE_SUB(NOW(),INTERVAL 1 HOUR))');
        return $yuedanInfo;
    }
}
