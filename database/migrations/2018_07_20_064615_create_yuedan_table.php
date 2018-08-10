<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYuedanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yuedan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('openid', 100)->unique()->comment('用户的唯一标识');
            $table->string('nickname')->default('')->comment('用户昵称');
            $table->tinyInteger('sex')->default(0)->comment('用户的性别，值为1时是男性，值为2时是女性，值为0时是未知');
            $table->string('province')->default('')->comment('用户个人资料填写的省份');
            $table->string('city')->default('')->comment('普通用户个人资料填写的城市');
            $table->string('country', 20)->default('CN')->comment('国家，如中国为CN');
            $table->string('headimgurl')->default('')->comment('用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。');
            $table->string('unionid', 100)->default('')->comment('只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。');
            $table->string('api_token', 50)->index()->comment('api token');
            $table->smallInteger('expires_in')->default(7200)->comment('接口调用凭证超时时间，单位（秒）');
            $table->smallInteger('credit_score')->default(200)->comment('信用分');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('yuedan');
    }
}
