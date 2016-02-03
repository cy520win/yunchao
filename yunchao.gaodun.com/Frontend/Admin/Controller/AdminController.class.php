<?php
// +----------------------------------------------------------------------
// | 后台基类控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-08 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Controller;
use Vendor\PHPMailer;
header("Content-type: text/html; charset=utf-8");
/**
 * 后台基类控制器
 * @author 致远<george.zou@gaodun.cn>
 */
class AdminController extends Controller {

    /**
    * 邮件发送账号配置
    */ 
    protected $mail_Host = 'smtp.exmail.qq.com';                  //邮件服务器
    protected $mail_Port = 465;                             //端口号
    protected $mail_Secure = 'ssl';                         //ssl加密
    protected $mail_Username = 'shixi@gaodun.com';        //发送账号
    protected $mail_Password = 'abcd1235';                  //登陆密码
    protected $mail_FromName = '高顿实习';                  //发送人
    protected $mail_Replymail = 'shixi@gaodun.com';       //回复地址
    protected $mail_Replyname = '高顿实习';                   //回复人

    //初始化分页列表数量 
    protected $page_number = 10 ; 
    
    /**
     * 初始化
     */
    protected function _initialize(){
        // self::checkSessionLogin();
    }

    /**
     * 验证管理员登陆 SESSION 
     * @author 致远<george.zou@gaodun.cn>
     */
   protected function checkSessionLogin(){
        if(session('hash') && session('login')===true){
          $check = D('AdminUser')->getAdmininfo(session('admin_id'),array('pkid'));
          if(md5(Sha1($check['pkid'],true)) != session('hash')){
                self::logout();
          }else{
                return true;
          }
        }else{
                self::logout();
        }
   }

    /** 
    * 安全退出
    * @author 致远<george.zou@gaodun.cn>
    */
    public function logout(){
        session('[destroy]');
        $this->redirect('Public/login');
    }

    /**
    * 发送邮件
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function sendMail($email,$subject,$body){
        $mail = new PHPMailer(false);
        try{
                $mail->IsSMTP();                               
                $mail->SMTPAuth   = true;                      
                $mail->SMTPKeepAlive = true;                   
                $mail->CharSet = "utf-8";                       
                $mail->SMTPSecure = $this->mail_Secure;        
                $mail->Host       = $this->mail_Host;           
                $mail->Port       = $this->mail_Port;                       
                $mail->Username   = $this->mail_Username;     
                $mail->Password   = $this->mail_Password;                 
                $mail->From       = $this->mail_Username;
                $mail->FromName   = $this->mail_FromName;
                $mail->Subject    = $subject;
                $mail->Body    = $body;
                $mail->WordWrap   = 50;                       
                $mail->MsgHTML($body);
                $mail->AddReplyTo($this->mail_Replymail,$this->mail_Replyname); 
                $mail->AddAddress($email);                 
                $mail->IsHTML(true);
                if($mail->Send()){
                    $info = array('status'=>true,'msg'=>C('L_MAIL_SEND_SUCCESS'));  
                }else{
                    $info = array('status'=>false,'msg'=>$mail->ErrorInfo);
                }
                return $info;
        }catch (phpmailerException $e) {
                $info = array('status'=>false,'msg'=>$e->errorMessage());
                return $info;
        }
    }

    /*
     * 获取企业列表
     * @return array
     * author allen
     */
    public function getEnterpriseList()
    {
        $where = array('email_verify' => 1,'full_name' => array('neq',''));
        return D('Enterprise')->getEnterpriseTitle($where);
    }

    /** 
    * 获取企业规模
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getBusSacle(){
        return D('Scale')->getScaleList();
    }

    /** 
    * 获取热门城市
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getHotCity(){
        return D('HotCity')->getHotcityAll();
    }

    public function getHotCityNew(){
        return D('HotCity')->getHotcityListAll();
    }

    /** 
    * 获取行业类型
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getBusIndustry(){
        return D('Industry')->getIndustryAll();
    }

    public function getIndustField(){
        return D('Industry')->getFields();
    }
    /** 
    * 获取专业类型
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCardType($key){
        $data_new = D('MajorType')->getMajorTypeData();
        foreach($data_new as $k=>$v){
            $data[$v['pkid']] = $v['title'];
        }
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '';
            }
        }else{
            return $data;
        }  
    }

    //英文专业名称
    public function cardTypeEng($key){
        $data_new = D('MajorType')->getMajorTypeData();
        foreach($data_new as $k=>$v){
            $data[$v['pkid']] = $v['title_en'];
        }
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '';
            }
        }else{
            return $data;
        }  
    }

    /** 
    * 获取证书类型
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateType($key=''){
        $data = array(
                1=>'财经',
                2=>'语言',
                5=>'其它'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        }  
    }

    // 兼容 getDegreeArr
    public function getMajorArr($key=0){
        return self::getDegreeArr($key);
    }

    /**
    * 获取学历数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getDegreeArr($key=''){
        $data = array(1 => '专科',2 => '本科',3 => '硕士',4 => '博士',5 => '其他');
        if(is_null($key)){
            return $data;
        }
        if($key){
            $data[$key];
        }
    }

    public function getDegreeArr2($key=''){
        if($key===0){
            return false;
        }
        $data = array(1 => '专科',2 => '本科',3 => '硕士',4 => '博士',5 => '其他');
        if($key){
            return $data[$key];
        }else{
            return $data;
        }
    }

    public function getDegreeArr3($key=''){
        if($key===0){
            return false;
        }
        $data = array(1 => '专科',2 => '本科',3 => '硕士',4 => '博士');
        if($key){
            return $data[$key];
        }else{
            return $data;
        }
    }

    public static function getRange($key=''){
        if($key===0){
            return false;
        }
        $data = array(1 => '职位',2 => '企业',3 => '简历',4 => '学生');
        if($key){
            return $data[$key];
        }else{
            return $data;
        }
    }

    // 获取英文学历数组
    public function degreeArrEng($key=''){
        $data = array(1 => 'zhuanke',2 => 'benke',3 => 'shuoshi',4 => 'boshi',5 => 'qita');
        if(isset($key) && $key>=1 && $key<=5){
            return $data[$key];
        }
        if(!isset($key) || !$key){
            return $data;
        }
    }

    /**
    * 获取年纪数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getGradeArr($key=''){
        $year = date('Y',NOW_TIME);
        $year_start = $year-2;
        $year_end = $year+5;
        for($i=$year_start;$i<$year_end;$i++){
            $data[$i] = $i.'年';
        }
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    /**
     * 获取毕业年份数组
     *@author 致远<george.zou@gaodun.cn>
     */
    public function getGraduateArr($key=''){
        $year = date('Y',NOW_TIME);
        $year_start = $year-2;
        $year_end = $year+5;
        for($i=$year_start;$i<$year_end;$i++){
            $data[$i] = $i.'年';
        }
        // $data = array(
        //     1 => '2018年',
        //     2 => '2017年',
        //     3 => '2016年',
        //     4 => '2015年',
        //     5 => '2014年及以前'
        // );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        }
    }

    //英文毕业年份数组
    public function graduateArrEng($key=''){
        // $data = array(1=>'2018 year',2=>'2017 year',3=>'2016 year',4 =>'2015 year',5 =>'2014 year or ago');
        // if(isset($key) && $key>=1 && $key<=5){
        //     return $data[$key];
        // }
        // if(!isset($key) || !$key){
        //     return $data;
        // }

        $year = date('Y',NOW_TIME);
        $year_start = $year-2;
        $year_end = $year+5;
        for($i=$year_start;$i<$year_end;$i++){
            $data[$i] = $i.'year';
        }
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '';
            }
        }else{
            return $data;
        } 
    }

    /**
    * 获取实习天数数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getWorkDayArr($key=''){
        $data = array(
                1 => '每周一天',
                2 => '每周二天',
                3 => '每周三天',
                4 => '每周四天',
                5 => '每周五天'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    public function getWorkDayArr2($key=''){
        $data = array(
                1 => '1天',
                2 => '2天',
                3 => '3天',
                4 => '4天',
                5 => '5天'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    public function getTypeList(){
        $data = array(
                1 => '企业',
                2 => '职位',
                3 => '区域城市',
                // 4 => '每周实习天数',
                5 => '岗位分类',
                // 6 => '企业行业分类',
                7 => '学历',
            );        
       return $data;      
    }

    public function workDayArrEng($key=''){
        $data = array(1=>'1 day/week',2=>'2 day/week',3=>'3 day/week',4=>'4 day/week',5=>'5 day/week'
            );
        if(isset($key) && $key>=1 && $key<=5){
            return $data[$key];
        }
        if(!isset($key) || !$key){
            return $data;
        }
    }

    /**
    * 获取实习日薪数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getSalaryArr($key=''){
        $data = array(
                1 => '50以下',
                2 => '50~99元/天',
                3 => '100~200元/天',
                4 => '200以上'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    /**
    * 获取实习日薪数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function salaryArrEng($key=''){
        $data = array(1 =>'50 down',2=> '50~99 Yuan/day',3=>'100~200 Yuan/day',4=>'200 up');
        if(isset($key) && $key>=1 && $key<=5){
            return $data[$key];
        }
        if(!isset($key) || !$key){
            return $data;
        }
    }

    /**
    * 获取岗位订阅周期数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getCycleDayArr($key=''){
        $data = array(
                3 => '3天',
                7 => '7天'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    /**
    * 获取简历状态数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getResumeStatusArr($key=''){
        $data = array(
                1 => '未查看',
                2 => '未处理',
                3 => '允许面试',
                4 => '拒绝面试',
                5 => '待定'
            );
        if($key){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return false;
            }
        }else{
            return $data;
        } 
    }

    /**
    * 获取性别数组
    *@author 致远<george.zou@gaodun.cn>
    */ 
    public function getGenderArr($key=''){
        $data = array(
                1 => '男',
                2 => '女'
            );
        if($key||$key===0){
            if(array_key_exists($key,$data)){
                return $data[$key];
            }else{
                return '未知';
            }
        }else{
            return $data;
        } 
    }

    //将城市region_id转化为文本
    public function regionIdToname($region_id,$Regions){
        if(empty($Regions)){
            $Regions = D('Regions');
        }
        $city = $Regions->regionIdToname($region_id);
        return $city['region_name'];
    }

    //处理地址文本
    public function expectCityMatch($string){
        $arr = explode(',',$string);
        $result = D('Regions')->getCityName($arr);
        foreach($result as $v){
            if($v['region_name']){
               $city .= $v['region_name'].'，'; 
            } 
        }
        return $city ? trim($city,'，'):'';
    }

    /** 
    * 常规邮箱判断
    * @author 致远<george.zou@gaodun.cn>
    */
    public function checkMailType($email=''){
        if($email){
            $email = strtolower($email);
            $reg = "/^\w+([-+.]\w+)*@(sina|qq|163|126|sohu|hotmail|gmail|139)+\.(com|cn|net)$/i";
            return preg_match($reg,$email);
        }
    }

    //邮箱格式
    public function checkMail2($email){
        $email = strtolower($email);
        $reg = '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/';
        return preg_match($reg,$email);
    }
}
