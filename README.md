# FoodXink Backend
**Introduce and api document**
### 微信第三方（开发者服务器）登录
说明：把`login`得到的`code`和`getUserInfo`得到的数据合并一起发给第三方服务器，取得token
开发者服务器以`code`换取 用户唯一标识`openid` 和 会话密钥`session_key`。
校验通过会返回后续鉴权需要的`api_token`

注意：**前端得到api_token需要保存上，供后续请求使用**
统一域名`http://117.50.43.67/`

`POST ~/api/auth/oauth`

#### 请求参数

|参数|必选|类型|说明|
|:-----:|:-------:|:-----:|:-----:|
|js_code |ture |String|wx.login获取的临时凭证code |
|userInfo |true |OBJECT |用户信息对象，不包含 openid 等敏感信息|
|rawData |true |String |不包括敏感信息的原始数据字符串，用于计算签名。|
|signature |true |String |使用 sha1( rawData + sessionkey ) 得到字符串，用于校验用户信息|
|encryptedData |true |String |包括敏感数据在内的完整用户信息的加密数据|
|iv |true |String |加密算法的初始向量|

#### 返回说明

```json
//正常返回的JSON数据包
{
      "code": 0,
      "message": "success",
      "api_token": "75e81ceda165f4ffa64f4068af58c64b8f54b88c"
}

//错误时返回JSON数据包(示例为Code无效)
{
    "code": -1,
    "message": "invalid code"
}

```

 
 示例：
```javascript
var js_code
    // 登录
    wx.login({
      success: res => {
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        js_code = res.code
        console.log(res.code)
      }
    })
    // 获取用户信息
    wx.getSetting({
      success: res => {
        if (res.authSetting['scope.userInfo']) {
          // 已经授权，可以直接调用 getUserInfo 获取头像昵称，不会弹框
          wx.getUserInfo({
            success: res => {
              // 可以将 res 发送给后台解码出 unionId
              res.js_code = js_code
              // 把login得到的code和getUserInfo得到的数据合并一起发给第三方服务器，取得token
              wx.request({
                url: `http://117.50.43.67/api/auth/oauth`,
                method: 'POST',
                data: res,
                success: function (res) {
                  console.log(res.data)
                  wx.setStorage({
                    key: "api_token",
                    data: res.data.api_token
                  })
                },
                fail: function (res) {
                  console.log(res)
                  // ...
                }
              })
              this.globalData.userInfo = res.userInfo
              console.log(res.userInfo)
              wx.getStorage({
                key: 'api_token',
                success: function (res) {
                  console.log(res.data)
                }
              })


              // 由于 getUserInfo 是网络请求，可能会在 Page.onLoad 之后才返回
              // 所以此处加入 callback 以防止这种情况
              if (this.userInfoReadyCallback) {
                this.userInfoReadyCallback(res)
              }
            }
          })
        }
      }
    })
```
### 我的约单和历史约单

说明：返回列表是该用户自己发起的和参与的约单

我的约单`GET ~/api/v1/yuedans?api_token={api_token}`
历史约单`GET ~/api/v1/yuedan-history?api_token={api_token}`

#### 请求参数
只需要`api_token`

#### 返回说明

包含分页数据，每页默认5个数据

```json
// 成功返回
{
    "data": [
        {
            "yuedan_id": 10,
            "title": "地下美食城6",
            "time": {
                "date": "2018/07/15",
                "week": "星期日",
                "hour": "09:06"
            },
            "location": {
                "address": "望京南地铁站口地下美食城",
                "latitude": "116.489089",
                "longitude": "39.990423",
                "name": "美食城"
            },
            "img": "https://ps.ssl.qhmsg.com/sdr/200_200_/t01affa255a29b179c6.jpg"
        },
        {
            "yuedan_id": 11,
            "title": "地下美食城7",
            "time": {
                "date": "2018/07/15",
                "week": "星期日",
                "hour": "09:06"
            },
            "location": {
                "address": "望京南地铁站口地下美食城",
                "latitude": "116.489089",
                "longitude": "39.990423",
                "name": "美食城"
            },
            "img": "https://ps.ssl.qhmsg.com/sdr/200_200_/t01affa255a29b179c6.jpg"
        }
    ],
    "links": {
        "first": "http://foodxink.com/api/v1/yuedans?page=1",
        "last": null,
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "path": "http://foodxink.com/api/v1/yuedans",
        "per_page": 5,
        "to": 2
    }
}

```

### 新建约单

说明：地址需要获取经纬度等

`POST ~/api/v1/yuedan?api_token={api_token}`
#### 请求参数

|参数|必选|类型|说明|
|:-----:|:-------:|:-----:|:-----:|
|title |ture |String|约单标题 |
|description |true |String |约单描述|
|close_time |true |Int |该约单截止时间|
|eat_time |true |Int |吃饭时间|
|address |true |String |吃饭地址|
|latitude |true |String |加密算法的初始向量|
|longitude |true |Int |吃饭地址的纬度|
|location_name |true |Int |吃饭地址的经度|
|image |false |JsonString |多张图片地址组成的数组的序列化字符串|


#### 返回说明

```json
// 成功时返回
{
    "code": 0,
    "message": "success",
    "yue_id": 2 //新建的约单的id，用于请求约单详情
}

//错误时返回JSON数据包(例保存失败)
{
    "code": -1,
    "message": "保存失败"
}
```

### 上传图片

说明：该接口支持单图片上传

`POST ~/api/v1/yuedan/uploadimg?api_token={api_token}`
#### 请求参数

file类型
文件对应的 key ：upload-img


#### 返回说明


```json
// 成功时返回
{
    "code": 0,
    "message": "success",
    "imgUrl": "/storage/img/2018-07-19/Q4rl4sGS6MVtgDe38TUN4doLAFjuYMvqvqILhpmK.jpeg"
}

//错误时返回`
{
    "code": -1,
    "message": "xxx"
}
```
### 更多活动

说明：该接口获取一天之内的活动

`GET ~/api/v1/yuedan/more?api_token={api_token}`
#### 请求参数

#### 返回说明
```json
// 成功时返回
{
	"status": 0,
	"data": [{
		"id": 1,
		"title": "\u6735\u9890\u6392\u9aa8",
		"eat_time": 0,
		"latitude": "39.989680",
		"longitude": "116.476650",
		"image": "http:\/\/suo.im\/5lIls6"
	},
	{
		"id": 2,
		"title": "\u7709\u5dde\u697c\u793e",
		"eat_time": 0,
		"latitude": "39.990960",
		"longitude": "116.484260",
		"image": "http:\/\/suo.im\/4Z5twK"
	}]
}
```
### 活动详情

说明：该接口获取的详情

`GET ~/api/v1/yuedan/yuedan_id?api_token={api_token}`
#### 请求参数
yuedan_id:约单id
#### 返回说明
```json
// 成功时返回
{
	"status": 0,
	"data": {
		"id": 1,
		"sponsor_id": 1,
		"title": "\u6735\u9890\u6392\u9aa8",
		"description": "\u6682\u65e0\u4fe1\u606f",
		"close_time": 0,
		"eat_time": 0,
		"address": "\u5317\u4eac\u9152\u4ed9\u6865",
		"latitude": "39.989680",
		"longitude": "116.476650",
		"location_name": "",
		"image": null,
		"create_time": 1532269443
	}
}
```
### 赴约

说明：该接口是否赴约

`PUT ~/api/v1/participator/join/yuedan_id?api_token={api_token}`
#### 请求参数
yuedan_id:约单id
#### 返回说明
```json
// 成功时返回
{
    "code": 0,
    "message": "赴约成功"
}
//赴约失败`
{
    "code": -1,
    "message": "赴约失败"
}
```
