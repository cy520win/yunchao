<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学生投递箱、学生手机验证
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-26 15:46:21  星期二
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Controller;
use Vendor\PageAdmin as PageAdmin;
class DeliveryController extends CommonController{

    protected $ResumePostModel;     //投递模型
    protected $RegionsModel;
    protected $ResumeViewModel;
    protected $RegionViewModel;
    protected $IndustryModel;
    protected $page_number = 20;
    protected $default_text = array(3=>'你好，同学！你的简历已通过我们的审核，近期我们会电话联系你，请保持电话畅通。',
        4=>'非常荣幸收到你的简历，但你的简历与该职位条件不太匹配，祝你找到满意的工作。');
    protected $ScaleModel;

    public function _initialize(){
        parent::_initialize();
        $this->verifyRightLogin();
        $this->chkStudentCenter();
        $this->ResumePostModel = D('ResumePost');
        $this->RegionsModel = D('Regions');
        $this->RegionViewModel = D('ResumeView');
        $this->ScaleModel = D('Scale');
        $this->IndustryModel = D('Industry');
    }

    /*
    * @function 投递箱首页，如果是ajax访问，则返回json
    * @param int:$id，int:$page，string:$ajax
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    */
    public function student(){
        $id = I('get.id');
        $page  = I('get.page');
        $ajax = I('get.ajax')=='true'?true:false;
        $list = self::deliveryList($id,$page);
        $this->assign('delivery',$list['data']);
        $this->assign('page',$list['page']);
        $this->assign('text',$list['text']);
        $html = $this->fetch('Student:student_delivery_list');
        if(isMobile()){
            redirect('/MobileStudent/delivery');
            exit;
        }
        if(!$ajax){
            $this->assign('deliveryHtml',$html);
            $this->display('Student:student_delivery');exit;
        }else{
            returnjson(array('data'=>$html));
        } 
    }

    /*
    * @function 投递列表核心方法
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    */
    protected function deliveryList($id,$page){
        if(!isset($_GET['id']) || !$id || $id==1){
            $where = array();
            $id=1;
            $timeText = timeText(1);
            $order = 'resume_post.create_time desc';
        }
        if(isset($_GET['id']) && $id==2){
            $where['resume_post.status']=1;//未查看
            $timeText = timeText(1);
            $order = 'resume_post.create_time desc';
        }
        if(isset($_GET['id']) && $id==3){
            $where['resume_post.status']=2; //已查看
            $timeText = timeText(2);
            $order = 'resume_post.read_time desc';
        }
        if(isset($_GET['id']) && $id==4){
            $where['resume_post.status']=3; //通知面试
            $timeText = timeText(3);
            $order = 'resume_post.deal_time desc';
        }
        if(isset($_GET['id']) && $id==5){
            $where['resume_post.status']=4;//拒绝面试
            $timeText = timeText(3);
            $order = 'resume_post.deal_time desc';
        }
        if(isset($_GET['id']) && $id==6){
            $where['resume_post.status']=5;//待定中
            $timeText = timeText(3);
            $order = 'resume_post.deal_time desc';
        }
        actionLogAdd(29,array('account_type' => 2,'student_id' =>session('account.student_id')));
        $this->checkRedNote();
        $pagebtn['type']= $id;
        $count  = $this->ResumePostModel->getPostDeliveryTotal($where);
        $page_sum = ceil($count/$this->page_number);
        $page = isset($_GET['page'])?intval($page):1;
        $page = min($page,$page_sum);
        $page = max(0,$page);
        $delivery = $this->ResumePostModel->getPostDelivery($where,$page,$this->page_number,$order);
        if(!empty($delivery) && is_array($delivery)){
            foreach($delivery as $k=>$v){
                $delivery[$k]['send_type'] = getPostTypeText($v['send_type']);
                $delivery[$k]['status'] = getResumeStatusText($v['status']);
                if($id==2 || $id==1 || !$id){
                    $time = $v['create_time'];
                }
                if($id==3){
                    $time = $v['read_time'];
                }
                if($id==4||$id==5||$id==6){
                    $time = $v['deal_time'];
                }
                if($v['status']==3||$v['status']==4){
                    $delivery[$k]['hr_remark'] = !empty($v['hr_remark']) ? $v['hr_remark'] : $this->default_text[$v['status']];
                }
                $delivery[$k]['create_time'] = time2Unit($time);
                $delivery[$k]['city'] = regionIdToname($v['city_id'],$this->RegionsModel);
                $delivery[$k]['id'] = enInt($v['post_id']);
                $delivery[$k]['enterprise_id'] = enInt($v['enterprise_id']);
            }
//            if($id==3){
//                $time = $v['read_time'];
//            }
//            if($id==4||$id==5 || $id==6){
//                $time = $v['deal_time'];
//            }
//            $delivery[$k]['create_time'] = time2Unit($time);
//            $delivery[$k]['city'] = regionIdToname($v['city_id'],$this->RegionsModel);
//            $delivery[$k]['id'] = enInt($v['post_id']);
//            $delivery[$k]['enterprise_id'] = enInt($v['enterprise_id']);

        }
        $pagebtn['btn'] = self::page($id,$page,$page_sum);
        return array('data'=>$delivery,'page'=>$pagebtn,'text'=>$timeText);
    }

    /*
    * @function 简历关注度
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    */
    public function read(){
        $page  = I('get.page');
        $ajax = I('get.ajax')=='true'?true:false;
        $count_arr = $this->RegionViewModel->resumeViewTotal();
        $count = $count_arr['sum'];
        $pagebtn['view'] = $count_arr['view'];
        $page_sum = ceil($count/$this->page_number);
        $page = isset($_GET['page'])?intval($page):1;
        $page = min($page,$page_sum);
        $page = max(0,$page);
        $read = $this->RegionViewModel->resumeView($page,$this->page_number);
        foreach($read as $k=>$v){
            $read[$k]['city'] = regionIdToname($v['city_id'],$this->RegionsModel);
            $read[$k]['scale'] = getScaleText($v['scale_id'],$this->ScaleModel);
            $read[$k]['industry'] = getIndustryText($v['industry_id'],$this->IndustryModel);
            if($v['view']>1){
                $read[$k]['create_time'] = time2Unit($this->RegionViewModel->lastViewTime($v['enterprise_id']));
            }
            $read[$k]['create_time'] = time2Unit($v['create_time']);
        }
        $pagebtn['btn'] = self::page(1,$page,$page_sum);
        $this->assign('read',$read);
        $this->assign('page',$pagebtn);
        $html = $this->fetch('Student:student_read_list');
        if(!$ajax){
            $this->assign('readHtml',$html);
            $this->display('Student:student_read');exit;
        }else{
            returnjson(array('data'=>$html));
        }
    }

    /*
    * @function 生成分页按钮
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    */
    protected function page($id,$page,$page_sum){
        $pagebtn = array();
        if($page_sum>1){ //大于1，生成上下页分页按钮
            $page_prev = $page-1;
            $page_next = $page+1;
            if($page_prev==0){
                $pagebtn['prev'] = "<a href=\"javascript:;\" id=\"page-none-btn\">上一页</a>";
            }else{
                $pagebtn['prev'] = "<a href=\"javascript:;\" onclick=\"return page(".$id.",".$page_prev.")\">上一页</a>";
            }
            if($page_next>$page_sum){
                $pagebtn['next'] = "<a href=\"javascript:;\" id=\"page-none-btn\">下一页</a>";
            }else{
                $pagebtn['next'] = "<a href=\"javascript:;\" onclick=\"return page(".$id.",".$page_next.")\">下一页</a>";
            }
        }
        return $pagebtn;
    }

    /*
    * @function 发送手机验证码
    * @param string:$phone 手机号
    * @param int:$type 验证码类型
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function checkPhone(){
        $phone  = isset($_POST['phone']) ? I('post.phone') :'';
        $rst  = $this->chkphone($phone);
        if(!$rst['status']){
           returnjson($rst); 
        }
        $type = 3;//验证手机类型
        $result = A('CurlService')->sendSms($phone,$type);
        if($result['status']){
            returnjson(array('msg'=>'短信验证码发送成功','status'=>TRUE));
        }else{
            returnjson(array('msg'=>$result['msg'],'status'=>FALSE));
        }
    }

    /*
    * @function 检查手机验证码
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function verifyPhone(){
        $phone = isset($_POST['mobile']) ? I('post.mobile') : '';
        $rst  = $this->chkphone($phone);
        if(!$rst['status']){
           returnjson($rst); 
        }
        $mobile = isset($_SESSION['verify_phone']['mobile']) ? session('verify_phone.mobile') : '';
        if($mobile != $phone){
            returnjson(array('msg'=>'请填写正确的手机号','status'=>FALSE,'code'=>102));
        }
        $phonecode = isset($_SESSION['verify_phone']['code']) ? session('verify_phone.code') : '';
        $expiretime = isset($_SESSION['verify_phone']['expiretime']) ? session('verify_phone.expiretime') : '';
        $postcode = isset($_POST['code']) ? I('post.code') : '';
        if(!$phonecode || !$expiretime){
            returnjson(array('msg'=>'请重新获取验证码','status'=>FALSE,'code'=>103));
        }
        if(!$postcode){
            returnjson(array('msg'=>'验证码为空','status'=>FALSE,'code'=>104));
        }
        if($phonecode != $postcode){
             returnjson(array('msg'=>'请输入正确的验证码','status'=>FALSE,'code'=>105));
        }
        if($phonecode == $postcode){
            if(NOW_TIME>$expiretime){
                returnjson(array('msg'=>'验证码已过期','status'=>FALSE,'code'=>106));
            }else{
                $StudentModel = D('Student');
                $StudentModel->upMobileStatus($phone);//更新手机验证状态
                $StudentModel->updateContactMobile();//更新
                session('verify_phone',NULL);
                returnjson(array('msg'=>'','status'=>TRUE));
            }
        }
    }

    /*
    * @function 验证手机格式、以及唯一性
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function chkphone($phone){
        $bool  = checkPhoneRule($phone);
        $rst = array('msg'=>"",'status'=>TRUE);
        if(!$bool){
            $rst = array('msg'=>"请输入正确的大陆手机号码",'status'=>FALSE,'code'=>101);
        }
        $bool = D('Student')->checkMobileOnly($phone);
        if($bool){
            $rst = array('msg'=>"手机号已使用",'status'=>FALSE,'code'=>102);
        } 
        return $rst;      
    }
}