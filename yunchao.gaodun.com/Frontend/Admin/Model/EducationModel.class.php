<?php
// +----------------------------------------------------------------------
// | 学员教育表模型
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class EducationModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'education';

    /*
    * @func 新增保存
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addEducation($data){
        $data['start_time'] = $data['start_time'].'-01';
        $data['finish_time'] = $data['finish_time'].'-01';
        $data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['language_type'] = 1;
        return $this->data($data)->add();
    }

    /*
    * @func 获取学生学历
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getStuEdu($student_id,$language=1){
        return self::studentEduEng($student_id,$language);
    }

    public function studentEduEng($student_id,$language=1){
        $language = isset($language) ? intval($language) : 1;
        $result = $this->field(array('pkid','start_time','finish_time','school_name','major_title','degree'))->where(array('is_delete'=>2,'student_id'=>$student_id,'language_type'=>$language))->limit(4)->order('pkid desc')->select();
        return $result;
    }
    /*
    * @func 逻辑删除学生学历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function deleteStuEdu($id){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['is_delete'] = 'Y';
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 保存单个学员学历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function updateStuEdu($data,$where){
        $data['start_time'] = $data['start_time'].'-01';
        $data['finish_time'] = $data['finish_time'].'-01';
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        return $this->data($data)->where($where)->save();
    }


}
