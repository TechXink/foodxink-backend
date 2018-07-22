<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class YueDan extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'yuedan';

    protected $fillable = ['sponsor_id','title','description','close_time',
        'eat_time','address','latitude','longitude','location_name','image','create_time','avatar_group'];

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * 应该被转换成原生类型的属性。
     *
     * @var array
     */
    protected $casts = [
        'image' => 'array',
    ];

    public static function byParticipator($sponsor_id)
    {
        // return $this->hasMany('App\Participator', 'yuedan_id', 'id');


        return YueDan::groupBy(['yuedan.id'])->select(['sponsor_id','title','description','close_time',
            'eat_time','address','latitude','longitude','location_name','image'])->where('sponsor_id', $sponsor_id)
        ->join('participator', 'sponsor_id', '=', 'user_id')
        ;//->get();
    }


}