<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class YueDan extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'yuedan';

    protected $fillable = ['sponsor_id'];

    const CREATED_AT = null;
    const UPDATED_AT = null;
}