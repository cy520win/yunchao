<?php
// +----------------------------------------------------------------------
// | 城市地区模型
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

class JobExperienceModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = C('DB_PREFIX').'job_experience';

    /*
    * @func 新增保存学员校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addJobExperience($data){
        $data['start_time'] = $data['start_time'].'-01';
        $data['finish_time'] = $data['finish_time'].'-01';
        $data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['language_type'] =1 ;
        return $this->data($data)->add();
    }

    /*
    * @func 获取学员校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function stuJobExperience($sid){
        return self::resumeJobExperEng($sid,1);
    }

    public function resumeJobExperEng($sid,$language=1){
        $language = isset($language) ? intval($language) : 1;
        return $this->field(array('pkid','start_time','finish_time','job_id','description','job_title'))->where(array('is_delete'=>2,'student_id'=>$sid,'language_type'=>$language))->order('start_time desc')->limit(6)->select();  
    }

    /*
    * @func 逻辑删除校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteStuJobExperience($id){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['is_delete'] = 'Y';
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新保存校内职务
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updateStuJobExperience($data,$where){
        $data['start_time'] = $data['start_time'].'-01';
        $data['finish_time'] = $data['finish_time'].'-01';
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        return $this->where($where)->data($data)->save();
    }
}
