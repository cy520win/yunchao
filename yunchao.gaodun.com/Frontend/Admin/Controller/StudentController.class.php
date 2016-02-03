<?php
// +----------------------------------------------------------------------
// | 学员账户控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Upload as Upload;
use Vendor\PageAdmin as PageAdmin;
use Think\Crypt as Crypt;
class StudentController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $StudentModel ;
    protected $nowtime;
    protected $industry_list;

    public function _initialize(){
	    parent::_initialize();
        $this->StudentModel = D('Student'); // 初始化学生表模型
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
        $this->industry_list = $this->getBusIndustry(); //行业类型
    }

    /**
     * 学员展示列表、搜索列表
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        $field = '';
        $order = 'student.create_time desc';
        $word_form = I('post.w') ? I('post.w') : I('get.w');//获取查询字
        //生成数据库查询条件
        if($word_form){
            $condition['student.name'] = array('like',"%".$word_form."%");
            $condition['student.login_email']  = array('like',"%".$word_form."%");
            $condition['_logic'] = 'or';
            $where['_complex'] = $condition;
        }

        $where = !empty($where) ? $where : true;
        $student_total = $this->StudentModel->getStudentTotalOutDelete($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($student_total/$this->page_number)); // 获取合法的分页数
        $student_data = $this->StudentModel->getStudentListOutDelete($where,$field,$order,$page,$this->page_number); // 分页数据
        $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
        $Page       = new PageAdmin($student_total,$this->page_number,$map); //分页类实例化

        foreach($student_data as $k=>$v){
            $student_data[$k]['education'] = ($v['education']>0)?AdminController::getDegreeArr2($v['education']):'';
            if($v['email_verify']==1){
                $student_data[$k]['email_verify'] = '已认证';
            }elseif($v['email_verify']==2){
                $student_data[$k]['email_verify'] = '未认证';
            }else{
                $student_data[$k]['email_verify'] = '未知';
            }
        }


        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('word',$word_form);// 查询关键字
        $this->assign('studentlist',$student_data);
    	$this->display();
    }
    
    /**
     * 加载新增学员页面
     * @author 致远<george.zou@gaodun.cn>
     */
    public function add(){
        $degree = AdminController::getDegreeArr2();
        $grade_data = AdminController::getGradeArr();
        $workday_data = AdminController::getWorkDayArr();
        $salary_data = AdminController::getSalaryArr();
        $graduate_data = AdminController::getGraduateArr();
        $schooljob = D('SchoolJob')->getSchoolJobAll();
        $major_type = D("MajorType")->getMajorTypeSelect();
        $postcategory = D('PostCategory')->getPostTypeAll();
        $parentcity = D('Regions')->parentCity();
        $scale = D("Scale")->getScale();
        $industry = D("Industry")->getIndustrySelect();
        $postcate = D("PostCategory")->getParentCate();
        $schooljob = D("SchoolJob")->schoolJobOpt();
        $this->assign('degree',$degree);
        $this->assign('majortype',$major_type);
        $this->assign('grade',$grade_data);
        $this->assign('graduate',$graduate_data);
	    $this->display();
    }

    /**
     * 保存新增学员数据
     * @author 致远<george.zou@gaodun.cn>
     */
    public function save(){
        if(IS_POST){
            $data = I('post.student');            
            if(empty($data['name']) || empty($data['login_email'])){
               $this->redirect("Student/add",'',1,'姓名或邮箱为空'); 
            }
            $id = $this->StudentModel->getPkid($data['login_email']);           
            if($id){
                $this->redirect("Student/index",'',1,'登录邮箱不能重复');
            }

            $data['create_time'] = $this->nowtime;
            $data['modify_time'] = $this->nowtime;
            $bool = $this->StudentModel->studentAdd($data);
            if($bool['status']=='true'){
                $this->redirect("Student/index",'',1,$bool['msg']);
            }else{
                $this->redirect("Student/add",'',1,$bool['msg']);
            }
        }
    }
    
    /**
     * 加载学员信息编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
        $field = array();
        $degree = AdminController::getDegreeArr2();
        $major_type = D('MajorType')->getMajorTypeSelect();
        $grade_data = AdminController::getGradeArr();
        $workday_data = AdminController::getWorkDayArr();
        $salary_data = AdminController::getSalaryArr();
        $schooljob = D('SchoolJob')->getSchoolJobAll();
        $graduate_data = AdminController::getGraduateArr();
        $postcategory = D('PostCategory')->getPostTypeAll();
        $parentcity = D('Regions')->parentCity();
        $scale = D("Scale")->getScale();
        $industry = D("Industry")->getIndustrySelect();
        $postcate = D("PostCategory")->getParentCate();
        $schooljob = D("SchoolJob")->schoolJobOpt();
        $subscribe = D('PostSubscribe')->getScribeInfo(array('student_id'=>I('get.id'),'info_type'=>1));
        if($subscribe){
            $cur_city = D('Regions')->getParentRegion($subscribe['expect_city']);
            $child_city = D('Regions')->childCity($cur_city['parent_id']);
            $adddress = $subscribe['expect_city']?AdminController::expectCityMatch($subscribe['expect_city']):'';
            $this->assign('curcity',$child_city);
            $this->assign('parentcity',$cur_city['parent_id']);
            $this->assign('address',$adddress);
        }  

        $info = $this->StudentModel->getStudentinfo(I('get.id'),$field);
        $this->assign('degree',$degree);
        $this->assign('majortype',$major_type);
        $this->assign('schooljob',$schooljob);
        $this->assign('grade',$grade_data);
        $this->assign('graduate',$graduate_data);
        $this->assign('city',$parentcity);
        $this->assign('workday',$workday_data);
        $this->assign('postcategory',$postcategory);
        $this->assign('salary',$salary_data);
        $this->assign('scale',$scale);
        $this->assign('industry',$industry);
        $this->assign('postcate',$postcate);
        $this->assign('schooljob',$schooljob);
        $this->assign('user',$info);
        $this->assign('scribe',$subscribe);
        $this->assign('eduhtml',$this->stuEduList(I('get.id')));
        $this->assign('prihtml',$this->stuPriList(I('get.id')));
        $this->assign('ticehtml',$this->stuTiceList(I('get.id')));
        //$this->assign('acthtml',$this->stuActList(I('get.id')));
        $this->assign('jobhtml',$this->stuJobList(I('get.id')));
        $this->assign('cardhtml',$this->stuCardList(I('get.id')));
        $this->display();
    }

    /**
     * 学员信息更新
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){
	   if(IS_POST){
            $post = I('post.student');
            $student_id = I('post.student_id');//接受修改学员ID
            if(empty($post['name'])){
               $this->redirect("Student/edit",array('id'=>$student_id),1,'姓名为空'); 
            }
            $post['modify_time'] = $this->nowtime;
            $bool = $this->StudentModel->studentUpdata($post,$student_id);
            if($bool){

                $this->redirect("Student/edit",array('id'=>$student_id),1,'修改成功');
            }else{
                $this->redirect("Student/edit",array('id'=>$student_id),1,'修改失败');
            }
        }  
    }

    /*
     * 学生帐号密码重设
     */
    public function reset(){
        $enId = I('get.id');
        $field = 'account.login_pass,student.login_email,student.pkid as student_id,student.account_id';
       // $data = $this->StudentModel->field($field)->join('account ON account.pkid = student.account_id')->where
        //(array('student.pkid' => $enId))->find();
        //$this->assign('accountInfo',$data);
        $this->display();
    }

    /*
     * 修改学生帐号信息
     * @author allen
     */
    public function resetAccount(){
        $data = I('post.');
        $acParam = array(
            'pkid' => $data['acId'],
            'login_email' => $data['email'],
            'login_pass' => MD5(C('DATA_AUTH_KEY').Crypt::encrypt(strtolower($data['password'])))
        );
        /* $bool = D('Account')->checkMailUnique($acParam['login_email'],$acParam['pkid']);
        if(!$bool){
            exit(json_encode(array('status' => 'fail','msg' => '修改失败','email' => 'notunique')));
        } */

        $pwdRule = checkpass($data['password']);
        if(!$pwdRule){
            exit(json_encode(array('status' => 'fail','msg' => '修改失败','pass_word' => 'notunique')));
        }
        $stParam = array(
            'pkid' => $data['enId'],
            'login_email' => $data['email']
        );
        //$result = D('Account')->data($acParam)->save();
        $this->StudentModel->data($stParam)->save();
       /*  if($result !== false){
            exit(json_encode(array('status' => 'success','msg' => '修改成功')));
        }else{
            exit(json_encode(array('status' => 'fail','msg' => '修改失败')));
        } */
    }

    /**
     * 上传头像、图片
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function uphead(){
        $upload = new Upload();// 实例化上传类
        $upload->rootPath  =  'Public/Upload/headpic/student/'; // 设置附件上传根目录
        dir_create($upload->rootPath);//创建文件夹
        $head = $upload ->uploadOne($_FILES['headpic']);
        $path = C('APP_URL').'/'.$upload->rootPath.$head['savepath'].$head['savename'];
        $data = array('msg'=>$path,'error'=>'');
        exit(json_encode($data));
    }

    /**
     * 检查学生邮箱
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function checkMail(){
        if(IS_POST){
            $email = isset($_POST['mail']) ? I('post.mail') : '';
            $uid = isset($_POST['uid']) ? I('post.uid') : '';
            /* $bool = D('Account')->checkMailUnique($email,$uid);
            exit(json_encode($bool)); */
        }
    }

    /**
     * 获取分类的所有专业
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function getMajor(){
        if(IS_POST){
            $major_data = D('Major')->getMajorType(array('valid_status'=>1,'type_id'=>I('post.id')),array('pkid','title'));
            if($major_data){
                $option = '';
                foreach($major_data as $k=>$v){
                    $option .= '<option value='.$v['pkid'].'>'.$v['title'].'</option>';
                }
                $data = array('status'=>true,'data'=>$option,'msg'=>'获取成功');
            }else{
                $data = array('status'=>false,'data'=>$major_data,'msg'=>'获取失败');
            }
            exit(json_encode($data));
        }
    }

    /**
     * 获取联动城市
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function getCity(){
        if(IS_POST){
            $city = D('Regions')->childCity(I('post.id'));
            if($city){
                $option = '';
                foreach($city as $k=>$v){
                    $option .= '<option value='.$v['region_id'].'>'.$v['region_name'].'</option>';
                }
                $data = array('status'=>true,'data'=>$option,'msg'=>'获取成功');
            }else{
                $option = '<option >请选择</option>';
                $data = array('status'=>false,'data'=>$option,'msg'=>'获取失败');
            }
            exit(json_encode($data));
        }
    }

    /**
     * 保存用户期望
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function subscribe(){
        if(IS_POST){
            $data = I('post.post_subscribe');
            $scribe_id = I('post.scribe_id');
            $student_id = $data['student_id'];
            if(empty($scribe_id)){ //新增
                $last_id = D('PostSubscribe')->addScribe($data);
                if($last_id){
                    $this->redirect("Student/edit",array('id'=>$student_id),1,'修改成功-add');
                }else{
                    $this->redirect("Student/edit",array('id'=>$student_id),1,'修改失败-add');
                }                
            }
            if($scribe_id){//更新
                $where['pkid'] = $scribe_id;
                $bool = D('PostSubscribe')->updateScribe($data,$where);
                if($bool){
                    $this->redirect("Student/edit",array('id'=>$student_id),1,'修改成功-up');
                }else{
                     $this->redirect("Student/edit",array('id'=>$student_id),1,'修改失败-up');
                }
            }
        }
    }

    /**
     * 保存学历教育
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function education(){
        if(IS_POST){
            $last_id = D('Education')->addEducation(I());
            if($last_id){
                $html =  $this->stuEduList(I('post.student_id'));
                $result = array('data'=>$html,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));
            }
        }
    }

    /*
    * 获取渲染学历数据后的模板
    */ 
    public function stuEduList($student_id=17){
            $edu = D('Education')->getStuEdu($student_id);
            $major = AdminController::getDegreeArr();
            $this->assign('edu',$edu);
            $this->assign('tpl',false);
            $this->assign('major',$major);
            $html = $this->fetch('student_education');
            return $html;
    }

    /*
    * 获取添加学历模板
    */ 
    public function stuEduTpl(){
        $this->assign('tpl',true);
        $this->assign('major',AdminController::getDegreeArr());
        $html = $this->fetch('student_education');
        exit(json_encode(array('data'=>$html)));
    }

    /*
    * 获取添加奖励模板
    */ 
    public function stuPriTpl(){
        $this->assign('tpl',true);
        $html = $this->fetch('student_prize');
        exit(json_encode(array('data'=>$html)));     
    }

    /*
    * 获取奖励渲染模板
    */ 
    public function stuPriList($sid=17){
        $data = D('PrizeExperience')->stuPrize($sid);
        $this->assign('tpl',false);
        $this->assign('prize',$data);
        $html = $this->fetch('student_prize');
        return $html;
    }

    /*
    * 逻辑删除学员学历
    */ 
    public function stuDelEdu(){
        if(IS_POST){
            $bool = D('Education')->deleteStuEdu(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        }
    }

    /*
    * 学员更新学历
    */ 
    public function updateEdu(){
        if(IS_POST){
            $where['pkid'] = I('post.pid');
            $bool = D('Education')->updateStuEdu(I(),$where);
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
            }
            exit(json_encode($result));
        }
    }

    /*
    * 新增学员获奖经历
    */ 
    public function prize(){
        if(IS_POST){
            $last_id = D('PrizeExperience')->addPrize(I());
            if($last_id){
                $data = $this->stuPriList(I('post.student_id'));
                $result = array('data'=>$data,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));  
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));       
            }
        }
    }

    /*
    * 逻辑删除学员获奖
    */ 
    public function stuDelPri(){
        if(IS_POST){
            $bool = D('PrizeExperience')->deleteStuPri(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        }
    }

    /**
    * 更新学员奖励
    */ 
    public function updatePri(){
        $where['pkid'] = I('post.pid');
        $bool = D('PrizeExperience')->updateStuPri(I(),$where);
        if($bool){
            $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
        }else{
            $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
        }
        exit(json_encode($result));
    }

    //加载实习经历模板
    public function stuTiceTpl(){
        $this->assign('tpl',true);
        $html = $this->fetch('student_practice');
        exit(json_encode(array('data'=>$html)));         
    }

    //加载渲染的实践经历模板
    public function stuTiceList($sid=17){
        $data = D('Practice')->getStuPractice($sid);
        $this->assign('tpl',false);
        $this->assign('practice',$data);
        $html = $this->fetch('student_practice');
        return $html;
    }

    // 新增学员实践经历
    public function practice(){
        if(IS_POST){
            $last_id = D('Practice')->addPractice(I());
            if($last_id){
                $data = $this->stuTiceList(I('post.student_id')); //to do ...
                $result = array('data'=>$data,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));  
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));       
            }
        }
    } 

    //逻辑删除学员实践经历
    public function stuDelTice(){
        if(IS_POST){
            $bool = D('Practice')->deleteStuTice(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        }      
    }

    //更新保存学员实践经历
    public function updateTice(){
        $where['pkid'] = I('post.pid');
        $bool = D('Practice')->updateStuPractice(I(),$where);
        if($bool){
            $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
        }else{
            $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
        }
        exit(json_encode($result));
    }

    //加载课外活动
    public function stuActTpl(){
        $this->assign('tpl',true);
        $html = $this->fetch('student_activity');
        exit(json_encode(array('data'=>$html)));  
    }

    //新增学员活动经历
    public function activity(){
        if(IS_POST){
            $last_id = D('ActivityExperience')->addActivity(I());
            if($last_id){
                $data = $this->stuActList(I('post.student_id')); //to do ...
                $result = array('data'=>$data,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));  
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));       
            }
        }
    }

    //加载渲染的课外活动模板
    public function stuActList($sid=17){
        $data = D('ActivityExperience')->getStuActivity($sid);
        $this->assign('tpl',false);
        $this->assign('active',$data);
        $html = $this->fetch('student_activity');
        return $html;
    }

    //逻辑删除学员课外活动
    public function stuDelAct(){
        if(IS_POST){
            $bool = D('ActivityExperience')->deleteStuActivity(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        }  
    }

    //更新单个学员课外活动
    public function updateAct(){
        $where['pkid'] = I('post.pid');
        $bool = D('ActivityExperience')->updateStuActivity(I(),$where);
        if($bool){
            $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
        }else{
            $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
        }
        exit(json_encode($result));       
    }

    //获取学生校内职务模板
    public function stuJobTpl(){
        $this->assign('schooljob',D("SchoolJob")->schoolJobOpt());
        $this->assign('tpl',true);
        $html = $this->fetch('student_schooljob');
        exit(json_encode(array('data'=>$html)));          
    }

    //获取学生校内职务渲染模板
    public function stuJobList($sid=17){
        $this->assign('schooljob',D("SchoolJob")->schoolJobOpt());
        $this->assign('experience',D("JobExperience")->stuJobExperience($sid));
        $this->assign('tpl',false);
        $html = $this->fetch('student_schooljob');
        return $html;
    }

    //新增保存学员校内职务
    public function schooljob(){
        if(IS_POST){
            $last_id = D('JobExperience')->addJobExperience(I());
            if($last_id){
                $data = $this->stuJobList(I('post.student_id')); //to do ...
                $result = array('data'=>$data,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));  
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));       
            }
        }      
    }

    //逻辑删除用户校内职务
    public function stuDeljob(){
        if(IS_POST){
            $bool = D('JobExperience')->deleteStuJobExperience(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        }         
    }

    //更新学员校内职务
    public function updateJob(){
        $where['pkid'] = I('post.pid');
        $bool = D('JobExperience')->updateStuJobExperience(I(),$where);
        if($bool){
            $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
        }else{
            $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
        }
        exit(json_encode($result));         
    }

    //学员证书模板
    public function stuCardTpl(){
        $this->assign('CertificateType',AdminController::getCertificateType());
        $this->assign('tpl',true);
        $html = $this->fetch('student_certificate');
        exit(json_encode(array('data'=>$html)));   
    }

    //加载学员渲染模板
    public function stuCardList($sid=17){
        $data = D('StudentCertificate')->stuCertificate($sid);
        $cardModel = D('Certificate');
        foreach($data as $k=>$v){
             $card = $cardModel->getCartType($v['certificate_id']);
             $data[$k]['card_type'] = $card['certificate_type'];
             $data[$k]['all_type'] = $cardModel->getCartTypeList($card['certificate_type']);
        }
        $this->assign('card',$data);
        $this->assign('tpl',false);
        $this->assign('CertificateType',AdminController::getCertificateType());
        $html = $this->fetch('student_certificate');
        return $html;
    }

    //新增保存学员证书
    public function certificate(){
         if(IS_POST){
            $last_id = D('StudentCertificate')->addCertificate(I());
            if($last_id){
                $data = $this->stuCardList(I('post.student_id')); //to do ...
                $result = array('data'=>$data,'status'=>true,'msg'=>'保存成功');
                exit(json_encode($result));  
            }else{
                $result = array('data'=>'暂无数据','status'=>false,'msg'=>'保存失败');
                exit(json_encode($result));       
            }
        }         
    }

    //逻辑删除学员证书
    public function stuDelCard(){
        if(IS_POST){
            $bool = D('StudentCertificate')->deleteStuCertificate(I('post.eid'));
            if($bool){
                $result = array('data'=>'success','status'=>true,'msg'=>'删除成功');
                exit(json_encode($result));
            }else{
                $result = array('data'=>'error','status'=>false,'msg'=>'删除失败');
                exit(json_encode($result));               
            }
        } 
    }

    //更新学员证书
    public function updateCard(){
        $where['pkid'] = I('post.pid');
        $bool = D('StudentCertificate')->updateStuCertificate(I(),$where);
        if($bool){
            $result = array('data'=>'success','status'=>true,'msg'=>'保存成功');
        }else{
            $result = array('data'=>'error','status'=>false,'msg'=>'保存失败');
        }
        exit(json_encode($result));        
    }

}