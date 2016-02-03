<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学生简历控制器
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-05 16:49:05  星期二
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Frontend\Controller;
use Think\Upload as Upload;
class StudentController extends CommonController {

    protected $PostSubscribeModel;
    protected $StudentModel;
    protected $EducationModel;
    protected $PracticeModel;
    protected $PrizeExperienceModel;
    protected $ActivityExperienceModel;
    protected $StudentCertificateModel;
    protected $JobExperienceModel;
    protected $CommonController;
    protected $EnStudentController;
    protected $TraceSetupController;
    protected $ResumeModel;
    protected $error_border='1px solid #ff4155';
    protected $success_border='1px solid rgb(228, 228, 228)';
    protected $date_type = '0000-00-00';
    protected $controller_code = 400; //控制器编码
    protected $rate_base = 11;//简历基数 11%
    protected $trace_id = array('invitation_wait' => 3);
    protected $resume_type = array('introduce_bool' => 'Dear HR','education_bool' => '教育背景',
        'practice_bool' => '实习经历', 'school_acticity_bool' => '校园经历', 'cert_prize_bool' => '证书和荣誉');


    public function _initialize(){
		parent::_initialize();
        $this->CommonController = A('Common');
        $this->StudentModel = D('Student');
        $this->PostSubscribeModel = D('PostSubscribe');
        $this->EducationModel = D('Education');
        $this->PracticeModel = D('Practice');
        $this->ResumeModel = D('Resume');
        $this->PrizeExperienceModel = D('PrizeExperience');
        $this->ActivityExperienceModel = D('ActivityExperience');
        $this->StudentCertificateModel = D('StudentCertificate');
        $this->JobExperienceModel = D('JobExperience');
        $this->EnStudentController = A('EnStudent');
        $this->TraceSetupController = A('TraceSetup');
	}

    /*
    * @function 学生个人简历
    * @author 致远<george.zou@gaodun.cn> 2015-05-08
    */
    public function index(){
        $this->CommonController->chkStudentCenter();
        $major_type = D('MajorType')->getMajorTypeSelect(); //专业分类
        $certificate_option = arrTokey(D('Certificate')->getCartTypeList(1));//初始化财经
        $this->assign('titleHtml',$this->EnStudentController->indexTitle());
        $this->assign('introduceHtml',textareaFormat($this->introduceTpl()));
        $this->assign('exist',self::resumeModule());
        $this->assign('baseInfoHtml',self::baseInfoTpl('',TRUE));//简历基本信息
        $this->assign('educationHtml',self::educationListTpl());//教育背景列表
        $this->assign('practiceHtml',self::priacticeListTpl());//实习经历列表
        $this->assign('prizeHtml',self::prizeListTpl());//获奖荣誉列表
        $this->assign('activityHtml',self::activityListTpl());//课外活动
        $this->assign('certHtml',self::certListTpl());//证书
        $this->assign('schoolHtml',self::schoolListTpl());//校内职务
        $this->assign('expectHtml',self::expectListTpl());//实习意向展示
        $this->assign('expectEditHtml',self::edit_expectTpl());//实习意向编辑模板
        $this->assign('majorType',$major_type);
        $this->assign('degree2',getDegreeTextSTU(NULL));
        $this->assign('grade',getGradeText(NULL));
        $this->assign('certificate',getCertificateText(NULL));
        $this->assign('schooljob',getScholJobText(NULL));
        $this->assign('workday',getWorkDayText(NULL));
        $this->assign('daysalary',getDaySalaryText(NULL));
        $this->assign('postcate',getPostCategory());
        $this->assign('headpic',$this->StudentModel->studentAvatar());
        $this->assign('certopt',$certificate_option);
        /*加载简历完成度百分比 2015-12-14*/
        $this->assign('resumeRateHtml',$this->resumeRate());

        //2015-10-28   简历类型
        $this->assign('resumeHtml',$this->resumeTypeHtml());
        if(isMobile()){
            redirect('/MobileStudent/index');exit;
        }
        $this->display();
    }

    /*
    * @function 加载账号设置视图
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function account(){
        $this->chkStudentCenter();
        $data = $this->StudentModel->getStudentinfo(session('account.student_id'));
        $data['href'] = $this->mailLoginHref($data['login_email']);
        $this->assign('user',$data);
        $trace_result = $this->TraceSetupController->returnTraceId(session('account.student_id'));
        /*2015-12-01 默认值*/
        $traceSetupModel = D('TraceSetup');
        $now_time = date('Y-m-d',time()) . ' 07:00:00';
        if(empty($trace_result[3])){
            $traceSetupModel->add(array('trace_id' => 3, 'account_id' => session('account.student_id'),
                'crontab_time' => $now_time, 'hours' => 72, 'number' => 25, 'create_time' => dateTime()));
        }
        $trace_result = $this->TraceSetupController->returnTraceId(session('account.student_id'));
        $trace_type = array(
            'invitation_wait' => !empty($trace_result[3]) ? 'edit' : 'add',  //学生邀请数量过多
        );
        // 手机验证开始 2015-11-14 09:52:20  星期六
        $mobile = $this->StudentModel->getMobile();
        $mobile['verify'] = $mobile['status'] && $mobile['mobile_verify']==1 ? TRUE : FALSE; //手机已经验证
        $this->assign('mobile',$mobile);
        $this->assign('trace_result',$trace_result);
        $this->assign('trace_type',$trace_type);
        $this->assign('trace_number',trace_number());
        if(isMobile()){
            $this->display('/MobileStudent/account');
            exit;
        }
        $this->display('account_setting');
    }

    /*
    * @function 加载教育背景修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function educationTpl(){
        $id = I('post.id');
        $data = $this->EducationModel->studentEduInfo($id);
        $this->assign('rst',array('degree'=>getDegreeTextSTU(NULL),'base'=>$data));
        exit(json_encode(array('data'=>$this->fetch('resume_education_edit'))));
    }

    /*
    * @function 加载基本信息模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function baseInfoTpl($bool='',$index=''){
        return $this->resumeViewTpl('base',$bool,$index);
    }

    /*
    * @function ajax获取基本信息编辑模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function base_info(){
        if(IS_POST){
            $this->assign('majorType',D('MajorType')->getMajorTypeSelect());//专业分类
            $this->assign('degree2',getDegreeTextSTU());//学历
            $this->assign('grade',getGradeText());//年级
            $this->assign('edit',TRUE);
            self::getBaseInfo();
            returnjson(array('status'=>TRUE,'data'=>$this->fetch('resume_base_edit'),'code'=>200));
        }
    }

    /*
    * @function 加载教育背景列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function educationListTpl($bool=''){
        return $this->resumeViewTpl('edu',$bool);
    }

    /*
    * @function 更新基础信息
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function update_base(){
        $data = $this->CommonController->getRequestData();
        $data = $this->CommonController->arr2ToArr($data['user']);
        foreach($data as $k=>$v){
            if($k == 'id_number'){
                continue;
            }
            $v = trim($v);
            if(empty($v)){
                $error[$k] = C('L_REQUIRED_INPUT');
            } 
        }
        if($data['contact_email']){
            if(!checkMailRuleStudent($data['contact_email'])){
                $error['contact_email'] = '邮箱格式不对';
            }  
        }
        if($data['id_number']){
            //简单验证身份证正则表达式(18位)
            $isIDCard="/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}(\d{1}|X{1})$/i";
            $id_number_bool = preg_match($isIDCard,$data['id_number']);
            if(!$id_number_bool){
                $error['id_number'] = '输入合法身份证号';
            }
            if(strlen($data['id_number']) != 18){
                $error['id_number'] = '输入合法身份证号';
            }
        }
        if($data['living_city']){
            $data['living_city'] = cityNameToid($data['living_city']);
        }
        if($data['mobile_type'] == 1){
            if(!checkPhoneRule($data['contact_mobile'])){
                $error['mobile'] = C('L_PHONE_RULE');
            }
        }
        if($data['birth_date']){
            if(strtotime($data['birth_date'])>strtotime(date('Y-m-d',NOW_TIME))){
                $error['birth_date'] = C('L_BIRTH_RULE');
            }
        }
        if($data['name']){
            if(!strLength($data['name'],12)){
                $error['name'] = C('L_LIMIT_NAME');
            }
        }
        if($data['graduate_school']){
            if(!strLength($data['graduate_school'],20)){
                $error['graduate_school'] = C('L_LIMIT_SCHOOL');
            }           
        }
        if($data['detail_major']){
            if(!strLength($data['detail_major'],17)){
                $error['detail_major'] = C('L_LIMIT_MAJOR');
            }              
        }
        if(!empty($error)){
            $error['status'] = FALSE;
            $error['code'] = 1;
            exit(json_encode($error));
        }
        $bool = $this->StudentModel->studentUpdata($data);
        if($bool){
            D('Account')->makeSession(session('account.account_id'));//编辑成功后写入SESSION
            exit(json_encode(array('code'=>2,'status'=>TRUE,'msg'=>C('L_UPDATE_SUCCESS'),'data'=>self::baseInfoTpl('',TRUE))));
        }else{
            exit(json_encode(array('code'=>3,'status'=>FALSE,'msg'=>C('L_UPDATE_FAIL'))));
        }
    }

    /*
    * @function 更新教育背景
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function update_edu(){
        $data = $this->CommonController->getRequestData();
        $data = $this->CommonController->arr2ToArr($data['edu']);
        $chk = $this->chkDataNull($data);
        $where['pkid'] = $data['edu_id'];
        unset($data['edu_id']);
        $bool = $this->EducationModel->updateStuentEdu($data,$where);
        if($bool){
            $this->actionLog(9);
            $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::educationListTpl());
            exit(json_encode($result));
        }else{
            $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
            exit(json_encode($result));
        }
        
    }

    /*
    * @function 逻辑删除教育背景
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function education_del(){
        $bool = $this->EducationModel->deleteEducation(I('post.id'));
        if($bool){
            perchange($this->EducationModel,'del');
            $this->actionLog(10);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    /*
    * @function 添加教育背景
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_edu(){
        $data = $this->CommonController->getRequestData();
        $data = $this->CommonController->arr2ToArr($data['edu']);
        $chk = $this->chkDataNull($data);
        $bool = D('Education')->addEducation($data);
        if($bool){
            perchange($this->EducationModel,'add');
            $this->actionLog(8);
            $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::educationListTpl());
            exit(json_encode($result));
        }else{
            $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
            exit(json_encode($result));           
        }
    }

    /*
    * @function 添加实习经历
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_pri(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);  
            $chk = $this->chkDataNull($data);
            $bool = $this->PracticeModel->addPractice($data);
            if($bool){
                perchange($this->PracticeModel,'add');
                $this->actionLog(11);
                $html = self::priacticeListTpl();
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>$html);
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 更新实习经历
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_pri(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);  
            $chk = $this->chkDataNull($data);
            $bool = $this->PracticeModel->updatePractice($data,array('pkid'=>$data['practice_id']));
            if($bool){
                $this->actionLog(12);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::priacticeListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }


    /*
    * @function 加载实例经历列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function priacticeListTpl($bool=''){
        return $this->resumeViewTpl('priactice',$bool);
    }

    /*
    * @function 加载实习经历修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function practiceTpl(){
        $id = I('post.id');
        $data = $this->PracticeModel->getPracticePkid($id);
        $data['content'] = textareaFormat($data['content'],TRUE);
        $this->assign('base',$data);
        exit(json_encode(array('data'=>$this->fetch('resume_practice_edit'))));
    }

    /*
    * @function 逻辑删除实习经历
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function practice_del(){
        $bool = $this->PracticeModel->deletePractice(I('post.id'));
        if($bool){
            perchange($this->PracticeModel,'del');
            $this->actionLog(13);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    /*
    * @function 添加获奖荣誉
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_prize(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);        
            $chk = $this->chkDataNull($data,FALSE);
            if(!strLength($data['description'])){
                $error['description'] = $this->error_border;
                $error['status'] = FALSE;
                $error['code'] = 1;
                exit(json_encode($error));
            }
            $data['language_type'] = 1;
            $bool = $this->PrizeExperienceModel->addPrize($data);
            if($bool){
                perchange($this->PrizeExperienceModel,'add');
                $this->actionLog(14);
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::prizeListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 加载获奖经历列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function prizeListTpl($bool=''){
        return $this->resumeViewTpl('prize',$bool);
    }

    /*
    * @function 加载获奖荣誉修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function prizeTpl(){
        $id = I('post.id');
        $data = $this->PrizeExperienceModel->getPrizePkid($id);
        $this->assign('base',$data);
        exit(json_encode(array('data'=>$this->fetch('resume_prize_edit'))));
    }

    /*
    * @function 更新获奖荣誉
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_prize(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);        
            $chk = $this->chkDataNull($data,FALSE);
            if(!strLength($data['description'])){
                $error['description'] = $this->error_border;
                $error['status'] = FALSE;
                $error['code'] = 1;
                exit(json_encode($error));
            }
            $bool = $this->PrizeExperienceModel->updatePrize($data,array('pkid'=>$data['prize_id']));
            if($bool){
                $this->actionLog(15);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::prizeListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 逻辑删除获奖经历
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function prize_del(){
        $bool = $this->PrizeExperienceModel->deletePrize(I('post.id'));
        if($bool){
            perchange($this->PrizeExperienceModel,'del');
            $this->actionLog(16);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    /*
    * @function 添加课外活动
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_activity(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);
            $chk = $this->chkDataNull($data,TRUE,TRUE);
            if(!strLength($data['description'])){
                $error['description'] = $this->error_border;
                $error['status'] = FALSE;
                $error['code'] = 1;
                exit(json_encode($error));
            }
            $data['language_type'] = 1;
            $bool = $this->ActivityExperienceModel->addActivity($data);
            if($bool){
                perchange($this->ActivityExperienceModel,'add');
                $this->actionLog(17);
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::activityListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 更新课外活动
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_activity(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']); 
            $chk = $this->chkDataNull($data,TRUE,TRUE);
            if(!strLength($data['description'])){
                $error['description'] = $this->error_border;
                $error['status'] = FALSE;
                $error['code'] = 1;
                exit(json_encode($error));
            }
            $bool = $this->ActivityExperienceModel->updateActivity($data,array('pkid'=>$data['activity_id']));
            if($bool){
                $this->actionLog(18);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::activityListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 加载课外活动列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function activityListTpl($bool=''){
        return $this->resumeViewTpl('activity',$bool); 
    }

    /*
    * @function 加载获奖荣誉修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function activityTpl(){
        $id = I('post.id');
        $data = $this->ActivityExperienceModel->getActivityPkid($id);
        $this->assign('base',$data);
        exit(json_encode(array('data'=>$this->fetch('resume_activity_edit'))));
    }

    /*
    * @function 逻辑删除课外活动
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function activity_del(){
        $bool = $this->ActivityExperienceModel->deleteActivity(I('post.id'));
        if($bool){
            perchange($this->ActivityExperienceModel,'del');
            $this->actionLog(19);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    /*
    * 获取分类下证书
    * @author george.zou@gaodun.cn
    */ 
    public function cert_list(){
        if(IS_POST){
            $major_data = D('Certificate')->certList(I('post.id'));
            $option = '';
            if($major_data){
                foreach($major_data as $k=>$v){
                    $option .= '<option value='.$v['pkid'].'>'.$v['full_name'].'</option>';
                }
                $data = array('status'=>TRUE,'data'=>$option,'msg'=>'获取成功');
            }else{
                $option = '<option value="" selected="TRUE">暂无数据</option>';
                $data = array('status'=>FALSE,'data'=>$option,'msg'=>'获取失败');
            }
            exit(json_encode($data));
        }
    }

    /*
    * @function 添加证书
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_cert(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);     
            $chk = $this->chkDataNUllCert($data);
            isset($data['certificate_name']) ? $data['certificate_id']=0 :'';            
            if(isset($data['certificate_id']) && $data['certificate_id']>0){
                $data['certificate_name'] = D('Certificate')->getCertFullName($data['certificate_id']);
            }
            $data['language_type'] = 1;
            $bool = $this->StudentCertificateModel->addCertificate($data);
            if($bool){
                perchange($this->StudentCertificateModel,'add');
                $this->actionLog(20);
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::certListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 更新证书
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_cert(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);
            $chk = $this->chkDataNUllCert($data,FALSE);
            isset($data['certificate_name']) ? $data['certificate_id']=0 :'';            
            if(isset($data['certificate_id']) && $data['certificate_id']>0){
                $data['certificate_name'] = D('Certificate')->getCertFullName($data['certificate_id']);
            }

            $bool = $this->StudentCertificateModel->updateCertificate($data,array('pkid'=>$data['cert_id']));
            if($bool){
                $this->actionLog(21);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::certListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 加载证书列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function certListTpl($bool=''){
        return $this->resumeViewTpl('cert',$bool);    
    }

    /*
    * @function 加载证书修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function certificateTpl(){
        $id = I('post.id');
        $certificate = getCertificateText(NULL);//证书类型
        $data = $this->StudentCertificateModel->getCertificateInfo($id);
        $data2 = D('Certificate')->getCertificateInfo($data['certificate_id']);
        $data3 = D('Certificate')->getCartTypeList($data2['certificate_type']);
        foreach($data3 as $k=>$v){
            $data4[$v['pkid']] = $v['full_name'];
        }
        $data['certificate_type'] = $data2['certificate_type'];
        $data['child'] = $data4;
        $this->assign('base',$data);
        $this->assign('certificate',$certificate);
        exit(json_encode(array('data'=>$this->fetch('resume_cert_edit'))));

      
    }

    /*
    * @function 逻辑删除证书
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function cert_del(){
        $bool = $this->StudentCertificateModel->deleteCertificate(I('post.id'));
        if($bool){
            perchange($this->StudentCertificateModel,'del');
            $this->actionLog(22);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    /*
    * @function 添加校内职务
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_school(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);  
            $chk = $this->chkDataNull($data);
            $data['language_type'] = 1;
            $bool = $this->JobExperienceModel->addJobExperience($data);
            if($bool){
                perchange($this->JobExperienceModel,'add');
                $this->actionLog(23);
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::schoolListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 加载校内职务列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function schoolListTpl($bool=''){
        return $this->resumeViewTpl('school',$bool);
    }

    /*
    * @function 加载校内职务修改模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function schoolTpl(){
        $id = I('post.id');
        $data = $this->JobExperienceModel->jobExperienceInfo($id);
        $data['description'] = textareaFormat($data['description'],TRUE);
        $this->assign('base',$data);
        exit(json_encode(array('data'=>$this->fetch('resume_school_edit'))));
    }

    /*
    * @function 编辑校内职务
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_school(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);  
            $chk = $this->chkDataNull($data);
            $bool = $this->JobExperienceModel->updateJobExperience($data,array('pkid'=>$data['school_id']));
            if($bool){
                $this->actionLog(24);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::schoolListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }
    }

    /*
    * @function 逻辑删除校内职务
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function school_del(){
        $bool = $this->JobExperienceModel->deleteJobExperience(I('post.id'));
        if($bool){
            perchange($this->JobExperienceModel,'del');
            $this->actionLog(25);
            $result = array('msg'=>C('L_DELETE_SUCCESS'),'status'=>TRUE,'code'=>4);
            exit(json_encode($result));           
        }else{
            $result = array('msg'=>C('L_DELETE_FAIL'),'status'=>FALSE,'code'=>5);
            exit(json_encode($result));              
        }
    }

    //更新自我介绍
    public function add_introduce(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $data = $this->CommonController->arr2ToArr($data['data']);  
            if(!trim($data['dear_hr'])){
                $error['msg'] = '必填';
                $error['status'] = FALSE;
                $error['code'] = ($this->controller_code)+1;
                returnjson($error);
            }
            if(!strLength($data['dear_hr'],300)){
                $error['msg'] = '限300字内';
                $error['status'] = FALSE;
                $error['code'] = ($this->controller_code)+2;
                returnjson($error);
            }
            //对是否存在dearHr进行判断
            $student_result = $this->StudentModel->field('dear_hr')->find(session('account.student_id'));
            $bool = $this->StudentModel->update_introduce($data['dear_hr']);
            if($bool){
                if(empty($student_result['dear_hr'])){
                    $stData = array(
                        'pkid' => session('account.student_id'),
                        'complete_rate' => array('exp','complete_rate+'.$this->rate_base)
                    );
                    $this->StudentModel->save($stData);
                }
                $error['msg'] = '添加成功';
                $error['status'] = TRUE;
                $error['code'] = ($this->controller_code)+3;
                $error['data'] = textareaFormat($this->introduceTpl());// ;
                returnjson($error);
            }else{
                $error['msg'] = '添加失败';
                $error['status'] = FALSE;
                $error['code'] = ($this->controller_code)+4;
                returnjson($error);
            }
        }
    }

    //自我介绍模板
    public function introduceTpl(){
        return trim($this->StudentModel->getIntroduce());
    }

    //ajax获取自我介绍模板
    public function get_introduceTpl(){
        $data['data'] = textareaFormat($this->introduceTpl(),TRUE);
        returnjson($data);
    }

    //获取热门城市
    public function hotcity(){
        return $this->CommonController->hotCityArr();
    }

    /*
    * @function 添加实习意向
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function add_expect(){
        if(IS_POST){
            if($this->PostSubscribeModel->existExpect()){
                $result = array('msg'=>C('L_RESUME_EXIST'),'status'=>FALSE,'code'=>4);
                exit(json_encode($result));  
            }
            $data = $this->CommonController->getRequestData();
            $city_text_value = expectCityText($data['data']);
            $data = $this->CommonController->arr2ToArr($data['data']);
            $data['city_text'] = $city_text_value;
            $chk = $this->chkDataNUllExpect($data);
            $city_id = expectCityID($data['city_text']);
            unset($data['city_text']);
            if($city_id){
                $data['expect_city'] = $city_id;
            }
            $bool = $this->PostSubscribeModel->addPostExpect($data);
            if($bool){
                $stData = array(
                    'pkid' => session('account.student_id'),
                    'complete_rate' => array('exp','complete_rate+'.$this->rate_base),
                    'complete_rate_en' => array('exp','complete_rate_en+'.$this->rate_base),
                );
                $this->StudentModel->save($stData);
                $this->actionLog(26);
                $result = array('msg'=>C('L_ADD_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::expectListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_ADD_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }       
    }

    /*
    * @function 更新实习意向
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function edit_expect(){
        if(IS_POST){
            $data = $this->CommonController->getRequestData();
            $city_text_value = expectCityText($data['data']);
            $data = $this->CommonController->arr2ToArr($data['data']);
            $data['city_text'] = $city_text_value;
            $chk = $this->chkDataNUllExpect($data,FALSE);
            $city_id = expectCityID($data['city_text']);
            $pkid = $data['expect_id'];
            unset($data['city_text']);
            unset($data['expect_id']);
            if($city_id){
                $data['expect_city'] = $city_id;
            }
            $bool = $this->PostSubscribeModel->updatePostExpect($data,array('pkid'=>$pkid));
            if($bool){
                $this->actionLog(27);
                $result = array('msg'=>C('L_UPDATE_SUCCESS'),'status'=>TRUE,'code'=>2,'data'=>self::expectListTpl());
                exit(json_encode($result));
            }else{
                $result = array('msg'=>C('L_UPDATE_FAIL'),'status'=>FALSE,'code'=>3);
                exit(json_encode($result));           
            }   
        }       
    }

    /*
    * @function 实习意向列表模板
    * @author 致远<george.zou@gaodun.cn> 
    */
    public function expectListTpl($student_id=''){
        $data['period_start'] = '';
        $data['period_finish'] = '';
        $data = $this->PostSubscribeModel->getLastExpect($student_id);
        $data['week_available'] = $data['week_available'] ? getWorkDayText($data['week_available']) : '';
        $data['salary_range'] = $data['salary_range'] ? getDaySalaryText($data['salary_range']) : '';
        $data['city']['region_name'] = $data['expect_city'] ? expectCityName($data['expect_city']) : '';
        $data['category_text'] = $data['expect_category'] ? D('PostCategory')->getCategoryText($data['expect_category']) : '';
        if($data['period_start'] == $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 1;
        }elseif($data['period_start'] != $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 2;
        }else{
            $data['dateType'] = 3;
        }
        $this->assign('base',$data);
        return $this->fetch('resume_expect_ul');
    }

    /*
    * @function 加载实习意向编辑模板
    * @author 致远<george.zou@gaodun.cn>最后更新时间
    */
    public function edit_expectTpl(){
        $data = $this->PostSubscribeModel->getLastExpect();
        $data['city']['region_name'] = expectCityNameLI($data['expect_city']);
        if($data['period_start'] == $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 1;
        }elseif($data['period_start'] != $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 2;
        }else{
            $data['dateType'] = 3;
        }
        $this->assign('base',$data);
        $this->assign('workday',getWorkDayText(NULL));
        $this->assign('daysalary',getDaySalaryText(NULL));
        $this->assign('postcate',getPostCategory());
        return $this->fetch('resume_expect_edit');      
    }

    /*
    * @function 
    * @author 致远<george.zou@gaodun.cn>最后更新时间
    */
    public function expect_tpl(){
        if(IS_POST){
            returnjson(array('status'=>TRUE,'code'=>200,'data'=>self::edit_expectTpl()));
        }
    }

    /*
    * @function AJAX访问用于计算学生简历完成比值插件渲染后的简历比值
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function resume_rate(){
        exit(json_encode($this->resumeRate()));
    }

    public function resumeRate(){
        $resume = $this->resumeModule();
        $result = $this->StudentModel->getBaseInfo();
        $rate = intval($result['complete_rate']);
        foreach($resume as $key => $v){
            if(!$v){
                if(isset($this->resume_type[$key])){
                    $resumeText[] = $this->resume_type[$key];
                }
            }
        }
        if(!empty($resumeText) && isset($resumeText)){
            $resume_text = implode('、',$resumeText);
        }
        if($rate < 23){
            $rate_style = array('span' => 'schedule-red','div' => 'diag-complete-red','i' => 'font-red');
        }elseif($rate >= 23 && $rate < 67){
            $rate_style = array('span' => 'schedule-orange','div' => 'diag-complete-orange','i' => 'font-orange');
        }elseif($rate > 67){
            $rate_style = array('span' => 'schedule-blue','div' => 'diag-complete-blue','i' => 'font-blue');
        }
        $this->assign('resume_text',$resume_text);
        $this->assign('rate_style',$rate_style);

//        if(empty($rate)){
//            $resume = self::resumeModule();
//            unset($resume['avatar_bool']);//删除头像
//            unset($resume['school_acticity_bool']);//
//            unset($resume['cert_prize_bool']);//
//            $i=0;
//            if($resume['expect_bool'] && $resume['base_bool']){
//                foreach($resume as $v){
//                    if($v){
//                        $i++;
//                    }
//                }
//            }else{
//                $i=1;
//            }
//            $rate = $i*($this->rate_base);
//            $stData = array(
//                'pkid' => session('account.student_id'),
//                'complete_rate' => $rate
//            );
//            $this->StudentModel->save($stData);
//        }
        $this->assign('number',$rate>90 ? 100 : $rate);
        return $this->fetch('resume_rate');
    }

    //上传头像的页面加载
    public function photo(){
        $this->assign('headpic',$this->StudentModel->studentAvatar());
        $this->display('head_pic');
    }

    /*
    * @function 删除简历模块
    * @param int:$post , 不同的参数代表不同的模块
    * @return json
    * @author 致远<george.zou@gaodun.cn>    
    */
    public function delete_module(){
        $id = intval(I('post.id'));
        switch ($id) {
            case 0:
                $education_bool = $this->EducationModel->existEducation();
                if($education_bool){
                    $bool = $this->EducationModel->deleteEducationModule();// 教育背景
                }
                break;
            case 1: 
                $priactice_bool = $this->PracticeModel->existPractice();
                if($priactice_bool){
                   $bool = $this->PracticeModel->deletePracticeModule();// 实习经历 
                }
                break;
            case 2:
                // 2015-12-15
                $activity_bool = $this->ActivityExperienceModel->existActivity();
                $school_bool = $this->JobExperienceModel->existCertificate();
                if($activity_bool){
                    $bool_2 = $this->ActivityExperienceModel->deleteActivityModule();// 课外活动
                }
                if($school_bool){
                    $bool = $this->JobExperienceModel->deleteJobExperienceModule();// 校内职务
                }
                if($activity_bool && $school_bool){
                    $this->rate_base = $this->rate_base*2;
                }
                if($bool_2 || $bool){
                    $bool = TRUE;
                }
                break;
            case 3:
                //2015-12-15
                $cert_bool = $this->StudentCertificateModel->existCertificate();
                $prize_bool = $this->PrizeExperienceModel->existPrize();
                if($cert_bool){
                    $bool_2 = $this->StudentCertificateModel->deleteCertificateModule();// 荣誉证书
                }
                if($prize_bool){
                    $bool = $this->PrizeExperienceModel->deletePrizeModule();// 获奖荣誉
                }
                if($cert_bool && $prize_bool){
                  $this->rate_base = $this->rate_base*2;  
                }
                if($bool_2 || $bool){
                    $bool = TRUE;
                }
                break;
            case 4:
                $dear_bool = $this->StudentModel->introduceExist($student_id);
                if($dear_bool){
                    $bool = $this->StudentModel->deleteIntroduceModule();//自我介绍
                    break;                   
                }
        }
        if($bool){
            $data = array(
                'pkid' => session('account.student_id'),
                'complete_rate' => array('exp','complete_rate-'.$this->rate_base)
            );
            $this->StudentModel->save($data);
            $arr = array(10,13,16,19,22,25);
            $this->actionLog($arr[$id]);
            exit(json_encode(array('status'=>TRUE,'msg'=>C('L_DELETE_SUCCESS'))));
        }else{
            exit(json_encode(array('status'=>FALSE,'msg'=>C('L_DELETE_FAIL'))));
        }
    }

    /*
    * @function 学生预览自己的简历
    * @author 致远<george.zou@gaodun.cn>    
    */
    public function like(){
        $this->CommonController->chkStudentCenter();
        $this->assign('exist',self::resumeModule());
        $this->assign('titleHtml',$this->EnStudentController->resumeTitle('','student'));
        $this->assign('introduceHtml',textareaFormat($this->introduceTpl()));
        $this->assign('baseInfoHtml',self::baseInfoTpl('pre'));//简历基本信息
        $this->assign('educationHtml',self::educationListTpl('pre'));//教育背景列表
        $this->assign('practiceHtml',self::priacticeListTpl('pre'));//实习经历列表
        $this->assign('prizeHtml',self::prizeListTpl('pre'));//获奖荣誉列表
        $this->assign('activityHtml',self::activityListTpl('pre'));//课外活动
        $this->assign('certHtml',self::certListTpl('pre'));//证书
        $this->assign('schoolHtml',self::schoolListTpl('pre'));//校内职务
        $this->assign('expectHtml',self::expectListTpl());//实习意向展示
        $this->assign('headpic',$this->StudentModel->studentAvatar());
        if(isMobile()){
            redirect('/MobileStudent/resume');
            exit;
        }
        $this->display('resume_look');
    }

    /*
    * @function 企业浏览投递学生简历、后台浏览学生简历
    * @param $pkid 简历主键ID
    * @param $role=2 超级会员ID后台浏览
    * @return 
    * @author 致远<george.zou@gaodun.cn>最后更新时间
    */
    public function resume(){
        $pkid = enInt(I('get.id'));
        $role_id = isset($_GET['role'])? intval(I('get.role')) : 0;
        if($role_id != 2){
            if($this->checkSessionLogin() && session('account.account_type')==1 ){
               $where['enterprise_id'] = session('account.enterprise_id'); 
               if(isset($_GET['type']) && isset($_GET['invite'])){
                    $status = $_GET['type']=='true' ? TRUE : FALSE;
                    $wait = $_GET['invite']=='true' ? TRUE : FALSE;//TURE表示投递,FALSE表示邀请
                    $id = I('get.p_r_id');
                    D('ResumePost')->readResume($id);//更新投递状态 2015-12-10
                    $this->assign('invite',array('status'=>$status,'wait'=>$wait,'id'=>$id));
               }
           }else{
                if(session('account.account_type')==2){
                    redirect('/');
                }else{
                    $url = base64_encode('/Student/resume/id/'.I('get.id'));
                    redirect('/Account/login/func/'.$url);                         
                }
           }
        }else{ //超级管理员
            if(session('account.account_email') != $this->admin_email){
                redirect('/');exit();
            }
        }
        $where['pkid'] = $pkid;
        if(is_numeric($pkid)){
            /*
             * 新增判断，如果是附件简历，则直接跳转至附件简历页面
             * 2015-11-02
             */
            $result = $this->ResumeModel->field('resume_type')->where($where)->find();
            if(!$result){
                $this->logout();
            }
            if($result['resume_type'] == 4){
                redirect('/PageOffice/resume/id/'.enInt($pkid));
            }
            // 2016-01-07 该简历学生是否投递过企业，否则屏蔽联系信息
            $int = M('resume as re')
                        ->join('inner join resume_post as rp on re.student_id=rp.student_id')
                        ->field('rp.pkid')
                        ->where(array('re.pkid'=>$pkid,'rp.enterprise_id'=>session('account.enterprise_id')))
                        ->count();
            if($int>0){
                 $this->assign('postReumeNone',TRUE);
            }
            $this->assign('titleHtml',$this->EnStudentController->resumeTitle($pkid,'Enterprise'));
            $this->assign('centerHtml',$this->centerPreview($pkid));
            $this->display('resume_look_view');
        }else{
            returnjson(array('msg'=>'student.resume.error'));
        }
    }

    /*
    * @function 推荐简历库，企业浏览学生简历
    * @param $pkid 简历主键ID
    * @return $post_id 职位id
    * @author 致远<george.zou@gaodun.cn>最后更新时间
    */
    public function resumeInvite(){
        $pkid = enInt($_GET['id']);
        $post_id = enInt($_GET['pid']);
        $invite_bool = D('InterviewInvitation')->checkPostResume($post_id,$pkid);
        if(is_numeric($pkid) && is_numeric($post_id)){
            // 2016-01-07 该简历学生是否投递过企业，否则屏蔽联系信息
            $int = M('resume as re')
                        ->join('inner join resume_post as rp on re.student_id=rp.student_id')
                        ->field('rp.pkid')
                        ->where(array('re.pkid'=>$pkid,'rp.enterprise_id'=>session('account.enterprise_id')))
                        ->count();
            if($int>0){
                 $this->assign('inviteViewNone',TRUE);
            }
            $this->assign('inviteView',TRUE);
            $this->assign('info',array('r_id'=>$_GET['id'],'p_id'=>$_GET['pid'],'invite'=>$invite_bool));
            // 2015-12-29
            $resume_type = M('resume')->field('resume_type')->find($pkid);
            $this->assign('titleHtml',resumePostType($resume_type['resume_type']));
            $this->assign('centerHtml',$this->centerPreview($pkid,$post_id));
            $this->display('resume_look_view_invite');
        }else{
            returnjson(array('msg'=>'student.resume.error'));
        }
    }

    /*
    * @function 企业浏览学生简历时右侧的推荐简历
    * @param int:$student_id 学生主键PKID
    * @param int:$post_id 职位id为真，则表示为邀请推荐
    * @author 致远<george.zou@gaodun.cn>最后更新时间
    */
    public function getRecomStudent($student_id,$post_id=''){
            $recommand = $this->PostSubscribeModel->getLastExpect($student_id);
            $education = $this->StudentModel->getEducation($student_id);
            $expect_city_arr = explode(',',$recommand['expect_city']);
            $where['post_subscribe.expect_city'] = array('like',$expect_city_arr);
            $where['post_subscribe.expect_category'] = $recommand['expect_category'];
            $where['post_subscribe.week_available'] = $recommand['week_available'];
            $where['post_subscribe.student_id'] = array('neq',$student_id);
            $where['student.education'] = $education;
            $return = $this->PostSubscribeModel->getRecommand($where);
            $data = $return['data'];
            foreach($data as $k=>$v){
                if($v['avatar']){
                    $data[$k]['avatar'] = imageExist($v['avatar']);
                }else{
                    $data[$k]['avatar']  = C('APP_URL').'/Public/static/images/new_nophoto_student.png';
                }
            }
            $this->assign('recommand',$data);
            $this->assign('student',$student_id);
            $this->assign('page',$return['page']);
            $this->assign('post_id',$post_id);
            return $this->fetch('/Student/resume_look_ul');
    }

    /*
    * @function 通过AJAX获取推荐的学生简历
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function ajaxRecomStu(){
        $id = I('post.data');
        $post = enInt(I('post.post'));
        $html = $this->getRecomStudent(intval($id),intval($post));
        $html ? exit(json_encode(array('msg'=>$html))) : '';
    }

    //写入日志文件
    public function actionLog($id){
        actionLogAdd($id,session('account'));
    }

    // 2015-08-31 15:04:07  星期一 致远<george.zou@gaodun.cn> ('<^>') 
    public function share(){
        fromVal();
        $base_url = '/Special/highMall';
        $from_id = isset($_GET['from']) ?  intval(I('from')) : 0; 
        $id = isset($_GET['id']) ? $_GET['id'] : FALSE;
        $base_url = $base_url .'/from/'.$from_id;
        if($id){
            $base_url = $base_url.'/id/'.intval($id);
        }
        redirect($base_url);
    }
    
    //推送设置
    public function traceSave(){
        $data = I('post.');
        $type = $data['type'];
        $data['crontab_time'] = $this->TraceSetupController->now_time;
        $data['trace_id'] = $this->trace_id[$data['name']];
        $data['account_id'] = session('account.student_id');
        if(!empty($data['id'])){
            $data['pkid'] = $data['id'];
        }
        if($type == 'add'){
            $data['create_time'] = date('Y-m-d H:i:s',time());
        }
        unset($data['type']);
        unset($data['name']);
        unset($data['id']);
        $result = $this->TraceSetupController->saveTrace($data,$type);
        if($result !== FALSE){
            $return_json['status'] = 'success';
            $type == 'add' ? $return_json['id'] = $result : '';
            exit(json_encode($return_json));
        }else{
            exit(json_encode(array('status' => 'fail')));
        }
    }


    /*
    * @function 判断form数据是否为空
    * @param array:$data
    * @param bool:$type,默认TURE,需要验证时间字段;FALSE不需要验证时间字段
    * @param bool:$filed,默认FALSE,验证时间字段为start_time和finish_time，为TURE验证字段为period和period_end
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function chkDataNull($data,$type=TRUE,$field=FALSE){
        if($type){
            if($field){
                if(strtotime($data['period'])>strtotime($data['period_end'])){
                    $error['period_end'] = $this->error_border;
                }else{
                    $success['period_end'] = $this->success_border;
                } 
            }else{
                if(strtotime($data['start_time'])>strtotime($data['finish_time'])){
                    $error['finish_time'] = $this->error_border;
                }else{
                    $success['finish_time'] = $this->success_border;
                }                
            }
        }        
        foreach($data as $k=>$v){
            if(empty($v)){
                $error[$k] = $this->error_border;
            }else{
                $success[$k] = $this->success_border;
            }
        }
        if(!empty($error)){
            $error['status'] = FALSE;
            $error['code'] = 1;
            exit(json_encode($error));
        }else{
            return '';
        }
    }

    /*
    * @function 新增、编辑荣誉证书时验证form数据
    * @param bool:$type 默认为TRUE，新增时验证;为FALSE时编辑时验证
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function chkDataNUllCert($data,$type=TRUE){
        if(isset($data['certificate_name'])){
           unset($data['certificate_id']); 
        }else{
            if(!$data['certificate_id']){
                $error['certificate_id'] = $this->error_border;
            }
            if($type){
                $cert_bool = $this->StudentCertificateModel->checkOnlyCertificate($data['certificate_id']);
            }else{
                $cert_bool = $this->StudentCertificateModel->checkOnlyCertificate($data['certificate_id'],FALSE,$data['cert_id']);
            }
            if($cert_bool){
                $error['certificate_id'] = $this->error_border;
                $cert_bool_info = '请勿选择重复的证书';
            }
        }
        foreach($data as $k=>$v){
            if(empty($v)){
                $error[$k] = $this->error_border;
            }else{
                $success[$k] = $this->success_border;
            }
        }
        if(!empty($error)){
            $error['status'] = FALSE;
            $error['code'] = 1;
            $error['cert_bool'] = $cert_bool_info ? $cert_bool_info : '';
            exit(json_encode($error));
        }else{
            return '';
        }
    }

    /*
    * @function 新增、编辑实习意向时验证form数据
    * @param bool:$type 默认为TRUE，新增时验证;为FALSE时编辑时验证
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function chkDataNUllExpect($data,$type=TRUE){
        if($type){
            if(empty($data['period_start'])){
                unset($data['period_start']);
            }
            if(empty($data['period_finish'])){
                unset($data['period_finish']);
            }
            if(!empty($data['period_start']) && !empty($data['period_finish'])){
                if(strtotime($data['period_start'])>strtotime($data['period_finish'])){
                    $error['period_finish'] = $this->error_border;
                }else{
                    $success['period_finish'] = $this->success_border;
                }
            }           
        }else{
            if(empty($data['period_start'])){
                $data['period_start'] = $this->date_type;
            }
            if(empty($data['period_finish'])){
                $data['period_finish'] = $this->date_type;
            }
            if($data['period_start'] != $this->date_type && $data['period_finish'] != $this->date_type){
                if(strtotime($data['period_start'])>strtotime($data['period_finish'])){
                    $error['period_finish'] = $this->error_border;
                }else{
                    $success['period_finish'] = $this->success_border;
                }
            }            
        }
        foreach($data as $k=>$v){
            if(empty($v)){
                $error[$k] = $this->error_border;
            }else{
                $success[$k] = $this->success_border;
            }
        }
        if(!empty($error)){
            $error['status'] = FALSE;
            $error['code'] = 1;
            exit(json_encode($error));
        }else{
            return '';
        }
    }

    /*
    * @function 学生简历不同模块的视图模板
    * @param bool:$edit 默认FALSE,隐藏编辑按钮;为TRUE则显示编辑按钮
    * @param string:$type
    * @param string:$index 是否为简历首页
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function resumeViewTpl($type,$edit='',$index=''){
        if($type=='base'){
            if($index){
                $this->assign('baseTitleBool',TRUE);
            }
            self::getBaseInfo();
            $tpl = 'resume_base';
        }
        if($type=='edu'){
            $education = $this->EducationModel->studentEduList();
            if(!empty($education) && is_array($education)){
                foreach($education as $k=>$v){
                    $education[$k]['degree'] = getDegreeTextSTU($v['degree']==0?5:intval($v['degree']));
                    $education[$k]['start_time'] = date3ToFormate($v['start_time']);
                    $education[$k]['finish_time'] = date3ToFormate($v['finish_time']);
                }
            }
            $this->assign('education',$education);
            $tpl = 'resume_education_edit_ul';
        }
        if($type=='priactice'){
            $practice = $this->PracticeModel->getPractice();
            if(!empty($practice) && is_array($practice)){
                foreach($practice as $k=>$v){
                    $practice[$k]['content'] = textareaFormat($v['content']);// 接着去掉两个空格以上
                    $practice[$k]['start_time'] = date3ToFormate($v['start_time']);
                    $practice[$k]['finish_time'] = date3ToFormate($v['finish_time']);
                }
            }
            $this->assign('practice',$practice);
            $tpl = 'resume_priactice_ul';                
        }
        if($type=='prize'){
            $prize = $this->PrizeExperienceModel->getPrize();
            if(!empty($prize) && is_array($prize)){
                foreach($prize as $k=>$v){
                    $prize[$k]['period'] = date3ToFormate($v['period']);
                }
            }
            $this->assign('prize',$prize);
            $tpl = 'resume_prize_ul';
        }
        if($type=='activity'){
            $activity = $this->ActivityExperienceModel->getActivity();
            if(!empty($activity) && is_array($activity)){
                foreach($activity as $k=>$v){
                    $activity[$k]['period'] = date3ToFormate($v['period']);
                    $activity[$k]['period_end'] = date3ToFormate($v['period_end']);
                }
            }
            $this->assign('activity',$activity);
            $tpl = 'resume_activity_ul'  ; 
        }
        if($type=='cert'){
            $cert = $this->StudentCertificateModel->getcert();
            $type = D('Certificate');
            if(!empty($cert) && is_array($cert)){
                foreach($cert as $k=>$v){
                    $cert[$k]['certificate'] = $v['certificate_name'];
                    $cert[$k]['status'] = getCertStatusText($v['status']);
                    $cert[$k]['finish_time'] = date3ToFormate($v['finish_time']);
                }
            }
            $this->assign('cert',$cert);
            $tpl = 'resume_cert_ul';
        }
        if($type=='school'){
            $school = $this->JobExperienceModel->jobExperienceList();
            $job = D('SchoolJob');
            if(!empty($school) && is_array($school)){
                foreach($school as $k=>$v){
                    $school[$k]['description'] = textareaFormat($v['description']);
                    $school[$k]['start_time'] = date3ToFormate($v['start_time']);
                    $school[$k]['finish_time'] = date3ToFormate($v['finish_time']);
                }
            }
            $this->assign('school',$school);
            $tpl = 'resume_school_ul';
        }
        $edit ? $this->assign('pre',TRUE) : '';
        return $this->fetch($tpl);
    }

    //基础信息模块
    protected function getBaseInfo($account_id=''){
        $base_info = $this->StudentModel->getBaseInfo($account_id);//简历基本信息
        if($base_info){
            $base_info['gender_text'] = getGerderText($base_info['gender']);
            $base_info['current_grade_text'] = getGradeText($base_info['graduate_year']?$base_info['graduate_year']:'');
            $base_info['education_text'] = getDegreeTextSTU($base_info['education']);
            $base_info['politics_status_text'] = getPoliticsText($base_info['politics_status']); 
            $nothing = '';
            $base_info['major_type_text'] = getMajorTypeText($base_info['major_type'],$nothing);
            $base_info['detail_major'] = trim($base_info['detail_major']);
            $base_info['living_city'] = regionIdToname($base_info['living_city']);
            $base_info['gender']  = empty($base_info['gender']) ? 1: $base_info['gender'];
            $this->assign('baseInfo',$base_info);
            $this->assign('headpic',$this->StudentModel->studentAvatar());
        }
    }

    /*
    * @function 判断学生简历激活的模块
    * @param int:$student_id , 默认为''
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    protected function resumeModule($student_id=''){
        $resume['avatar_bool'] = $this->StudentModel->existAvatar($student_id);
        $resume['base_bool'] = $this->StudentModel->existBase($student_id);
        $resume['expect_bool'] = $this->PostSubscribeModel->existExpect($student_id);
        $resume['education_bool'] = $this->EducationModel->existEducation($student_id);
        $resume['practice_bool'] = $this->PracticeModel->existPractice($student_id);
        $resume['prize_bool'] = $this->PrizeExperienceModel->existPrize($student_id);
        $resume['activity_bool'] = $this->ActivityExperienceModel->existActivity($student_id);
        $resume['cert_bool'] = $this->StudentCertificateModel->existCertificate($student_id);
        $resume['school_bool'] = $this->JobExperienceModel->existCertificate($student_id);
        $resume['school_acticity_bool'] = ($resume['school_bool'] || $resume['activity_bool']) ? TRUE : FALSE;
        $resume['cert_prize_bool'] = ($resume['cert_bool'] || $resume['prize_bool']) ? TRUE : FALSE;
        $resume['introduce_bool'] = $this->StudentModel->introduceExist($student_id);
        //判断是否需要出现黑色图片  2015-11-17
        if($resume['base_bool'] && $resume['expect_bool']){
            if($resume['education_bool'] || $resume['practice_bool'] || $resume['school_acticity_bool'] ||
                $resume['cert_prize_bool'] || $resume['introduce_bool']){
                $resume['back_bool'] = true;
            }
        }else{
            $resume['back_bool'] = true;
        }

        return $resume;
    }

    // 判断学生简历的激活模块，兼容 resumeModule()
    protected function resumeModule2($student_id){
        return self::resumeModule($student_id);
    }

       /*
     * @function 将预览简历部分提取
     * @param int:$invite_post_id 职位id为真，则表示为邀请预览
     * @update by allen
     * @time 2015-10-09
     */
    protected function centerPreview($pkid = '',$invite_post_id=FALSE){
        $student_id = D('Resume')->studentIdByKey($pkid);
        if($student_id) {
            if (isMobile()) {
                redirect('/MobileStudent/resumeView/id/' . $student_id);
                exit;
            }
            $account_id = $this->StudentModel->getAccountId($student_id);
            $recommand = self::getRecomStudent($student_id,$invite_post_id);
            self::getBaseInfo($account_id);
            $education = $this->EducationModel->studentEduList($student_id);
            foreach ($education as $k => $v) {
                $education[$k]['degree'] = getDegreeTextSTU($v['degree'] == 0 ? 5 : intval($v['degree']));
                $education[$k]['start_time'] = date2ToFormate($v['start_time']);
                $education[$k]['finish_time'] = date2ToFormate($v['finish_time']);
            }
            $practice = $this->PracticeModel->getPractice($student_id);
            foreach ($practice as $k => $v) {
                $practice[$k]['content'] = textareaFormat($v['content']);//
                $practice[$k]['start_time'] = date2ToFormate($v['start_time']);
                $practice[$k]['finish_time'] = date2ToFormate($v['finish_time']);
            }
            $prize = $this->PrizeExperienceModel->getPrize($student_id);
            foreach ($prize as $k => $v) {
                $prize[$k]['period'] = date2ToFormate($v['period']);
            }
            $activity = $this->ActivityExperienceModel->getActivity($student_id);
            foreach ($activity as $k => $v) {
                $activity[$k]['period'] = date2ToFormate($v['period']);
                $activity[$k]['period_end'] = date2ToFormate($v['period_end']);
            }
            $cert = $this->StudentCertificateModel->getcert($student_id);
            $type = D('Certificate');
            foreach ($cert as $k => $v) {
                $cert[$k]['certificate'] = $type->getCertificateName($v['certificate_id']);
                $cert[$k]['status'] = getCertStatusText($v['status']);
                $cert[$k]['finish_time'] = date2ToFormate($v['finish_time']);
            }
            $school = $this->JobExperienceModel->jobExperienceList($student_id);
            $job = D('SchoolJob');
            foreach ($school as $k => $v) {
                $school[$k]['description'] = textareaFormat($v['description']);//
                $school[$k]['start_time'] = date2ToFormate($v['start_time']);
                $school[$k]['finish_time'] = date2ToFormate($v['finish_time']);
            }
            #新增关注度   --begin--
            $enterprise_id = session('account.enterprise_id');
            if (!empty($enterprise_id)) {
                $resumeViewModel = D('ResumeView');
                $resumeViewModel->viewAdd(array('enterprise_id' => $enterprise_id, 'resume_id' => $pkid,
                    'student_id' => $student_id));
            }
            #新增关注度    --end--
            $this->assign('introduceHtml', textareaFormat($this->StudentModel->getIntroduce($student_id)));
            $this->assign('exist', self::resumeModule2($student_id));
            $this->assign('expectHtml', self::expectListTpl($student_id));
            $this->assign('education', $education);
            $this->assign('practice', $practice);
            $this->assign('prize', $prize);
            $this->assign('activity', $activity);
            $this->assign('cert', $cert);
            $this->assign('school', $school);
            $this->assign('headpic', $this->StudentModel->studentAvatar($student_id));
            $this->assign('recommand', $recommand);
            $this->assign('pre', 'FALSE');
            return $this->fetch('resume_center');
        }
    }

    /*
     * @function 简历类型相关
     */
    public function resumeType(){
        $student_id = session('account.student_id');
        $result = $this->ResumeModel->where(array('student_id' => $student_id,'resume_type' => array('neq',2)))
            ->getField('resume_type,resume_name,pkid,true_path');
        return $result;
    }

    /*
     * @function 加载简历类型模版
     */
    public function resumeTypeHtml(){
        $default_type = array(
            1 => array('pkid' => 1,'name' => '中文简历','url' => '/Student/like'),
            3 => array('pkid' => 3,'name' => '中/英文简历','url' => '/EnStudent/enlike'),
            4 => array('pkid' => 4,'name' => '附件简历','url' => '/PageOffice/like'),
        );
        $data = $this->resumeType();
        //是否已经上传了简历附件
        if(isset($data[4])){
            $upload_file = 1;
        }else{
            $upload_file = 0;
        }

        $default = $this->ResumeModel->resumeDefault(); //默认投递
        $this->assign('default',$default_type[$default['resume_type']]);
        //做选择数组
        foreach($default_type as $key => $value){
            if(empty($data[$key])){
               unset($default_type[$key]);
            }else{
                if($key == $default['resume_type']){
                    unset($default_type[$key]);
                }
            }
        }

        //插件下载链接
        $download_url = 'http://'. C('APP_IP') . ':8080/JavaBridge/posetup.exe';

        $this->assign('default_type',$default_type);
        $this->assign('upload_file',$upload_file);
        $this->assign('pop_show',isset($data[1]) ? 'show' : 'hide'); //判断是否能够上传简历，先有中文简历后方可上传简历
        $this->assign('data',$data);
        $this->assign('download_url',$download_url);
        $this->assign('browser',checkBrowser());
        $this->assign('cookie_set',cookieUploadCheck());
        return $this->fetch('resume_upload');
    }
    
    /*更改第三方插件cookie状态*/
    public function cookieSet(){
        $status = I('get.status');
        $name = session('account.account_email');
        if($status){
            cookie($name,1);
        }else{
            cookie($name,0);
        }
    }

}
