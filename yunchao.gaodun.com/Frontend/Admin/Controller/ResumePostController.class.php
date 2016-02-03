<?php
// +----------------------------------------------------------------------
// |简历投递控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class ResumePostController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $ResumeModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
        $this->ResumeModel = D('ResumePost');
    }

    /**
    * 简历列简历搜索
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        //生成搜索数组条件 
        $search_name = I('post.w') ? I('post.w') :I('get.w');
        $search_status = I('post.s') ? I('post.s') :I('get.s');
        if(!empty($search_name)){
            $where['student.name|post.title|enterprise.full_name'] = array('like',"%".$search_name."%");
            $map['w'] = $search_name;
            $this->assign('search_n',$search_name);
        }
        if(!empty($search_status)){
            if($this->getResumeStatusArr($search_status)){
                $where['resume_post.status'] = intval($search_status);
                $map['s'] = $search_status;
                $this->assign('search_s',$search_status);
            }            
        }
        if($search_status==3||$search_status==4){
            $order = 'resume_post.deal_time desc';
        }else{
            $order = 'resume_post.pkid desc';
        }
        $where = !empty($where) ? $where : array();
        $Resume_total = $this->ResumeModel->getResumeTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($Resume_total/$this->page_number)); // 获取合法的分页数
        $Resume_data = $this->ResumeModel->getResumeList($where,$page,$this->page_number,$order); // 分页数据 
        $map = !empty($map) ? $map : array();//生成分页连接参数
        $Page       = new PageAdmin($Resume_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('resume',$Resume_data);
        $this->assign('status',AdminController::getResumeStatusArr());
        $this->assign('front_url',C('FRONT_URL'));
    	$this->display();
    }


    /*
     * 投递简历状态改变
     * @author allen
     */
    public function changeStatus(){
        $data = I('post.');
        if(!empty($data['status'])){
            if($data['status'] == 2){
                $data['read_time'] = date('Y-m-d H:i:s',NOW_TIME);
            }else{
                $data['deal_time'] = date('Y-m-d H:i:s',NOW_TIME);
            }
        }else{
            exit(json_encode(array('status' => 'success')));  //确保不会重复更新查看时间
        }

        $studentTrace = new StudentTraceController();
        if($data['status'] == '3') {
            $studentTrace->allowInterview($data['pkid']);
        }elseif($data['status'] == '4'){
            $studentTrace->refuseInterview($data['pkid']);
        }
        $data['last_operator'] = 2;//操作人选择运营后台人员
        $result = $this->ResumeModel->data($data)->save();
        $status_label = resumeStatus($data['status']);
        if($result !== false){
            exit(json_encode(array('status' => 'success','msg' => '操作成功','label' => $status_label)));
        }else{
            exit(json_encode(array('status' => 'fail','msg' => '操作失败，请刷新后重试')));
        }
    }

    /*
    * @function 允许面试的简历总数
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function allowInterviewCount($where){
        return $this->where($where)->count();
    }
}