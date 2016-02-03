<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/25
 * Time: 11:37
 */
namespace Frontend\Model;
use Think\Model\RelationModel;
class ResumePostModel extends RelationModel{
    protected $pk = 'pkid';
    protected $trueTableName = 'resume_post';
    protected $_link = array(
        'Post'          => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Post',
            'foreign_key'   => 'post_id',
            'as_fields'     => 'title:post_title,pkid:post_id,major_wish:major_id'
        ),
        'Student'       => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Student',
            'foreign_key'   => 'student_id',
            'as_fields'     => 'name:student_name'
        ),
        'Enterprise'    => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Enterprise',
            'foreign_key'   => 'enterprise_id',
            'as_fields'     => 'full_name:enterprise_name'
        ),
        'Resume'    => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Resume',
            'foreign_key'   => 'resume_id',
            'as_fields'     => 'resume_type'
        ),
    );

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
    * @func 获取学生的投递列表
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPostDelivery($where,$page,$number,$order){
        $where['resume_post.student_id']=D('Account')->studentID();
        $data = $this->field(array('resume_post.pkid','resume_post.send_type','resume_post.hr_remark','resume_post.status','resume_post.create_time','enterprise.full_name as enterprise','post.title as position','post.city_id','post.pkid as post_id','resume_post.enterprise_id','resume_post.read_time','deal_time'))
                ->join('enterprise on enterprise.pkid=resume_post.enterprise_id')
                ->join('post on post.pkid=resume_post.post_id')
                ->where($where)->page($page,$number)->order($order)->select();
        return $data;
    }

    /*
    * @function 获取学生的投递总数
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function getPostDeliveryTotal($where){
        $where['resume_post.student_id']=D('Account')->studentID();
        $sum = $this->join('enterprise on enterprise.pkid=resume_post.enterprise_id')
                ->join('post on post.pkid=resume_post.post_id')->where($where)->count();
        return $sum;
    }

    /*
     * 简历投递列表
     * @return array
     */
    public function getResumePostList($where = array(),$page = 1,$page_number = 10,$order = ''){
        $order = $order ? $order : 'create_time desc';
        $result = $this->where($where)->relation(true)->page($page,$page_number)->order($order)->select();
        return $result;
    }

    /*
     * 简历投递数量
     * @return int
     */
    public function getResumePostTotal($where = array()){
        $result = $this->table('resume_post')->where($where)->count();
        return intval($result);
    }

    public function getResumePostCount($where = array()){
        $result = $this->join('inner join enterprise on enterprise.pkid=resume_post.enterprise_id')
                ->join('inner join post on post.pkid=resume_post.post_id')
                ->join('inner join resume on resume_post.resume_id=resume.pkid')
                ->join('inner join student on student.pkid=resume_post.student_id')
                ->join('inner join post_subscribe on student.pkid=post_subscribe.student_id')
                ->where($where)->count();
        return intval($result);     
    }

    public function getResumePostData($where = array(),$page = 1,$page_number = 10,$order = ''){
        $order = $order ? $order : 'resume_post.create_time desc';
        $field = array('resume_post.pkid','resume_post.create_time','post.title as post_title','post.pkid as post_id','student.name as student_name','student.pkid as student_id','resume_post.resume_id','student.education','student.detail_major','post.major_wish','student.major_type','resume_post.status','resume.resume_type','post_subscribe.period_start','post_subscribe.period_finish','student.graduate_year','student.living_city','student.avatar','resume_post.deal_time');
        $result = $this->join('inner join enterprise on enterprise.pkid=resume_post.enterprise_id')
                ->join('inner join post on post.pkid=resume_post.post_id')
                ->join('inner join resume on resume_post.resume_id=resume.pkid')
                ->join('inner join student on student.pkid=resume_post.student_id')
                ->join('inner join post_subscribe on student.pkid=post_subscribe.student_id')
                ->where($where)->field($field)->page($page,$page_number)->order($order)->select();
        return $result;
    }
    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function studentIdByKey($pkid){
        $result = $this->field('student_id')->find($pkid);
        return $result['student_id'];
    }

    /*
    * @function 简历浏览次数
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function resumeViewData($pkid){
        return $this->field('student_id,resume_id,enterprise_id')->find($pkid);
    }

    // --------------2015-09-15 13:50:56  星期二
    /*
    * @function 获取投递该职位的学生ID
    * @param 
    * @return array()
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postStudent($pkid){
        $id = session('account.enterprise_id');
        $where = array('enterprise_id' =>$id,'post_id'=>$pkid);
        $list = array();
        if(!empty($id)){
            $list = $this->where($where)->field(array('DISTINCT(student_id)'))->select();
        }
        return $list;
    }

    /*
     * @function 获取转发邮件相关信息
     * @param $id 投递记录id
     */
    public function resumeForwardInfo($id){
        if(empty($id)){
            return false;
        }
        $field = array('enterprise.full_name as enterprise_name','student.name as student_name','major_type.title as
        major_type','student.graduate_school as school_name','student.detail_major','student.education','student
        .graduate_year','student.contact_mobile','post.title as post_name','student.gender','student.mobile','resume.resume_name');
        return $this->field($field)
            ->join('inner join enterprise ON enterprise.pkid = resume_post.enterprise_id')
            ->join('inner join student ON student.pkid = resume_post.student_id')
            ->join('inner join major_type ON student.major_type = major_type.pkid')
            ->join('inner join post ON post.pkid = resume_post.post_id')
            ->join('inner join resume ON resume.pkid = resume_post.resume_id')
            ->where(array('resume_post.pkid' => $id))
            ->find();
    }

    /*
    * @function 更新职位投递为已读状态
    * @param int:$pkid 投递主键PKID
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function readResume($pkid){
        $data['modify_time'] = dateTime();
        $data['read_time'] = dateTime();
        $data['status'] = 2;
        $where['enterprise_id'] = session('account.enterprise_id');
        $where['pkid'] = $pkid;
        $where['status'] = array('ELT',1);
        $this->where($where)->data($data)->save();
    }
}
