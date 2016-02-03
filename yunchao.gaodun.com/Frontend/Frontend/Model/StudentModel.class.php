<?php
// +----------------------------------------------------------------------
// | 学员账户模型 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-05-14 13:24:09  星期四
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
use Think\Crypt as Crypt;
class StudentModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'student';

    /*
    * @func 获取学员信息
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getStudentinfo($id=false,$field=array()){
        if($id){
            return $this->field($field)->where(array('pkid'=>$id))->find();
        }
    }

    public function getStudentAccount(){
        return $this->field(array('pkid','name','name_en','email_verify','nickname'))->where(array('account_id'=>session('account.account_id')))
        ->find();
    }

    /*
    * @func account_id保存更新学员
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function studentUpdata($data=array()){
        if(!empty($data)){
            $data['modify_time'] = date("Y-m-d H:i:s",time());
            actionLogAdd(7,session('account'));
            return $this->data($data)->where(array('account_id'=>session('account.account_id')))->save();
        }
    }

    /*
    * @func account_id更新学员的邮箱状态
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function upMailStatus($id=''){
        if(!empty($id)){
            return $this->data(array('email_verify'=>1,'modify_time'=>date('Y-m-d H:i:s',NOW_TIME)))->where(array('account_id'=>$id))->save();
        }
    }

    //更新手机验证状态
    public function upMobileStatus($phone,$id=''){
        $id = !empty($id) ? intval($id) : session('account.account_id');
        $data = array(
            'mobile_verify'=>1,
            'modify_time'=>date('Y-m-d H:i:s',NOW_TIME),
            'mobile' => $phone
        );
        $bool['student'] = $this->data($data)->where(array('account_id'=>$id))->save();//更新student
        $bool['account'] = M()->table('account')->data(array('modify_time'=>date('Y-m-d H:i:s',NOW_TIME),'login_mobile' => $phone))->where(array('pkid'=>$id))->save();//更新account
        return $bool;
    }

    //验证手机号唯一性
    public function checkMobileOnly($phone='',$account_id=''){
        if($phone){
            $account_id = $account_id ?$account_id : session('account.account_id');
            $where['mobile'] = $phone;
            $where['account_id'] = array('neq',$account_id);
            $number = $this->where($where)->count();
            return $number > 0 ? TRUE :FALSE;            
        }
    }

       /**----------------------------分割线---------------------------------------*/ 

    /*
    * @func 学生新注册添加基本信息
    * @param array:$data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    * 2015-05-14
    */
    public function addBaseStudent($data=array()){
        if(!empty($data)){
            $data['create_time'] = dateTime();
            $data['modify_time'] = $data['create_time'];
            $data['regist_from'] = D('Account')->cookieActivityNumber();
            $data['complete_rate'] = 11;
            $result = $this->data($data)->add();
            return $result;
        }
    }

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    public function getMailStatus($mail=""){
        if($mail){
            $result = $this->where(array('login_email'=>$mail))->field(array('email_verify'))->find();
            return $result;
        }
    }

    /*
    * @function 学生简历的基本信息
    * @param int:$account_id
    * @return array:$data
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    public function getBaseInfo($account_id=''){
        $field = array();
        $account_id = !empty($account_id) ? intval($account_id) : session('account.account_id');
        return $this->field($field)->where(array(array('account_id'=>$account_id)))->find();
    }

    public function getBaseEnInfo($student_id=''){
        $field = array();
        $student_id = !empty($student_id) ? intval($student_id) : session('account.student_id');
        return $this->field($field)->find($student_id);
    }

    //获取学员PKID
    public function getStudentId($aid){
        return $this->getFieldByAccount_id($aid,'pkid');
    }

    public function getAccountId($pkid){
        return $this->getFieldByPkid($pkid,'account_id');
    }

    public function getEducation($pkid){
        return $this->getFieldByPkid($pkid,'education');
    }



    //获取学生姓名，判断生成基本信息
    public function existBase($id=''){
        $student_id = !empty($id) ? $id : session('account.student_id');
        $school = $this->getFieldByPkid($student_id,'graduate_school');
        $name  = $this->getFieldByPkid($student_id,'name');
        return $school&&$name ? TRUE : FALSE;
    }

    //获取学生姓名，判断生成基本信息
    public function existEnBase($id=''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        $school_en = $this->getFieldByPkid($student_id,'graduate_school_en');
        $name_en  = $this->getFieldByPkid($student_id,'name_en');
        return $school_en&&$name_en?TRUE:FALSE;
    }

    //判断是否上传学生头像
    public function existAvatar($id=''){
        $student_id = !empty($id) ? $id : session('account.student_id');
        $name = $this->getFieldByPkid($student_id,'avatar');
        return $name?TRUE:FALSE;
    }

    //判断是否上传学生头像
    public function studentAvatar($id=""){
        $student_id = !empty($id) ? $id : session('account.student_id');        
        $name = $this->getFieldByPkid($student_id,'avatar');
        return $name?$name:FALSE;
    }

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function uploadAvatar($path){
        actionLogAdd(6,session('account'));
        return $this->data(array('avatar'=>$path))->where(array('pkid'=>session('account.student_id')))->save();
    }

    public function recommand($where){
        $result = $this->field(array('pkid','name','graduate_school','detail_major','avatar','education'))->limit(5)->where($where)->select();
       return $result;
    }

    /*-------------------------------------------*/ 
    //更新dear_hr
    public function update_introduce($data){
        return $this->data(array('dear_hr'=>$data,'modify_time'=>dateTime()))->where(array('pkid'=>AccountModel::studentID()))->save();
    }

    //获取自我介绍
    public function getIntroduce($id=''){
        $student_id = !empty($id) ? $id : session('account.student_id');
        $introduce = $this->getFieldByPkid($student_id,'dear_hr');
        return $introduce ? $introduce : false;  
    }

    //是否存在自我介绍 dear_hr
    public function introduceExist($id=''){
        $student_id = !empty($id) ? $id : session('account.student_id');
        $introduce = $this->getFieldByPkid($student_id,'dear_hr');
        $introduce = trim($introduce);
        return !empty($introduce) ? true : false;
    }

    public function introduceEnExist($id=''){
        $student_id = !empty($id) ? $id : session('account.student_id');
        $introduce = $this->getFieldByPkid($student_id,'dear_hr_en');
        $introduce = trim($introduce);
        return !empty($introduce) ? true : false;
    }

    public function deleteIntroduceModule(){
        return $this->data(array('dear_hr'=>'','modify_time'=>dateTime()))->where(array('pkid'=>AccountModel::studentID()))->save();
    }

    /*
    * @function 获取简历推荐库的学生基本信息
    * @param array():student_id
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function inviteStuent($data){
        $where['re.default_status'] = 1;
        $where['re.valid_status'] = 1;
        $where['re.student_id'] = array('in',$data);
        $field = array('student.pkid as student_id,re.pkid as resume_id,student.name,student.graduate_school,student.education','student.detail_major','student.major_type','re.resume_type','student.graduate_year','student.avatar');
        $result = $this->join('inner join resume as re on student.pkid = re.student_id')
                        ->field($field)->where($where)->order('re.student_id desc')->group('student.pkid')->select();
        return $result;
    }

    public function inviteStudentRecome($student_id){
        $where['re.default_status'] = 1;
        $where['re.valid_status'] = 1;
        $where['re.student_id'] = $student_id;
        $field = array('student.pkid as student_id,re.pkid as resume_id,student.name,student.graduate_school,student.education','student.detail_major','student.major_type','re.resume_type','student.graduate_year','student.avatar');
        $result = $this->join('inner join resume as re on student.pkid = re.student_id')
                        ->field($field)
                        ->where($where)
                        ->find();
        return $result;
    }

    /*
    * @function 获取学生的手机号、手机验证状态
    * @param int:$student_id //学生主键
    * @return array
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function getMobile($student_id=''){
        $pkid = $student_id ? $student_id : session('account.student_id');
        $result = $this->field(array('mobile','mobile_verify','contact_mobile'))->find($pkid);
        $result['status'] = !empty($result['mobile']) ? TRUE : FALSE;
        return $result;
    }

    /*
    * @function 学生验证手机号mobile，如果联系手机为空则更新contact_mobile
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function updateContactMobile($student_id=''){
        $student_id = $student_id ? $student_id : session('account.student_id');
        $sql = "update `student` set contact_mobile=ifnull(contact_mobile,mobile) where isnull(contact_mobile) and pkid={$student_id}";
        $this->execute($sql);
    }

    //通过account_id更新学生student信息
    public function studentSaveInfo($data,$account_id){
        $data['modify_time'] = dateTime();
        return $this->data($data)->where(array('account_id'=>$account_id))->save();
    }
}
