<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学生校园职务表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-13 16:01:33  星期三
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class JobExperienceModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'job_experience';
    protected $deleteType = 1;
    protected $activeType = 2;

    /*
    * @func 新增学员校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addJobExperience($data){
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        $data['student_id'] = D('Account')->studentID();
        return $this->data($data)->add();
    }

    /*
    * @func 获取学员校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function jobExperienceList($id=''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        return $this->field(array('pkid','start_time','finish_time','job_id','description','job_title'))->where(array('is_delete'=>$this->activeType,'student_id'=>$student_id,'language_type'=>1))->order("start_time desc,finish_time desc")->select();
    }

    /*
    * @func 获取学员校内职务
    * @param $pkid
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function jobExperienceInfo($pkid){
        return $this->field(array('pkid','start_time','finish_time','job_id','description','job_title'))->where(array('is_delete'=>$this->activeType))->find($pkid);
    }

    /*
    * @func 逻辑删除校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteJobExperience($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新保存校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updateJobExperience($data,$where){
        $data['modify_time'] = dateTime();
        return $this->where($where)->data($data)->save();
    }

    /*
    * @function 是否生成校内职务
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existCertificate($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }
        $number = $this->where(array('student_id'=>$student_id,'is_delete'=>2,'language_type'=>1))->count();
        return $number>0?true:false;
    }

    /*
    * @func 逻辑删除校内职务模块
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteJobExperienceModule($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        $where = array('student_id'=>D('Account')->studentID(),'language_type'=>1);
        return $this->where($where)->data($data)->save();
    }
}
