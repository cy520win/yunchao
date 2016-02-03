<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/5/6
 * Time: 10:28
 */

namespace Frontend\Model;


use Think\Model\RelationModel;

class PostSubscribeModel extends RelationModel{

    protected $pk = 'pkid';
    protected $trueTableName = 'post_subscribe';
    protected $patchValidate = true;
    public $expectType = 1;//实习意向类型
    public $acticeType = 1;//有效
    public $closeType = 2;//无效

    protected $_validate = array(
        array('pkid','require','主键缺失',1,'regex',2),
        array('expect_city','require','城市必须填写'),
        array('email','require','邮箱必须填写'),
        array('email','email','邮箱格式不正确')
    );

    protected $_link = array(
        'Category' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'PostCategory',
            'foreign_key'   => 'expect_category',
            'mapping_name'  => 'category',
            'as_fields'     => 'title:category_title'
        ),
        'Industry' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Industry',
            'foreign_key'   => 'industry_id',
            'mapping_name'  => 'industry',
            'as_fields'     => 'title:industry_title'
        )
    );

    /*
     * 查询列表内容
     * @return array
     */
    public function getSubscribeList()
    {
        //todo
    }

    /*
     * 获取视图数据
     * @param int $student_id
     * @return array
     * @author allen
     */
    public function getSubscribeView($student_id = '')
    {
        if(!empty($student_id))
        {
            $result = $this->field('post_subscribe.*,regions.region_name')->where(array('student_id' => $student_id))
                ->join('regions ON post_subscribe.expect_city=regions.region_id')->relation(true)->find();
        }
        return $result;
    }

    /*
     * 查询单条数据
     * @param array $where
     * @return array
     */
    public function getSubscribeInfo($where = array())
    {
        $result = $this->where($where)->order('pkid desc')->find();
        return $result;
    }

    /*
     * 新增数据
     * @param array $data
     * @return bool
     */
    public function subscribeAdd($data = array())
    {
        $result = $this->data($data)->filter('strip_tags')->add();
        return $result;
    }

    /*
     * 更新
     * @param array $data
     * @return bool
     */
    public function subscribeUpdate($data = array())
    {
        $result = $this->data($data)->filter('strip_tags')->save();
        return $result;
    }

    /*
    * @function 添加实习意向
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 2015-05-14
    */
    public function addPostExpect($data){
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        $data['student_id'] = D('Account')->studentID();
        $data['info_type'] = $this->expectType;
        $data['status'] = 1;
        $this->startTrans();
        $expect_id = $this->data($data)->add();
        if($expect_id){
            $resume_id = D('Resume')->addResume();//生成简历记录
            if($resume_id){
                $this->commit();
                return $expect_id;
            }else{
                $this->rollback();
                return false;
            }
        }else{
            return null;
        }
    }

    /*
    * @function 获取实习意向
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function getLastExpect($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        $data = $this->where(array('student_id'=>$student_id,'info_type'=>$this->expectType))->order('pkid desc')->find();
        return $data;
    }

    /*
    * @function 更新实习意向
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function updatePostExpect($data,$where){
        $data['modify_time'] = dateTime();
        $result = $this->data($data)->where($where)->save();
        return $result;
    }

    /*
    * @function 判读是否存在实习意向
    * @param 
    * @return int
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existExpect($id=''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }

        $number = $this->where(array('student_id'=>$student_id,'info_type'=>$this->expectType,'status'=>1))->count();
        return $number>0?true:false;
    }

    /*
    * @function 关闭、打开订阅器
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function updateSubStatus($type=""){
        if($type){
            if($type==1){ //打开
                $data['status']=1;
            }   
            if($type==2){ //关闭
                $data['status']=2;
            }
            $data['modify_time'] = dateTime();
            return $this->data($data)->where(array('student_id'=>session('account.student_id'),'info_type'=>2))->save();            
        }
    }

    /*
    * @function 获取符合推荐条件的学生id
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function getRecommand($where){
        $where['post_subscribe.info_type'] = 1;
        $total = $this->getRecommandCount($where);
        $num = 5;
        if($total>$num){
            $return['page'] = true;
        }
        $page_total = ceil($total/$num);
        $page = mt_rand(1,$page_total);
        $return['data'] = M('post_subscribe')->join('left join `student` on student.pkid=post_subscribe.student_id')
                        ->join('left join `resume` on resume.student_id=post_subscribe.student_id')
                        ->field('student.name,student.pkid as student_id,post_subscribe.pkid,student.graduate_school,student.detail_major,student.education,student.avatar,resume.pkid as resume_id')
                        ->page($page,$num)
                        ->group('post_subscribe.student_id')
                        ->where($where)->select();
        if(is_array($return['data'])){
            foreach ($return['data'] as $k => $v) {
                $return['data'][$k]['education'] = $v['education']?getDegreeText($v['education']):'';
            }
            return $return;        
        }else{
            return '';
        }
    }

    public function getRecommandCount($where){
        $data = M('post_subscribe')->join('left join `student` on student.pkid=post_subscribe.student_id')
                        ->join('left join `resume` on resume.student_id=post_subscribe.student_id')
                        ->field('student.pkid')
                        ->group('post_subscribe.student_id')
                        ->where($where)->select();
        return count($data);
    }

    // --------------------2015-09-15 13:33:27  星期二
    /*
    * @function 通过职位信息匹配实习意向
    * @param 
    * @return array() 学生student_id
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postSubStu($where,$page=1,$limit=10){
        $where['info_type'] = 1;
        $result = $this->field(array('DISTINCT(student_id)'))->page($page,10)->where($where)->order('student_id desc')->select();
        return $result;
    }

    //获取符合实习意向条件的学生主键pkid
    public function postSubcribeStudent($where,$start=0,$limit=10){
        $where['info_type'] = 1;
        $result = $this->field(array('DISTINCT(student_id)'))->limit($start,$limit)->where($where)->select();
        return $result;        
    }

    //
    public function postSubStuTotal($where){
        $where['info_type'] = 1;
        $result = $this->field(array('DISTINCT(student_id)'))->where($where)->count();
        return $result;
    }
}
