<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：简历控制器
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-08 15:42:11  星期五
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Controller;
class ResumeController extends CommonController {

    protected $PostSubscribeModel;
    protected $StudentModel;
    protected $EducationModel;
    protected $PracticeModel;
    protected $PrizeExperienceModel;
    protected $ActivityExperienceModel;
    protected $StudentCertificateModel;
    protected $JobExperienceModel;
    protected $EnStudentController;
    public $ResumeModel;
    protected $date_type = '0000-00-00';

	public function _initialize(){
		parent::_initialize();
        $this->StudentModel = D('Student');
        $this->PostSubscribeModel = D('PostSubscribe');
        $this->EducationModel = D('Education');
        $this->PracticeModel = D('Practice');
        $this->PrizeExperienceModel = D('PrizeExperience');
        $this->ActivityExperienceModel = D('ActivityExperience');
        $this->StudentCertificateModel = D('StudentCertificate');
        $this->JobExperienceModel = D('JobExperience');
        $this->EnStudentController = A('EnStudent');
        $this->ResumeModel = D('Resume');
	}

    /**
     * 加载首页视图
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $this->display();
    }

    /*
    * @function 生成简历完成百分比
    * @param number:$id
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    public function resumeRate(){
        if(IS_GET){
            $data = $this->getRequestData();
            $this->assign('number',$data['id']);
            $this->display('resume_rate');
        }
    }

    /*
     * 简历下载
     * @param int $type int $resume_id
     * @author allen
     */
    public function resumeDownload(){
        $ty = I('get.type');
        $resume_id = I('get.resume_id');
        $resume_id = enInt($resume_id);
        if(empty($resume_id)){
            $studentId = session('account.student_id');
            $resumeInfo = $this->ResumeModel->where(array('student_id' => $studentId))->find();
            $resume_id = $resumeInfo['pkid'];
        }
        // 2015-12-29 邀请下载
        if(isset($_GET['tag']) && $_GET['tag']=='invite'){
            $invite = true;
        }else{
            $invite = false;
        }
        $data = $this->resumeHtml($resume_id,$ty,'',$invite);
        $html = $data['html'];
        $name = $data['name'];


        //导入pdf生成的插件
        switch($ty){
            case 1:
                require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/mpdf/mpdf.php';
                $mpdf = new \mPDF('+aCJK','A4');
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
                $mpdf->SetDisplayMode('fullpage');
                $htmlFooter = '<div style="text-align: center;color:#bfbfbf;font-size:14px;">简历来自：高顿实习—专注大学生财经实习机会—shixi.gaodun.com</div>';
                $mpdf->SetHTMLFooter($htmlFooter);
                $mpdf->WriteHTML($html);
                $mpdf->Output($name . '的个人简历.pdf','D');
                break;
            case 2:
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                $name = $name . '的个人简历.doc';
                header("Content-Type: application/doc");
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = urlencode($name);
                $encoded_filename = str_replace("+", "%20", $encoded_filename);
                if (preg_match("/MSIE/", $ua)) {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                } else if (preg_match("/Firefox/", $ua)) {
                    header('Content-Disposition: attachment; filename*="utf8\'\'' . $name . '"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $name . '"');
                }
                echo $html;
                break;
            default:
                redirect('/');
                break;
        }
    }


    public function getBaseInfo($account_id=''){
        $base_info = $this->StudentModel->getBaseInfo($account_id);//简历基本信息
        if($base_info){
            $base_info['gender_text'] = getGerderText($base_info['gender']);
            $base_info['current_grade_text'] = getGradeText($base_info['graduate_year']?$base_info['graduate_year']:'');
            $base_info['education_text'] = getDegreeTextSTU($base_info['education']);
            $base_info['politics_status_text'] = getPoliticsText($base_info['politics_status']);
            $nothing = '';
            $base_info['major_type_text'] = getMajorTypeText($base_info['major_type'],$nothing);
            $base_info['detail_major'] = trim($base_info['detail_major']);
            return $base_info;
        }
    }

    public function expectListTpl($student_id=''){
        $data = $this->PostSubscribeModel->getLastExpect($student_id);
        $data['week_available'] = getWorkDayText($data['week_available']);
        $data['salary_range'] = getDaySalaryText($data['salary_range']);
        $data['city']['region_name'] = expectCityName($data['expect_city']);
        $data['category_text'] = D('PostCategory')->getCategoryText($data['expect_category']);
        if($data['period_start'] == $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 1;
        }elseif($data['period_start'] != $this->date_type && $data['period_finish'] == $this->date_type){
            $data['dateType'] = 2;
        }else{
            $data['dateType'] = 3;
        }
        return $data;
    }

    public function resumeModule2($student_id){
        /*----------2015-07-21 11:48:27  星期二----------*/
        $resume['introduce_bool'] = $this->StudentModel->introduceExist($student_id);
        /*----------2015-07-21 11:48:27  星期二----------*/
        $resume['avatar_bool'] = $this->StudentModel->existAvatar($student_id);
        $resume['base_bool'] = $this->StudentModel->existBase($student_id);
        $resume['expect_bool'] = $this->PostSubscribeModel->existExpect($student_id);//是否存在实习意向，判断简历生成的标准
        $resume['education_bool'] = $this->EducationModel->existEducation($student_id);
        $resume['practice_bool'] = $this->PracticeModel->existPractice($student_id);
        $resume['prize_bool'] = $this->PrizeExperienceModel->existPrize($student_id);
        $resume['activity_bool'] = $this->ActivityExperienceModel->existActivity($student_id);
        $resume['cert_bool'] = $this->StudentCertificateModel->existCertificate($student_id);
        $resume['school_bool'] = $this->JobExperienceModel->existCertificate($student_id);
        /*-------------2015-07-24 10:39:36  星期五--------------*/
        $resume['school_acticity_bool'] = ($resume['school_bool'] || $resume['activity_bool']) ? true : false;
        $resume['cert_prize_bool'] = ($resume['cert_bool'] || $resume['prize_bool']) ? true : false;
        return $resume;
    }

    public function resumeHtml($resume_id,$ty,$status = '',$invite=false){
        /*
         * 查询相关数据
         */
        if(is_numeric($resume_id)){
            $student_id = $this->ResumeModel->studentIdByKey($resume_id);
            if($student_id){
                $account_id = $this->StudentModel->getAccountId($student_id);
                $baseInfo = self::getBaseInfo($account_id);
                if(empty($baseInfo['mobile_type'])){
                    $baseInfo['mobile_type'] = 1;
                }
                $expect = self::expectListTpl($student_id);
                $education = $this->EducationModel->studentEduList($student_id);
                foreach($education as $k=>$v){
                    $education[$k]['degree'] = getDegreeTextSTU($v['degree']==0?5:intval($v['degree']));
                    $education[$k]['start_time'] = date2ToFormate($v['start_time']);
                    $education[$k]['finish_time'] = date2ToFormate($v['finish_time']);
                }
                $practice = $this->PracticeModel->getPractice($student_id);
                foreach($practice as $k=>$v){
                    $practice[$k]['content'] = textareaFormat($v['content']);
                    $practice[$k]['start_time'] = date2ToFormate($v['start_time']);
                    $practice[$k]['finish_time'] = date2ToFormate($v['finish_time']);
                }
                $prize = $this->PrizeExperienceModel->getPrize($student_id);
                foreach($prize as $k=>$v){
                    $prize[$k]['period'] = date2ToFormate($v['period']);
                }
                $activity = $this->ActivityExperienceModel->getActivity($student_id);
                foreach($activity as $k=>$v){
                    $activity[$k]['period'] = date2ToFormate($v['period']);
                }
                $cert = $this->StudentCertificateModel->getcert($student_id);
                $type = D('Certificate');
                foreach($cert as $k=>$v){
                    $cert[$k]['certificate'] = $type->getCertificateName($v['certificate_id']);
                    $cert[$k]['status'] = getCertStatusText($v['status']);
                    $cert[$k]['finish_time'] = date2ToFormate($v['finish_time']);
                }
                $school = $this->JobExperienceModel->jobExperienceList($student_id);
                $job = D('SchoolJob');
                foreach($school as $k=>$v){
                    $school[$k]['job'] = $job->getSchoolJobTitle($v['job_id']);
                    $school[$k]['description'] = textareaFormat($v['description']);
                    $school[$k]['start_time'] = date2ToFormate($v['start_time']);
                    $school[$k]['finish_time'] = date2ToFormate($v['finish_time']);
                }

                //判断图片是否存在
                $headpic = $this->StudentModel->studentAvatar($student_id);
                $headpic = !empty($headpic) ? $headpic : C('APP_URL') . '/Public/static/images/new_nophoto_student.png';
                
                list($width,$height) = getimagesize($headpic);
                if($width > 114){
                    $width = $height = 114;
                }
		        if($ty == 1){

                    $headpic = str_replace(C('APP_URL'),'',$headpic);
                }
                $this->assign('width',$width);
                $this->assign('height',$height);
                $this->assign('exist',self::resumeModule2($student_id));
                $this->assign('baseInfo',$baseInfo);
                $this->assign('education',$education);
                $this->assign('practice',$practice);
                $this->assign('prize',$prize);
                $this->assign('activity',$activity);
                $this->assign('cert',$cert);
                $this->assign('school',$school);
                $this->assign('base',$expect);
                $this->assign('headpic',$headpic);
                $this->assign('introduce',$this->StudentModel->getIntroduce($student_id));
                $this->assign('pre','false');
                $this->assign('appUrl',C('APP_URL'));
                if($invite){
                    $this->assign('invite',TRUE);
                }
                if(!empty($status)){
                    if($status == 1){
                        $this->display('/MobileStudent/resume');
                        exit;
                    }
                }
                if($ty == 1){
                    $html = $this->fetch('resume_down');
                }else{
                    $html = $this->fetch('resume_down_word');
                }
                $name = $baseInfo['name'];
            }
            return array('html' => $html,'name' => $name);
        }
    }

    /*
     * @function 英文简历下载模版
     */
    public function enResumeHtml($resume_id,$ty,$status = ''){
        if(is_numeric($resume_id)){
            $student_id = $this->ResumeModel->studentIdByKey($resume_id);
            $baseInfo = $this->EnStudentController->getBaseInfo($student_id);
            $headpic = $this->StudentModel->studentAvatar($student_id);
            $headpic = !empty($headpic) ? $headpic : C('APP_URL') . '/Public/static/images/new_nophoto_student.png';
            list($width,$height) = getimagesize($headpic);
            if($width > 114){
                $width = $height = 114;
            }
            if($ty == 1){
                $headpic = str_replace(C('APP_URL'),'',$headpic);
            }
            $this->assign('width',$width);
            $this->assign('height',$height);
            $this->assign('exist',$this->EnStudentController->resumeModule($student_id));   //判断各个模块是否存在
            $this->assign('baseInfo',$baseInfo); //学生基础信息
            $this->assign('expect',$this->EnStudentController->returnPostSubscribeData($student_id)); //实习意向
            $this->assign('headpic',$headpic);   //头像
            $this->assign('education',$this->EnStudentController->returnShowData('Education',$student_id));//教育背景列表
            $this->assign('practice',$this->EnStudentController->returnShowData('Practice',$student_id));//实习经历列表
            $this->assign('prize',$this->EnStudentController->returnShowData('PrizeExperience',$student_id));//获奖荣誉列表
            $this->assign('activity',$this->EnStudentController->returnShowData('ActivityExperience',$student_id));//课外活动

            $cert_data = $this->EnStudentController->returnShowData('StudentCertificate',$student_id);
            foreach($cert_data as $key => $value){
                if(empty($value['certificate_name'])){
                    $cert_data[$key]['certificate'] =
                        $this->EnStudentController->certificate_list[$value['certificate_id']]['full_name_en'];
                }else{
                    $cert_data[$key]['certificate'] = $value['certificate_name'];
                }
            }
            $this->assign('cert',$cert_data);//证书
            $this->assign('school',$this->EnStudentController->returnShowData('JobExperience',$student_id));//校内职务
            $this->assign('appUrl',C('APP_URL'));
            if(!empty($status)){
                if($status == 1){
                    $this->display('/MobileEnStudent/resume');
                    exit;
                }
            }
            if($ty == 1){
                $html = $this->fetch('en_resume_down');
            }else{
                $html = $this->fetch('en_resume_down_word');
            }
            $name = $baseInfo['name_en'];
            return array('html' => $html,'name' => $name);
        }
    }

    //英文简历下载相关
    public function enResumeDownload(){
        $ty = I('get.type');
        $resume_id = I('get.resume_id');
        $resume_id = is_numeric($resume_id) ? $resume_id : enInt($resume_id);
        if(empty($resume_id)){
            $studentId = session('account.student_id');
            $resumeInfo = $this->ResumeModel->where(array('student_id' => $studentId))->find();
            $resume_id = $resumeInfo['pkid'];
        }

        $data = $this->enResumeHtml($resume_id,$ty);
        $html = $data['html'];
        $name = $data['name'];


        //导入pdf生成的插件
        switch($ty){
            case 1:
                require_once ROOT_PATH . '/ThinkPHP/Library/Vendor/mpdf/mpdf.php';
                $mpdf = new \mPDF('+aCJK','A4');
                $mpdf->autoScriptToLang = true;
                $mpdf->autoLangToFont = true;
                $mpdf->SetDisplayMode('fullpage');
                $htmlFooter = '<div style="text-align: center;color:#bfbfbf;font-size:14px;">简历来自：高顿实习—专注大学生财经实习机会—shixi.gaodun.com</div>';
                $mpdf->SetHTMLFooter($htmlFooter);
                $mpdf->WriteHTML($html);
                $mpdf->Output($name . '\'s Resume.pdf','D');
                break;
            case 2:
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                $name = $name . '\'s Resume.doc';
                header("Content-Type: application/doc");
                $ua = $_SERVER["HTTP_USER_AGENT"];
                $encoded_filename = urlencode($name);
                $encoded_filename = str_replace("+", "%20", $encoded_filename);
                if (preg_match("/MSIE/", $ua)) {
                    header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
                } else if (preg_match("/Firefox/", $ua)) {
                    header('Content-Disposition: attachment; filename*="utf8\'\'' . $name . '"');
                } else {
                    header('Content-Disposition: attachment; filename="' . $name . '"');
                }
                echo $html;
                break;
            default:
                redirect('/');
                break;
        }
    }


    /*
     * @function 判断附件简历是否存在，若存在，则需执行删除后再进行添加
     * @param array $data
     */
    public function resumeExist($data){
        $resume_type = 4;
        $type = 'add';
        $result = $this->ResumeModel->resumeInfo($resume_type);
        if(!empty($result)){
            unlink($result['file_path']);
            $type = 'edit';
        }
        $data['resume_type'] = $resume_type;
        $data['student_id'] = session('account.student_id');
        $data['default_status'] = !empty($result['default_status']) ? $result['default_status'] : 2;  //非默认投递状态
        $data['valid_status'] = 1; //默认显示状态
        $data['create_time'] = dateTime();
        $this->ResumeModel->resumeSave($data,$type,$resume_type);
    }

    /*
     * @function 默认投递更改
     */
    public function resumeDefaultChange(){
        $resume_type = I('post.resume_type');
        if(!is_numeric($resume_type)){
            $resume_type = enInt($resume_type);
        }
        $result = $this->ResumeModel->resumeDefaultChange($resume_type);
        if($result !== false){
            exit(json_encode(array('status' => 'success')));
        }else{
            exit(json_encode(array('status' => 'fail')));
        }
    }

    /*
     * @function 检查浏览器类型
     */
    public function checkBrowser(){
        $check = cookieUploadCheck();
        $url_view = I('post.url');

        if(!$check){
            if(!empty($url_view)){
                $this->assign('url_view',$url_view);
            }
            $download_url = 'http://'. C('APP_IP') . ':8080/JavaBridge/posetup.exe';
            $this->assign('download_url',$download_url);
            $html = $this->fetch('/Student/resume_other_box');
            exit(json_encode(array('status' => 'success','html' => $html)));
        }else{
            exit(json_encode(array('status' => 'fail')));
        }
    }

}