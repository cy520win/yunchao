<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/5
 * Time: 10:24
 */

namespace Admin\Model;
use Think\Model\RelationModel;

class InterviewInvitationModel extends RelationModel{
    protected $pk = 'pkid';
    //protected $trueTableName = 'interview_invitation';

    //创建关联查询获取企业信息
    protected $_link = array(
        'Enterprise' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Enterprise',
            'foreign_key'   => 'enterprise_id',
            'mapping_fields'=> 'full_name,telephone',
            'as_fields'     => 'full_name,telephone'
        ),
        'Student' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Student',
            'foreign_key'   => 'student_id',
            'mapping_fields'=> 'name,mobile',
            'as_fields'     => 'name,mobile'
        ),
        'Post' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Post',
            'foreign_key'   => 'post_id',
            'mapping_fields'=> 'title',
            'as_fields'     => 'title'
        )
    );

    /*
     * @func 获取订阅器列表
     * @param array $where, array $field, string $order, int $page, int $number
     * @return array
     * author allen
     */
    public function getInterviewInvitationList($where = array(),$field = array(),$order = 'create_time desc',$page = 1,
                                           $number = 10)
    {
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->relation(true)->select();
        return $result;
    }

    /*
     * @func 获取订阅器列表数量
     * @param array $where
     * @return int
     * author allen
     */
    public function getInterviewInvitationTotal($where = false)
    {
        if($where)
        {
            $total = $this->where($where)->count();
            return intval($total);
        }
    }


    /*
    * @func 更新邀请信息
    * @param array
    * @return int
    * @author allen
    */
    public function interviewInvitationUpdate($data=array())
    {
        if(!empty($data))
        {
            $bool = $this->data($data)->save();
            return $bool;
        }
    }

    /*
    * @func 删除面试信息
    * @param array
    * @return int
    * @author allen
    */
    public function interviewInvitationDelete($id)
    {
        if(!empty($id))
        {
            $bool = $this->where(array('pkid' => $id))->data(array('is_delete' => 'Y'))->save();
            return $bool;
        }
    }

}