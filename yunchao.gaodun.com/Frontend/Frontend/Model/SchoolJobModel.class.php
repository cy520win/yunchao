<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：校内职务表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-13 15:34:43  星期三
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
/**
 * 用户模型
 * @author 致远<george.zou@gaodun.cn>
 */

class SchoolJobModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'school_job';

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
    public function getSchoolJobList2($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 条件获取校园职务列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getSchoolJobList($field=array()){
        $result = $this->field(array('pkid','title'))->where(array('is_delete'=>2))->select();
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
    * @func 获取单个职务数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getSchoolJobTitle($id=false,$field=array()){
        if($id){
            $info = $this->field(array('title'))->where(array('pkid'=>$id))->find();
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
            $bool = $this->data(array('valid_status'=>2,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
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
        return $this->where(array('valid_status'=>1))->field(array('pkid','title'))->select();
    }
}
