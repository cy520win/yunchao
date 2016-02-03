<?php
// +----------------------------------------------------------------------
// | 校内职务模型 
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

class SchoolJobModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'school_job';

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }


    /*------------------------------------------------------------*/ 

    /*
    * @func 获取校园职务列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getSchoolJobList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 条件获取校园职务列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getSchoolJobAll($where=array(),$field=array()){
    	 $where['is_delete']=2;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 查询校园职务总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getSchoolJobTotal($where=array()){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }


    } 

    /*
    * @func 保存新增的校园职务
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function schoolJobAdd($data=array()){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;
        }
    }

    /*
    * @func 获取单个职务数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getSchoolJobinfo($id=false,$field=array()){
        if($id){
            $info = $this->field($field)->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    /*
    * @func 保存更新职务数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function schoolJobUpdata($data=array(),$id=''){
        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 逻辑更改职务状态为删除
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function schoolJobDelete($id){
        if($id){
            $bool = $this->data(array('is_delete'=>1,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 获取职务
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function schoolJobOpt(){
        return $this->where(array('is_delete'=>2))->field(array('pkid','title'))->select();
    }
}
