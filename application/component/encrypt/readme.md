# encrypt 说明

## 传输数据结构含义

|参数 | 值|说明|
|------------- |:-------------:| -----:|
|itboye|传输数据内容|传输数据内容|
|alg|加解密算法|md5_v2|
|client_id|客户端标识|分配的id|
|app_version|客户端应用版本|应用app的版本，数字形式|
|app_type|客户端类型|ios、android|

### 内容数据结构含义 从传输数据的itboye解密而来

|参数|	 值|	说明|
|------------- |:-------------:| -----:|
|notify_id |请求id|随机|
|time|时间戳|客户端请求时间|
|data|内容|服务参数|
|type|请求服务类型|服务类型|
|api_ver|请求服务版本|从100开始|
|sign|签名|time.type.datagetClientSecret().notify_id|


## 数据加密与解密

### 简单加解密

使用到的函数 :
1.json_encode 对数据转换为json
2.json_decode json字符串转换为数据

3.base64_encode 对数据进行base64处理
4.base64_decode 对base64字符串还原

第一步: 请求参数 放入一个键值对的数组设为 data_array

第三步: 生成加密数据

目前的 加密后的 data =  base64_encode(base64_encode(json_encode(data_array)))
加密后的 data 即 公共参数的data

数据签名

md5签名

第一步: 准备使用到算法

md5算法（32位）
第二步: 准备参数 

time 时间戳 带毫秒，公共参数的time参数 一个数字型的字符串，标明该请求的时间,防止过期请求的重复请求
type 请求接口类型 ，公共参数的type参数 一个字符串,  标明该请求需要的服务
data 对请求参数加密过后的密文字符串,               标明该请求所需的参数
client_secret ,管理员分配的client_id对应的client_secret (请向 管理员 要取) 标明该请求对应的应用
notify_id 消息id,公共参数的notify_id参数 一个字符串，该请求的ID
待加密字符串 = time ＋ type ＋ data ＋ client_secret ＋ notify_id （字符串的拼接）
第三步: 生成sign参数

text = 第二步中生成的 待加密字符串

sign = md5( text );
