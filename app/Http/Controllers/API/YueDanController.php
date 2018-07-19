<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\StoreYuedanPost;
use App\Participator;
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
        return YueDan::select(['title'])->simplePaginate(5);
        //return YueDan::paginate(5);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreYuedanPost $request)
    {
        //
        $data = $request->all();
        $data['create_time'] = time();

        // 验证 https://docs.golaravel.com/docs/4.1/validation/


        DB::beginTransaction();

        try {
            $avatar_group = Participator::getAvatarGroup();

            $data['sponsor_id'] = \Auth::guard('api')->id();
            $data['avatar_group'] = $avatar_group;

            $res = YueDan::create($data);
            $par_data = [
                'yuedan_id' => $res->id,
                'user_id' => $data['sponsor_id'],
                'avatar_url' => Participator::getAvatarUrl($avatar_group),
                'join_time' => time(),
                'name' => Participator::getRandName()
            ];

            Participator::create($par_data);
            DB::commit();

            return response()->json(['code' => 0, 'message'=>'success']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => -1, 'message' => $e->getMessage()], 500);
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
}
