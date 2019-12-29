<?php
/* *
 * 功能：支付宝电脑网站支付
 * 版本：2.0
 * 修改日期：2017-05-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

class AlipayOauthService {

	//支付宝授权地址
	public $oauth_url = "https://openauth.alipay.com/oauth2/publicAppAuthorize.htm";

	//支付宝网关地址
	public $gateway_url = "https://openapi.alipay.com/gateway.do";

	//授权回调地址
	public $redirect_url;

	//scope auth_base静默授权;auth_user获取用户信息
	public $scope = 'auth_base';
	//auth_code
	public $auth_code;

	//商户私钥
	public $private_key;

	//私钥文件路径
	public $rsaPrivateKeyFilePath;

	//应用id
	public $appid;

	//编码格式
	public $charset = "UTF-8";
	//文件编码
	private $fileCharset = "UTF-8";
	// 表单提交字符集编码
	public $postCharset = "UTF-8";

	public $token = NULL;
	
	//返回数据格式
	public $format = "json";

	//签名方式
	public $signtype = "RSA2";

	function __construct($alipay_config){
		$this->oauth_url = $alipay_config['oauth_url'];
		$this->redirect_url = $alipay_config['redirect_url'];
		$this->gateway_url = $alipay_config['gatewayUrl'];
		$this->appid = $alipay_config['app_id'];
		$this->private_key = $alipay_config['merchant_private_key'];
		$this->charset = $alipay_config['charset'];
		$this->signtype=$alipay_config['sign_type'];
		if($alipay_config['scope']){
			$this->scope = $alipay_config['scope'];
		}

		if(empty($this->appid)||trim($this->appid)==""){
			throw new Exception("appid should not be NULL!");
		}
		if(empty($this->private_key)||trim($this->private_key)==""){
			throw new Exception("private_key should not be NULL!");
		}
		if(empty($this->charset)||trim($this->charset)==""){
			throw new Exception("charset should not be NULL!");
		}
		if(empty($this->gateway_url)||trim($this->gateway_url)==""){
			throw new Exception("gateway_url should not be NULL!");
		}
		if(empty($this->oauth_url)||trim($this->oauth_url)==""){
			throw new Exception("oauth_url should not be NULL!");
		}

	}

	/**
	 * 获取授权跳转地址
 	 */
	function oauth() {
	
		$params["app_id"] = $this->appid;
        $params["redirect_uri"] = $this->redirect_url;
        $params["scope"] = $this->scope;
        $params["state"] = uniqid(mt_rand(100000,999999), true);
        session_start();
        $_SESSION["state"] = $params["state"];
        $bizString = http_build_query($params);
        return $this->oauth_url."?".$bizString;
	}
	/**
	 * 获取auth_code
	 * @return [type] [description]
	 */
	function auth_code(){
        session_start();
		if(!isset($_SESSION['state']) || $_SESSION['state'] != $_GET['state']){
			throw new Exception("The state does not match. You may be a victim of CSRF.");
		}
		$this->auth_code = $_GET['auth_code'];
		return $_GET['auth_code'];
	}
	/**
	 * 使用auth_code获取access_token
	 * @param  string $auth_code [description]
	 * @return [type]            [description]
	 */
	function get_token($auth_code='')
	{
		if($auth_code){
			$this->auth_code = $auth_code;
		}
		$params = array(
            //公共参数
            'app_id' => $this->appid,
            'method' => 'alipay.system.oauth.token',//接口名称
            'format' => 'JSON',
            'charset'=>$this->charset,
            'sign_type'=>$this->signtype,
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'grant_type'=>'authorization_code',
            'code'=>$this->auth_code,
        );
        $params["sign"] = $this->generateSign($params, $this->signtype);
        $result = $this->curlPost($this->gateway_url,$params);
        $result = iconv('GBK','UTF-8',$result);
        $res = json_decode($result,true);
        return $res['alipay_system_oauth_token_response']['access_token'] ?? '';
	}
	/**
	 * 使用access_token 获取用户信息
	 * @param  string $token [description]
	 * @return [type]        [description]
	 */
	function get_user_info($token='')
	{
		$params = array(
            //公共参数
            'app_id' => $this->appid,
            'method' => 'alipay.user.info.share',//接口名称
            'format' => 'JSON',
            'charset'=>$this->charset,
            'sign_type'=>$this->signtype,
            'timestamp'=>date('Y-m-d H:i:s'),
            'version'=>'1.0',
            'grant_type'=>'authorization_code',
            'code'=>$this->auth_code,
            'auth_token'=>$token,
        );
        $params["sign"] = $this->generateSign($params, $this->signtype);
        $result = $this->curlPost($this->gateway_url,$params);
        $result = iconv('GBK','UTF-8',$result);
        $res = json_decode($result,true);
        if(!isset($res['alipay_user_info_share_response']['user_id'])){
        	throw new \Exception('请求错误');
        }
        return $res['alipay_user_info_share_response'] ?? [];
	}
	/**
	 * 发送请求
	 * @param  string $url      [description]
	 * @param  string $postData [description]
	 * @param  array  $options  [description]
	 * @return [type]           [description]
	 */
	public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public function generateSign($params, $signType = "RSA") {
		return $this->sign($this->getSignContent($params), $signType);
	}
	public function getSignContent($params) {
		ksort($params);

		$stringToBeSigned = "";
		$i = 0;
		foreach ($params as $k => $v) {
			if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

				// 转换成目标字符集
				$v = $this->characet($v, $this->postCharset);

				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}

		unset ($k, $v);
		return $stringToBeSigned;
	}

	protected function sign($data, $signType = "RSA") {
		if($this->checkEmpty($this->rsaPrivateKeyFilePath)){
			$priKey=$this->private_key;
			$res = "-----BEGIN RSA PRIVATE KEY-----\n" .
				wordwrap($priKey, 64, "\n", true) .
				"\n-----END RSA PRIVATE KEY-----";
		}else {
			$priKey = file_get_contents($this->rsaPrivateKeyFilePath);
			$res = openssl_get_privatekey($priKey);
		}

		($res) or die('您使用的私钥格式错误，请检查RSA私钥配置'); 

		if ("RSA2" == $signType) {
			openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
		} else {
			openssl_sign($data, $sign, $res);
		}

		if(!$this->checkEmpty($this->rsaPrivateKeyFilePath)){
			openssl_free_key($res);
		}
		$sign = base64_encode($sign);
		return $sign;
	}

	/**
	 * 校验$value是否非空
	 *  if not set ,return true;
	 *    if is null , return true;
	 **/
	protected function checkEmpty($value) {
		if (!isset($value))
			return true;
		if ($value === null)
			return true;
		if (trim($value) === "")
			return true;

		return false;
	}

	/**
	 * 转换字符集编码
	 * @param $data
	 * @param $targetCharset
	 * @return string
	 */
	function characet($data, $targetCharset) {
		
		if (!empty($data)) {
			$fileType = $this->fileCharset;
			if (strcasecmp($fileType, $targetCharset) != 0) {
				$data = mb_convert_encoding($data, $targetCharset, $fileType);
				//$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
			}
		}


		return $data;
	}
}

?>