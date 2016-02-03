<?php
// +----------------------------------------------------------------------
// | 城市地区模型
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
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class RegionsModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'regions';

    /*
    * @func 获取一级省市、直辖市城市
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function parentCity(){
        $result = $this->field(array('region_name','region_id'))->where(array('parent_id'=>0,'region_type'=>1))->order('pkid asc')->select();
        return $result;
    }

    /*
    * @func 获取二级省市区、县
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function childCity($id){
        if(!empty($id)){
            $result = $this->field(array('region_name','region_id'))->where(array('parent_id'=>$id))->order('pkid asc')->select();
            return $result;            
        }
    }

    public function getParentRegion($region_id){

        $child = $this->where(array('region_id'=>$region_id))->find();
        return $child;
    }

    /*
     * @func 获取二级市，县列表
     * @return array
     * @author allen
     */
    public function getCityList()
    {
        $result = $this->where(array('region_type' => 2))->getField('region_id,region_name');
        return $result;
    }

    public function getCityName2($region_id)
    {
        $result = $this->where(array('region_id' => $region_id))->field(array('parent_id ','region_id,region_name'))->find();
        return $result;
    }

    public function regionIdToname($region_id){
        $city = $this->where(array('region_id'=>$region_id))->field(array('region_name'))->find();
        return $city;
    }

    public function getCityName($where){
        return $this->field(array('region_name'))->where(array('region_id'=>array('in',$where)))->select();
    }
}
