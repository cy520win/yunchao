<?php
// +----------------------------------------------------------------------
// | 简历模型 
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
 * @author 致远<george.zou@gaodun.cn>
 */

class ResumeModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'resume';

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }


    /*------------------------------------------------------------*/ 

    /*
    * @func 获取中文简历列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getResumeList($where=array(),$page=1,$number=10){
        $field = array('resume.pkid ','resume.student_id','student.name','student.graduate_school','student.detail_major','student.education','student.current_grade','student.mobile','student.contact_mobile','student.politics_status','student.major_type','student.graduate_year','student.birth_date','resume.create_time','student.contact_email','student.login_email');
        $order='resume.pkid desc';
        $where['resume.resume_type']=1;
        $result = $this->join('inner join student on student.pkid=resume.student_id')
                    ->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    //英文简历列表数据
    public function englishResumeList($where=array(),$page=1,$number=10){
        $field = array('resume.pkid ','resume.student_id','student.name_en as name','student.graduate_school_en as graduate_school','student.detail_major_en as detail_major','student.education','student.current_grade','student.mobile','student.politics_status','student.major_type','student.graduate_year','student.birth_date','resume.create_time','student.contact_mobile','student.contact_email');
        $order='resume.pkid desc';
        $result = $this->join('inner join student on student.pkid=resume.student_id')
                    ->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    //英文简历列表数据
    public function attachResumeList($where=array(),$page=1,$number=10){
        $field = array('resume.pkid ','resume.student_id','student.name','student.mobile','student.contact_mobile','resume.create_time','resume.modify_time','resume.resume_name','resume.default_status','resume.valid_status','student.contact_mobile','student.contact_email');
        $order='resume.pkid desc';
        $result = $this->join('inner join student on student.pkid=resume.student_id')
                    ->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 获取简历基本信息
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getResumeInfo($where=array()){
        $field = array('resume.pkid ','resume.student_id','student.name','student.graduate_school','student.detail_major','student.education','student.current_grade','student.mobile','student.politics_status','student.gender','student.major_type','student.graduate_year','student.birth_date');
        $result = $this->join('inner join student on student.pkid=resume.student_id')
                    ->field($field)->where($where)->select();
        return $result;
    }

    public function resumeInfoEng($where=array()){
        $field = array('resume.pkid ','resume.student_id','student.name_en as name','student.graduate_school_en as graduate_school','student.detail_major_en as detail_major','student.education','student.current_grade','student.mobile','student.politics_status','student.gender','student.major_type','student.graduate_year','student.birth_date','student.dear_hr_en as dear_hr');
        $result = $this->join('inner join student on student.pkid=resume.student_id')
                    ->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 查询中文简历总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getResumeTotal($where=array()){
        if($where){
            $where['resume.resume_type']=1;
            $total = $this->join('inner join student on student.pkid=resume.student_id')->where($where)->count();
            return intval($total);           
        }
    }

    //英文简历总数
    public function englishResumeTotal($where=array()){
        if($where){
            $total = $this->join('inner join student on student.pkid=resume.student_id')->where($where)->count('resume.pkid');
            return intval($total);           
        }
    }

    //附件简历总数
    public function attachResumeTotal($where=array()){
        if($where){
            $total = $this->join('inner join student on student.pkid=resume.student_id')->where($where)->count('resume.pkid');
            return intval($total);           
        }
    }

    /*
    * @func 保存新增的校园简历
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function ResumeAdd($data=array()){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;
        }
    }

    /*
    * @func 保存更新简历数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function ResumeUpdata($data=array(),$id=''){
        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 逻辑更改简历状态为删除
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function ResumeDelete($id){
        if($id){
            $bool = $this->data(array('valid_status'=>2,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getResume(){
        return $this->where(array('valid_status'=>1))->field(array('pkid','title','description'))->select();
    }
}
