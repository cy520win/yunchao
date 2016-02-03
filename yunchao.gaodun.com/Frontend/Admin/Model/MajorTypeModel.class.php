<?php
namespace Admin\Model;
use Think\Model;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 * 专业分类模型
 * @author 致远<george.zou@gaodun.cn>
 */

class MajorTypeModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'major_type';

    /*
     * 获取专业名称
     * @return array
     * author allen
     */
    public function getMajorTitle() {
        $result = $this->where(array('is_delete' =>2))->getField('pkid,title,title_en');
        return $result;
    }

    //中文
    public function getMajorTitleAdd(){
        $result = $this->where(array('is_delete' =>2))->getField('pkid,title');
        return $result;       
    }

    /*------------------------------------------------------------*/ 

    /*
    * @func 获取专业分类列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getMajorTypeList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 条件获取专业分类列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 

    public function getMajorTypeData(){
        $result = $this->field(array('pkid','title'))->where(array('is_delete'=>2))->select(); //,'title_en'
        return $result;
    }

    public function getMajorType($where=array(),$field=array()){
        $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 获取专业分类KEY-VALUE数组
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeSelect(){
        $result = $this->field('pkid,title')->where(array('is_delete'=>2))->select();
        $return = array();
        foreach($result as $key=>$val){
            $return[$val['pkid']] = $val['title'];
        }
        return $return;
    }

    /*
    * @func 查询专业分类总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeTotal($where=array()){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }
    } 

    /*
    * @func 保存新增的专业
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function majorTypeAdd($data=array()){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;
        }
    }

    /*
    * @func 获取单个专业数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeById($id=false,$field=array()){
        if($id){
            return $this->field($field)->where(array('pkid'=>$id))->find();
        }
    }

    /*
    * @func 保存更新职务数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function majorTypeUpdate($data=array(),$id=''){
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
    public function majorTypeDelete($id){
        if($id){
            $bool = $this->data(array('is_delete'=>1,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }
}
