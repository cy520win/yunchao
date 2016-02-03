<?php
// +----------------------------------------------------------------------
// | 企业规模模型 
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

class IndustryModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'industry';

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }


    /*------------------------------------------------------------*/ 

    /*
    * @func 获取行业列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getIndustryList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
    	$where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

        /*
    * @func 获取行业列表数
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getIndustryAll($where=array(),$field=array()){
    	 $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    public function getFields(){
        return $this->where(array('is_delete'=>2))->getField('pkid,title');
    }

    public function getIndustryOption(){
        $result = $this->field(array('title','pkid','description'))->where(array('is_delete'=>2))->select();
        return $result;       
    }

    public function getIndustryTitle($id=''){
        if($id){
            return $this->getFieldByPkid($id,'title');
        }
    }

    /*
    * @func 查询行业总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getIndustryTotal($where=array()){
    	$where['is_delete'] = 2;
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }


    } 

    /*
    * @func 保存新增的校园行业
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function IndustryAdd($data=array()){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;
        }
    }

    /*
    * @func 获取单个行业数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getIndustryinfo($id=false,$field=array()){
        if($id){
            $info = $this->field($field)->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    /*
    * @func 保存更新行业数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function IndustryUpdata($data=array(),$id=''){
        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 逻辑更改行业状态为删除
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function IndustryDelete($id){
        if($id){
            $bool = $this->data(array('is_delete'=>1,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

        /*
    * @func 逻辑更改行业状态为删除
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getIndustrySelect(){
        return $this->where(array('is_delete'=>2))->field(array('pkid','title'))->select();
    }
}
