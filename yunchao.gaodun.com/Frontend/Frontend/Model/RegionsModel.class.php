<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/6
 * Time: 10:58
 */

namespace Frontend\Model;


use Think\Model;

class RegionsModel extends Model{


    protected $pk = 'pkid';
    protected $trueTableName = 'regions';

    /*
    * @func 获取一级省市、直辖市城市
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function parentCity(){
        $result = $this->field(array('region_name','region_id','region_name_en'))
            ->where(array('parent_id'=>0, 'region_type'=>1, 'region_id' => array('lt',700000)))->order('pkid asc')
            ->select();
        $result[] = array('region_name' => '其他','region_name_en' => 'Other','region_id' => 700000);
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
            $result = $this->field(array('region_name','region_id','region_name_en'))->where(array('parent_id'=>$id))
            ->order('pkid asc')->select();
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

    /*
    * @function 根据城市文本返回城市region_id
    * @param string:$region_name
    * @return $region_id
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 
    * @最后更新时间 
    */
    public function reginonNameToid($name,$type = 'CH'){
        $where = array(
            'CH' => array('region_name' => array('like','%'.$name.'%')),
            'EN' => array('region_name_en' => array('like','%'.$name.'%')),
        );
        $result = $this->where($where[$type])->field(array('region_id'))->find();
        return $result;
    }

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function regionIdToname($region_id,$type='CH'){
        $field = array(
            'CH' => 'region_name',
            'EN' => 'region_name_en'
        );
        $city = $this->where(array('region_id'=>$region_id))->field($field[$type])->find();
        return $city[$field[$type]];
    }
}