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

