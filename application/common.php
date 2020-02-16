<?php
// 应用公共文件
use think\Session;


function godie($type='',$message='',$tabId=''){
    if($type=='tab'){
        $script='<script>alert("'.$message.'\n请刷新主页面！");';
        if($tabId!='') $script.='headerVm.removeTab("'.$tabId.'");';
        $script.='</script>';
    }else{
        $script='<script>alert("请您从正确途径访问系统哦~\n感谢您的配合！");history.go(-1);</script>';
    }
    
    die($script);
}


function checkTabloadToken($token='',$tabId=''){
    $tokenSess=Session::get('tabloadToken');
    
    if($tokenSess!=[]){
        if(!isset($tokenSess['value']) || $tokenSess['value']!=$token) godie('tab','非法的页面Token！',$tabId);
        if(!isset($tokenSess['expireTime']) || $tokenSess['expireTime']<=time()) godie('tab','页面Token已过期！',$tabId);
    }else{
        godie('tab','页面Token不存在！',$tabId);
    }
}


function checkSensOprToken($token=''){
	$sessToken=Session::get('sensOprToken');

	if($sessToken!=$token || $sessToken==null){
		returnAjaxData(403002,'Invaild sensitive operation token',[],'接口令牌无效<br>请刷新页面重试');
	}else{
		return true;
	}
}


/**
 * 获取HTTP-Get数据
 * @param  string  $dataName  参数名称
 * @param  integer $allowNull 是否允许为空（0/1）
 * @param  integer $isAjax    是否为ajax请求（0/1）
 * @param  integer $errorCode isAjax=1时，参数缺失提醒的错误码
 * @param  string  $errorMsg  isAjax=1时，参数缺失提醒的错误内容
 * @param  string  $errorTips isAjax=1时，参数缺失提醒的错误汉字提醒
 * @return string             参数内容
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-12-29
 */
function inputGet($dataName="",$allowNull=0,$isAjax=0,$errorCode=0,$errorMsg='',$errorTips='')
{
	$errorMsg=$errorMsg!=''?$errorMsg:'Lack parameter';
	$errorTips=$errorTips!=''?$errorTips:'参数['.$dataName.']缺失';
	
	if(isset($_GET[$dataName])){
		if($allowNull!=1 && $_GET[$dataName]==""){
			return $isAjax==1?returnAjaxData($errorCode,$errorMsg,[],$errorTips):godie();
		}else{
			return $_GET[$dataName];
		}
	}elseif($allowNull==1){
		return;
	}else{
		return $isAjax==1?returnAjaxData($errorCode,$errorMsg,[],$errorTips):godie();
	}
}


/**
 * 获取HTTP-Post数据
 * @param  string  $dataName  参数名称
 * @param  integer $allowNull 是否允许为空（0/1）
 * @param  integer $isAjax    是否为ajax请求（0/1）
 * @param  integer $errorCode isAjax=1时，参数缺失提醒的错误码
 * @param  string  $errorMsg  isAjax=1时，参数缺失提醒的错误内容
 * @param  string  $errorTips isAjax=1时，参数缺失提醒的错误汉字提醒
 * @return string             参数内容
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-12-23
 */
function inputPost($dataName="",$allowNull=0,$isAjax=0,$errorCode=0,$errorMsg='Lack parameter',$errorTips='')
{
	$errorTips=$errorTips!=''?$errorTips:'参数['.$dataName.']缺失';

	if(isset($_POST[$dataName])){
		if($allowNull!=1 && $_POST[$dataName]==""){
			return $isAjax==1?returnAjaxData($errorCode,$errorMsg,[],$errorTips):godie();
		}else{
			return $_POST[$dataName];
		}
	}elseif($allowNull==1){
		return;
	}else{
		return $isAjax==1?returnAjaxData($errorCode,$errorMsg,[],$errorTips):godie();
	}
}


/**
 * ajax返回统一标准json字符串
 * @param  integer $code    状态码
 * @param  string  $message 英文提示内容
 * @param  array   $data    返回数据
 * @param  string  $tips    中文提示语
 * @return string           json字符串（自动结束并输出）
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-11-17
 */
function returnAjaxData($code=0,$message='',$data=[],$tips=''){
	$ret=array('code'=>$code,'message'=>$message,'data'=>$data,'tips'=>$tips,'requestTime'=>time());
	die(json_encode($ret));
}


/**
 * 生成随机36位UUID
 * @return string 随机UUID
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-11-17
 */
function makeUUID(){
	$hash=sha1(time().md5(mt_rand(123456789,time())));
	return substr($hash,0,8).'-'.substr($hash,8,4).'-'.substr($hash,12,4).'-'.substr($hash,16,4).'-'.substr($hash,20,12);
}


/**
 * header自动跳转URL
 * @param string $path url
 */
function gotourl($path=''){
	die(header('location:'.$path));
}


/**
 * curl请求封装函数
 * @param  string  $url          请求URL
 * @param  string  $type         请求类型(get/post)
 * @param  array   $postData     需要POST的数据
 * @param  string  $postDataType POST数据类型(array/json)
 * @param  integer $timeout      超时秒数
 * @param  string  $userAgent    UserAgent
 * @return string                返回结果
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-11-17
 * @version 2019-11-17
 */
function curl($url,$type='get',$postData=array(),$postDataType='array',$timeout=5,$userAgent='Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36'){
	if($url=='' || $timeout <=0){
		return false;
	}

	$ch=curl_init((string)$url);
	curl_setopt($ch,CURLOPT_HEADER,false);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_TIMEOUT,(int)$timeout);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($ch,CURLOPT_USERAGENT,$userAgent);

	if($type=='post'){
		if($postData==array()){
			return false;
		}else if($postDataType=='json'){
			$postData=json_encode($postData);
			curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type:application/json'));
		}

		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
	}

	$rtn=curl_exec($ch);
	if($rtn===false) $rtn=curl_errno($ch);
	curl_close($ch);

	return $rtn;
}


/**
 * getIP 获取IP地址
 * @return string IP地址
 */
function getIP()
{
	if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$cip = $_SERVER["HTTP_CLIENT_IP"];
	}
	elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	}
	elseif(!empty($_SERVER["REMOTE_ADDR"])){
		$cip = $_SERVER["REMOTE_ADDR"];
	}
	else{
		$cip = "0.0.0.0";
	}
	return $cip;
}
