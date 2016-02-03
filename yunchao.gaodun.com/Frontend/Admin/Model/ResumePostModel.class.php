<?php
// +----------------------------------------------------------------------
// | 简历投递表模型 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
class ResumePostModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'resume_post';

    /*
    * @func 
    * @param 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getResumeList($where=array(),$page=1,$number=10,$order='resume_post.pkid desc'){
        $field = array('resume_post.pkid','resume_post.post_id','post.title','enterprise.full_name','enterprise.telephone', 'student.mobile','student.name','resume_post.resume_id','resume_post.status','resume_post.create_time','student.contact_mobile','student.contact_email');
        $result = $this->join('inner join student on student.pkid=resume_post.student_id')
                        ->join('inner join enterprise on enterprise.pkid=resume_post.enterprise_id')
                        ->join('inner join post on post.pkid=resume_post.post_id')
                        ->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 
    * @param  
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getResumeTotal($where=false){
            $total = $this->join('inner join student on student.pkid=resume_post.student_id')
                        ->join('inner join enterprise on enterprise.pkid=resume_post.enterprise_id')
                        ->join('inner join post on post.pkid=resume_post.post_id')->where($where)->count();
            return intval($total);           
    }

    /*
    * @func 获取队列列表数据
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getPostQueue($id){
        $data = $this->field(array('resume_post.pkid','student.login_email as mail','student.name as student','enterprise.full_name as enterprise','post.title as post'))
            ->join('student on student.pkid=resume_post.student_id')
            ->join('enterprise on enterprise.pkid=resume_post.enterprise_id')
            ->join('post on post.pkid=resume_post.post_id')
            ->where('resume_post.pkid='.$id)->select();
        return $data;
    }

    /*
     * 获取相关的投递数量
     * @param array $where
     * @return int
     */
    public function backTotal($where=array()){
        $result = $this->where($where)->count();
        return intval($result);
    }
}
