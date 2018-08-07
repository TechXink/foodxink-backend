<?php
/**
 * Created by PhpStorm.
 * User: liah
 * Date: 2018/7/19
 * Time: 5:27 PM
 */

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\User;
use App\WXBizDataCrypt;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public $appid;

    public $secret;

    public function __construct()
    {
        $this->appid = env('WEIXIN_KEY');
        $this->secret = env('WEIXIN_SECRET');

    }

    /**
     * 用户点击微信登录按钮后，调用此方法请求微信接口
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function oauth(Request $request)
    {
        $res_data = $request->all();
        # 省事不验证了

        $xin_data = $res_data['data'];

        # signature
        $signature = $xin_data['signature'];

        $js_code = $xin_data['js_code'];

        # $encryptedData = $xin_data['encryptedData'];
        # $iv = $xin_data['iv'];

        $rawData = $xin_data['rawData'];

        $verify = 'https://api.weixin.qq.com/sns/jscode2session?appid='
            . $this->appid . '&secret=' . $this->secret . '&js_code=' .
            $js_code . '&grant_type=authorization_code';

        $data = $this->curlGet($verify, 'get');

        $data = json_decode($data, true);

        if (empty($data['openid'])) {
            return response()->json(['code' => -1, 'message' => $data['"errmsg"']], 400);
        }
        $sessionKey = $data['session_key'];

        $signature2 = sha1($rawData.$sessionKey);

        if ($signature2 !== $signature) {
            return response()->json(['code' => -1, 'message' => 'signature 不一致'], 400);
        }

        # 进行业务逻辑

        $user = User::where('openid', $data['openid'])->get()->first();
        if ($user) {
            # 返回api_token
            $api_token = $user['api_token'];
        } else {
            # 保存用户
            $userInfo = $xin_data['userInfo'];
            $userInfo['openid'] = $data['openid'];
            $userInfo['api_token'] = sha1($signature);
            $userInfo['nickname'] = $userInfo['nickName'];
            $userInfo['sex'] = $userInfo['gender'];
            $userInfo['headimgurl'] = $userInfo['avatarUrl'];
            try {

                User::create($userInfo);

                $api_token = $userInfo['api_token'];
            } catch (\Exception $e) {
                return response()->json(['code' => -1, 'message' => $e->getMessage()], 500);
            }

        }
        return response()->json(['code' => 0, 'message' => 'success', 'api_token' => $api_token]);


        /*

        # 解密
        $pc = new WXBizDataCrypt($this->appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv, $resData );

        if ($errCode == 0) {
            print($resData . "\n");
        } else {
            print($errCode . "\n");
        }
        */

    }

    # 微信的回调地址
    public function callback(Request $request)
    {

        // 在这里可以获取到用户在微信的资料

        // 接下来处理相关的业务逻辑

    }


    public function curlGet($url, $method, $post_data = 0)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        } elseif ($method == 'get') {
            curl_setopt($ch, CURLOPT_HEADER, 0);
        }
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;

    }

}