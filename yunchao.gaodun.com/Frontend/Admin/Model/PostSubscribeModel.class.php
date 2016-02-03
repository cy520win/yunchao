<?php
// +----------------------------------------------------------------------
// | 学员订阅模型
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;

class PostSubscribeModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'post_subscribe';

    /*
    * @func 
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getSubscribeList($where=array(),$page=1,$number=10){
        $field = array('post_subscribe.*','student.name');
        $order='post_subscribe.create_time desc';
        $result = $this->join('inner join student on student.pkid=student_id')
                    ->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getSubscribeTotal($where=array()){
        if($where){
            $total = $this->join('inner join student on student.pkid=student_id')->where($where)->count();
            return intval($total);           
        }
    } 

    /*
    * @func 新增数据
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addScribe($data=array()){
        if(!empty($data)){
            $data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
            $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
            $data['status']  = 1;
            return $this->data($data)->add();
        }
    }

    /*
    * @func 获取某个订阅信息
    * @param $student_id
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getScribeInfo($where=array(),$field = array()){
        if($where){
            return $this->field($field)->where($where)->order('pkid desc')->find();
        }
    }

    /*
    * @func 获取某个订阅信息
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getScribeById($pkid='',$field = array()){
        if($pkid){
            return $this->field($field)->where(array('pkid'=>$pkid))->order('pkid desc')->find();
        }
    }

    /*
    * @func 更新某个订阅信息
    * @param $student_id
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updateScribe($data,$where){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $bool = $this->data($data)->where($where)->save();
        return $bool;
    }
}
