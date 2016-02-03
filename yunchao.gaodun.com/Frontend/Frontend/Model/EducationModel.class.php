<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：
// +----------------------------------------------------------------------
// | 创建时间 ：
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class EducationModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'education';

    /*
    * @func 新增保存
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addEducation($data){
        $data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['student_id'] = D('Account')->studentID();
        return $this->data($data)->add();
    }

    /*
    * @func $sdutent_id 获取学生学历
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function studentEduList($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        $data = $this->field(array('pkid','start_time','finish_time','school_name','major_title','degree'))->where(array('is_delete'=>2,'student_id'=>$student_id,'language_type'=>1))->order('start_time desc')->select();
        return $data;
    }

    /*
    * @func $pkid 获取学生学历
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function studentEduInfo($pkid){
        return $this->field(array('pkid','start_time','finish_time','school_name','major_title','degree'))->where(array('is_delete'=>2,'pkid'=>$pkid))->find();
    }

    /*
    * @func 逻辑删除学生学历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function deleteEducation($id){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['is_delete'] = 1;
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 保存单个学员学历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function updateStuentEdu($data,$where){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        return $this->data($data)->where($where)->save();
    }

    /*
    * @function 是否生成教育背景
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existEducation($id=''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }
        $number = $this->where(array('student_id'=>$student_id,'is_delete'=>2,'language_type'=>1))->count();
        return $number!=0?true:false;
    }

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function deleteEducationModule($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = 1;
        $where = array('student_id'=>D('Account')->studentID(),'language_type'=>1);
        return $this->where($where)->data($data)->save();
    }

}
