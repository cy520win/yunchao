<?php
// +----------------------------------------------------------------------
// | 简历管理控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class ResumeController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $ResumeModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
        $this->ResumeModel = D('Resume'); // 初始化证书表模型
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
    }

    /**
    * 简历列简历搜索
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        //生成搜索数组条件 
        $search_name = I('post.n') ? I('post.n') :I('get.n');
        $search_school = I('post.s') ? I('post.s') : I('get.s');
        $search_major = I('post.m') ? I('post.m') : I('get.m');
        $search_education = I('post.e') ? I('post.e') : I('get.e');
        if(!empty($search_name)){
            $where['student.name'] = array('like',"%".$search_name."%");
            $map['n'] = $search_name;
            $this->assign('search_n',$search_name);
        }
        if(!empty($search_school)){
            $where['student.graduate_school'] = array('like',"%".$search_school."%");
            $map['s'] = $search_school;
            $this->assign('search_s',$search_school);
        }
        if(!empty($search_major)){
            $where['student.detail_major'] = array('like',"%".$search_major."%");
            $map['m'] = $search_major;
            $this->assign('search_m',$search_major);
        }
        if(!empty($search_education)){
            $where['graduate_year'] = $search_education;
            $map['e'] = $search_education;
            $this->assign('search_e',$search_education);
        }


        $where = !empty($where) ? $where : array();
        //$where['resume.valid_status'] = 1; //证书状态为1
        $Resume_total = $this->ResumeModel->getResumeTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($Resume_total/$this->page_number)); // 获取合法的分页数
        $Resume_data = $this->ResumeModel->getResumeList($where,$page,$this->page_number); // 分页数据  
        $map = !empty($map) ? $map : array();//生成分页连接参数
        $Page       = new PageAdmin($Resume_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $Resume_data = self::eachdata($Resume_data); 
        $this->assign('major',AdminController::getGraduateArr());
        $this->assign('resume',$Resume_data);
    	$this->display();
    }
    
    /**
     * 加载简历页面
     * @author 致远<george.zou@gaodun.cn>
     */
    public function info(){
        $where['resume.pkid'] = I('get.id');
        $info = $this->ResumeModel->getResumeInfo($where);
        $data = self::eachdata($info);
        $education = D('Education')->getStuEdu($info[0]['student_id']);//教育背景
        foreach($education as $k=>$v){
            $education[$k]['degree'] = $v['degree']?AdminController::getMajorArr($v['degree']):'';
        };
        $practice = D('Practice')->getStuPractice($info[0]['student_id']);
        $prize = D('PrizeExperience')->stuPrize($info[0]['student_id']);
        $activity = D('ActivityExperience')->getStuActivity($info[0]['student_id']);
        $card = D('StudentCertificate')->stuCertificate($info[0]['student_id']);
        $cardModel = D('Certificate');
        foreach($card as $k=>$v){
             $card_title = $cardModel->getCertificateinfo($v['certificate_id'],array('full_name'));
             $card[$k]['title'] = $card_title['full_name'];
             if($v['status']==1){
                $card[$k]['status'] = '在读';
             }else{
                $card[$k]['status'] = '通过';
             }
        }
        $schoolModel = D("SchoolJob");
        $school = D("JobExperience")->stuJobExperience($info[0]['student_id']);
        foreach($school as $k=>$v){
            $job = $schoolModel->getSchoolJobinfo($v['job_id']);
            $school[$k]['job'] = $job['title'];
        }
        $subscribe = D('PostSubscribe')->getScribeInfo(array('student_id'=>$info[0]['student_id'],'info_type'=>1));
        if($subscribe){
            $city['parent'] = D('Regions')->getParentRegion($subscribe['expect_city']);
            $city['child'] = D('Regions')->getParentRegion($city['parent']['parent_id']);
            $subscribe['address'] = $subscribe['expect_city']?AdminController::expectCityMatch($subscribe['expect_city']):'';
            // $v['expect_city']?AdminController::expectCityMatch($v['expect_city']):'';
            $subscribe['salary_number'] = AdminController::getSalaryArr($subscribe['salary_range']);
            $subscribe['week_number'] =  AdminController::getWorkDayArr($subscribe['week_available']);
            $subscribe['category'] = D('PostCategory')->getCategoryText($subscribe['expect_category']);
        }
        $this->assign('education',$education);
        $this->assign('practice',$practice);
        $this->assign('prize',$prize);
        $this->assign('activity',$activity);
        $this->assign('card',$card);
        $this->assign('school',$school);
        $this->assign('subscribe',$subscribe);
        $this->assign('resume',$data[0]);
	    $this->display();
    }

    /*
    * 处理其他数据
    */ 
    protected function eachdata($Resume_data){
        foreach($Resume_data as $k=>$v){
            $Resume_data[$k]['education'] = AdminController::getDegreeArr2($Resume_data[$k]['education']);
            $Resume_data[$k]['graduate_year'] = $Resume_data[$k]['graduate_year']?AdminController::getGradeArr($Resume_data[$k]['graduate_year']):'';
            $Resume_data[$k]['major_text'] = AdminController::getCardType($Resume_data[$k]['major_type']);
            $Resume_data[$k]['view_uri'] = C('FRONT_URL').'/Student/resume/role/2/id/'.(int)($v['pkid']);
            if($Resume_data[$k]['politics_status']==1){
                $Resume_data[$k]['politics_status'] = '党员';
            }else{
                $Resume_data[$k]['politics_status'] = '其他';
            }

            if($Resume_data[$k]['gender']==1){
                $Resume_data[$k]['gender'] = '男';
            }else{
                $Resume_data[$k]['gender'] = '女';
            }
        }
        return    $Resume_data;   
    }

}