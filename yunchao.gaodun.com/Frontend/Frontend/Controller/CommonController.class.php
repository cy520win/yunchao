<?php
// +----------------------------------------------------------------------
// | 前台基类控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-08 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Frontend\Controller;
use Think\Controller;
use Vendor\PHPMailer;
class CommonController extends Controller {

    /**
     * 初始化
     */
    public function _initialize(){}


    /*
    * @function 发送邮件的方法
    * @param string:$email 接收邮箱
    * @param string:$name 收件人名称
    * @param string:$subject 邮件标题
    * @param string:$body 邮件内容
    * @param string:$key ,发送邮件账号 默认gaodun,{'qq','sina','163','gaodun'}
    * @return array
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function sendMail($email='',$name='',$subject,$body,$key='gaodun'){
        if(!$email){
            return FALSE;
        }
        //发送邮件的主要设置
        $mail = new PHPMailer(FALSE);
        $account = $this->setEmailAccount($key);
        try{
                $mail->IsSMTP();                               //启用smtp协议
                $mail->SMTPAuth   = TRUE;               
                $mail->SMTPKeepAlive = TRUE;                  
                $mail->CharSet = "utf-8";  
                $mail->SMTPSecure = $account['md'];                    
                $mail->Port       = $account['port'];
                $mail->Host       = $account['host'];                                 
                $mail->Username   = $account['email'];
                $mail->Password   = $account['pwd'];
                $mail->SetFrom($account['email'],'高顿实习');
                $mail->Subject    = $subject;
                $mail->Body    = $body;
                $mail->WordWrap   = 50;                        // set word wrap
                $mail->MsgHTML($body);
                $mail->AddReplyTo('shixi@gaodun.com','高顿实习');
                $mail->AddAddress($email,$name ? $name :$email);                 //设置收件人
                $mail->IsHTML(TRUE);
                if($mail->Send()){
                    $info = array('status'=>TRUE,'msg'=>C('L_MAIL_SEND_SUCCESS'));  // send as HTML
                }else{
                    $info = array('status'=>FALSE,'msg'=>$mail->ErrorInfo);  // send as HTM
                }
                return $info;
        }catch (phpmailerException $e) {
                $info = array('status'=>FALSE,'msg'=>$e->errorMessage());
                return $info;
        }          
    }

    /*
    * @function jquery字符窜数组转化为php数组
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    protected function arr2ToArr($data=''){
        if($data){
            foreach($data as $k=>$v){
                $arr[trim($v['name'])] = trim($v['value']);
            }
            return $arr;
        }
    }


    /*
     * 返回皆以pkid为key的数组
     */
    //获取热门城市数据
    public function getHotCityList(){
        return M('HotCity')->where(array('is_delete' => 2))->order('order_num desc')->limit(12)->getField('pkid,
        region_id,region_name');
    }

    //获取热门城市数据
    public function getEnHotCityList(){
        $result = M('HotCity')->where(array('hot_city.is_delete' => 2))
            ->join('regions ON regions.region_id = hot_city.region_id')
            ->field('regions.pkid,regions.region_id,regions.region_name_en')->order('hot_city.order_num desc')->limit(12)
            ->select();
        foreach($result as $value){
            $data[$value['pkid']] = array('pkid' => $value['pkid'],'region_id' => $value['region_id'],
                'region_name_en' => $value['region_name_en']);
        }
        return $data;
    }

    //获取行业信息
    public function getIndustryList(){
        return M('Industry')->where(array('is_delete' => 2))->getField('pkid,title');
    }

    //获取企业规模
    public function getScaleList(){
        return M('Scale')->where(array('is_delete' => 2))->getField('pkid,title,description');
    }

    //获取岗位分类列表
    public function getCategoryList(){
        return M('PostCategory')->where(array('is_delete' => 2))->order('order_num desc')->getField('pkid,title');
    }

    //获取福利列表
    public function getWelfareList(){
        return M('Welfare')->where(array('is_delete' => 2))->getField('pkid,title,create_time');
    }

    //获取证书列表
    public function getCertificateList(){
        return M('Certificate')->where(array('is_delete' => 2))->getField('pkid,full_name,full_name_en,certificate_type');
    }

    /*
    * @function 获取以pkid为key，title为value的专业数组array
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function majorTypeArr(){
        $result = M('MajorType')->where(array('is_delete'=>2))->getField('pkid,title');
        return is_array($result) ? $result : FALSE;
    }

    //城市
    public function hotCityArr(){
        foreach($this->getHotCityList() as $k =>$v){
            $result[] = $v['region_name'];
        }
        exit(json_encode($result));
    }


}
