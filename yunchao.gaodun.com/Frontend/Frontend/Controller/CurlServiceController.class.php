<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：curl控制器
// +----------------------------------------------------------------------
// | 创建时间 ：
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Controller;
use Think\Controller;
class CurlServiceController extends Controller {

	protected static $_ch; //curl资源
    protected static $url;//请求url
    protected static $query;//post 数据
    protected static $count;//有效数据总数
    protected static $DBModel;//模型
    protected static $where;//数据筛选条件
    protected static $result;//源数据
    protected static $field;//数据字段
    protected static $order;//排序方式
    protected static $page; //分页数量
    protected static $limit;//每次获取数据条数
    protected static $page_total;//分页总数
    protected static $key;//密钥key
    protected static $ymd;//密钥日期
    protected static $api_url;
    protected static $cityModel;//城市模型
    protected static $token_account = ''; //sxapi 账号
    protected static $token_pwd = '';//sxapi密码

	public function _initialize(){
        self::$where = array();
        self::$field = array();
        self::$order = 'pkid asc';
        self::$page = 1;
        self::$limit = 30;
    }

    /*
    * @function PC发送手机验证码
    * @param string:$phone 11位手机号
    * @param int:$type 类型： 1 注册账户 2 重设(找回)密码 3 验证手机,默认3
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function sendSms($phone='',$type=3){
        self::smsApiUrl();
        $bool = checkPhoneRule($phone); // 接口 $reg = '/^(1[34578])[0-9]{9}$/';
        if($bool){
            $data['ticket'] = self::getToken();
            $data['registMobile'] = $phone;
            $data['type'] = $type;
            $result = self::curlHttpWeb('sendsms',$data);
            if($result['returnCode'] == 0){
                session('verify_phone.code',$result['returnData']['registCode']);
                session('verify_phone.expiretime',NOW_TIME+33*60);
                session('verify_phone.mobile',$phone);
                return array('status'=>TRUE,'msg'=>'','code'=>0,'data'=>$result['returnData']);
            }else{
                return array('status'=>FALSE,'msg'=>$result['returnDesc'],'code'=>$result['returnCode']);
            }        
        }else{
            return array('status'=>FALSE,'msg'=>'输入合法的手机号');
        }
    }

    //获取sxapi ticket门票
    protected static function getToken(){
        $result = isset($_SESSION['token']['ticket']) ? session('token.ticket') : '';
        if(NOW_TIME > session('token.expiretime') || !$result){ //判定token过期或token值为空时，重取
            $data['authUserAcc'] = self::$token_account;
            $data['authUserPwd'] = self::$token_pwd;
            $data['authSign'] = md5(self::$token_account.self::$token_pwd.date('YmdH',$_SERVER['REQUEST_TIME']));
            $result = self::curlHttpWeb('token',$data);
            if($result['returnCode'] == 0){
                session('token.ticket',$result['returnData']['ticket']);
                session('token.expiretime',$result['returnData']['expireTime']-1200);
                return $result['returnData']['ticket'];
            }else{
                dump($result);exit();
            }
        }else{
           return $result; 
        }
    }

    //根据运行环境，返回不同的sms API地址
    protected static function smsApiUrl(){
       if(APP_ENV == 'RELEASE'){//生成环境
            self::$api_url = array(
                'sendsms' => 'http://shixiapi.gaodun.com/Account/sendSmsCode',
                'token' => 'http://shixiapi.gaodun.com/Auth/index'
            );
        }elseif(APP_ENV == 'TEST'){
            self::$api_url = array(
                'sendsms' => 'http://192.168.21.252:82/Account/sendSmsCode',
                'token' => 'http://192.168.21.252:82/Auth/index'
            ); 
        }elseif(APP_ENV == 'PRE' || APP_ENV=='FIX'){
            self::$api_url = array(
                'sendsms' => 'http://shixiapipre.gaodun.cn/Account/sendSmsCode',
                'token' => 'http://shixiapipre.gaodun.cn/Auth/index'
            ); 
        }else{
            self::$api_url = array(
                'sendsms' => 'http://192.168.21.251:82/Account/sendSmsCode',
                'token' => 'http://192.168.21.251:82/Auth/index'
            );           
        }        
    }

    /*-------- 发送手机验证码结束 -----------*/ 

    /*----------------- curl方法开始 ------------------*/ 

    /*
    * @function curl访问方法API
    * @param string:$key {'student','enterprise'}
    * @param array:$data
    * @param string:$method {'post','get'} 默认post
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public static function curlHttpWeb($key='',$data='',$method="post"){
        self::$url = self::$api_url[$key];  
        self::$query = $data;
        self::startHttp();
        if(strtolower($method) == 'get'){
            self::getHttp();
        }else{
            self::postHttp();
        }
        $ret = self::executeHttp();
        self::closeHttp();
        return $ret;
    }

	/*
    * @function curl post请求核心方法
    * @param string:$url 
    * @param string|array|json:$query 推送数据格式
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
	protected static function postHttp(){
        curl_setopt(self::$_ch, CURLOPT_URL, self::$url);
        curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$_ch, CURLOPT_HEADER, 0);
        curl_setopt(self::$_ch, CURLOPT_POST, TRUE );
        curl_setopt(self::$_ch, CURLOPT_POSTFIELDS,self::$query);
        curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt(self::$_ch, CURLOPT_SSLVERSION, 1);
	}

    /*
    * @function curl get请求核心方法
    * @param string:$url
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected static function getHttp(){
        curl_setopt(self::$_ch, CURLOPT_URL, self::$url);
        curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(self::$_ch, CURLOPT_HEADER, 0);
        curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt(self::$_ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt(self::$_ch, CURLOPT_SSLVERSION, 1);
    }

    /*
    * @function 执行curl
    * @param bool:$type 默认ture,将json数据转化成数组，FALSE返回源数据
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected static function executeHttp($type=TRUE){
        $response = curl_exec(self::$_ch);
        $errno = curl_errno(self::$_ch);
        if($errno > 0) {
            throw new \Exception(curl_error(self::$_ch), $errno);
        }
        if($type){
            return json_decode($response,TRUE);
        }else{
            return $response;
        }  
    }

    /*
    * @function 打开curl资源
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected static function startHttp(){
        self::$_ch = curl_init();
        curl_setopt(self::$_ch, CURLOPT_HEADER, TRUE);
        curl_setopt(self::$_ch, CURLOPT_RETURNTRANSFER, TRUE);
    }

    /*
    * @function 关闭curl资源
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected static function closeHttp(){
        if (is_resource(self::$_ch)) {  
            curl_close(self::$_ch);  
        }        
    }

    // dev版
    public function httpQuery(){
        
        $data = array();
        $query = http_build_query($data);
        // $url = 'http://192.168.21.208:8066/StudentAdd?'.$query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $output = curl_exec($ch);
        curl_close($ch);
        dump($output);
    }
}