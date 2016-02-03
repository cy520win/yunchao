<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：简历表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-14 16:54:15  星期四
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class ResumeModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'resume';
    public $resumeZh = 1;   //中文简历
    public $resumeEn = 2;   //英文简历
    public $resumeAll = 3;  //中英文简历
    public $resumeOther = 4;//附件简历
    public $resumeShow = 1; //显示
    public $resumeHide = 2; //隐藏

    /*
    * @func 新增简历
    * @param array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function addResume(){
        $data['student_id'] = session('account.student_id');
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        $data['resume_type'] = $this->resumeZh;
        $data['valid_status'] = $this->resumeShow;
        $data['default_status'] = 1;
        $last_id = $this->data($data)->add();
        return $last_id;
    } 

    /*
    * @function 学生是否生成简历
    * @param 
    * @return bool
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    public function existResume(){
        $number = $this->where(array('student_id'=>session('account.student_id')))->count();
        return $number!=0 ? $number : false;
    }

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function getLastResume(){
        $data = $this->where(array('student_id'=>AccountModel::studentID()))->find();
        return $data;
    }

    /*
    * @function 通过简历主键获取学生student_id
    * @param 简历PKID
    * @return 学生PKID int::student_id
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function studentIdByKey($pkid){
        $result = $this->field('student_id')->find($pkid);
        return $result['student_id'];
    }

    /*
    * @function 通过简历id，获取学生名和职位名
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function studentPost($pkid){
        $field = array('st.name','resume.pkid as resume_id','st.pkid as student_id','resume.student_id as r_student_id');
        $where['resume.pkid'] = $pkid;
        $result = $this->join('inner join student as st on resume.student_id=st.pkid')->field($field)->where($where)->find();
        return $result;
    }

    /*
    * @function 判断是否存在中英文简历
    * @param $student_id 学生主键PKID
    * @param resume_type {1:中文;2:英文}
    * @return array()
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function resumeZhEn($student_id=NULL){
        $where['student_id'] = $student_id;
        $where['valid_status'] = $this->resumeShow;
        $where['_string'] = 'resume_type = 1';
        $resume['zh'] = $zh = $this->where($where)->count();
        $where['_string'] = 'resume_type = 2';
        $resume['en'] = $en = $this->where($where)->count();
        $resume['zh'] = $zh>0 ? TURE : FALSE; 
        $resume['en'] = $en>0 ? TURE : FALSE;
        return $resume;
    }

    /*
     * @function 验证简历是否存在
     * @param int $student_id
     */
    public function resumeInfo($resume_type){
        $student_id = session('account.student_id');
        $resume_type = $resume_type;
        $where = array(
            'student_id' => $student_id,
            'resume_type' => $resume_type,
            'valid_status' => $this->resumeShow
        );
        return $this->where($where)->find();
    }

    /*
     * @function 新增或修改简历
     * @param array $data string $type int $resume_type
     * @description 如若$resume_type为空值，则默认更新所有跟当前学生相关的简历信息
     */
    public function resumeSave($data,$type = 'add',$resume_type = ''){
        if($type == 'add'){
            $this->data($data)->add();
        }else{
            $where['student_id'] = session('account.student_id');
            if(!empty($resume_type)){
                $where['resume_type'] = $resume_type;
            }
            $this->data($data)->where($where)->save();
        }
    }

    /*
     * @function 返回默认选中简历类型
     */
    public function resumeDefault(){
        return  $this->field('pkid,resume_type,resume_name,file_path,true_path')
            ->where(array('default_status' => 1,'student_id' => session('account.student_id')))->find();
    }

    /*
     * @function 名称变更时候更新简历名称
     */
    public function resumeChange(){
        $student = session('account');
        if(!empty($student['account_name'])){
            $this->data(array('resume_name' => $student['account_name'] . '的简历'))
                ->where(array('student_id' => $student['student_id'],'resume_type' => $this->resumeZh))
                ->save();
        }
        if(!empty($student['account_name_en'])){
            $this->data(array('resume_name' => $student['account_name_en'] . '\'s resume'))
                ->where(array('student_id' => $student['student_id'],'resume_type' => $this->resumeEn))
                ->save();
        }
        if(!empty($student['account_name']) && !empty($student['account_name_en'])){
            $this->data(array('resume_name' => $student['account_name'].'的简历/'.
                $student['account_name_en'] . '\'s resume'))
                ->where(array('student_id' => $student['student_id'],'resume_type' => $this->resumeAll))
                ->save();
        }
    }
    
    /*
     * @function 默认简历类型修改
     */
    public function resumeDefaultChange($resume_type){
        if(empty($resume_type)){
            return false;
        }
        $this->data(array('default_status' => 2))
            ->where(array('student_id' => session('account.student_id')))
            ->save();
        return $this->data(array('default_status' => 1))
            ->where(array('resume_type' => $resume_type,'student_id' => session('account.student_id')))
            ->save();
    }

    /*
     * @function 获取学生所有简历
     * @date 2015-11-12
     */
    public function resumeTypeAll($student_id = ''){
        $student_id = !empty($student_id) ? $student_id : session('account.student_id');
        return $this->field('pkid,resume_type,resume_name,file_path,true_path')
            ->where(array('student_id' => $student_id,'resume_type' => array('neq',2)))->group('resume_type')->select();

    }
}
