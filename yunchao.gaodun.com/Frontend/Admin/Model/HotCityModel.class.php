<?php
// +----------------------------------------------------------------------
// | 热门城市模型 
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
 * 企业规模模型
 * @author 致远<george.zou@gaodun.cn>
 */

class HotCityModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'hot_city';
    protected $patchValidate = true;

    protected $_validate = array(
        array('region_id',0,'请选择相应的城市','','notequal',1),
        array('region_id','','城市不可重复',0,'unique',1),
        array('order_num',array(1,100),'数值错误！',2,'between'),
        array('order_num','require','排序号必须填写！'),
    );

    /*
    * @func 获取热门城市列表
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getHotcityList($where=array(),$field=array(),$page = 1,$number = 10){
        $result = $this->where($where)->field($field)->order('order_num desc,create_time desc')->page($page,$number)
            ->select();
        return $result;
    }

    public function getHotcityListAll(){
        $result = $this->where(array('is_delete'=>2))->field(array('region_id','region_name'))->order('order_num asc,create_time desc')->select();
        return $result;      
    }

    /*
    * @func 条件获取热门城市列表
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getHotcityAll($where=array(),$field=array()){
        $result = $this->where($where)->field($field)->order('order_num asc')->select();
        return $result;
    }

    /*
     * @func 获取热门城市列表数量
     * @param array $where
     * @return int
     * author allen
     */
    public function getHotCityTotal($where = true)
    {
        $total = $this->where($where)->count();
        return intval($total);
    }

    /*
    * @func 新增热门城市
    * @param array $data
    * @return bool
    * @author allen
    */
    public function hotCityAdd($data = array()){
        $result = $this->data($data)->add();
        return $result;
    }

}
