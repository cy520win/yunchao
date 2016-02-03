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
    protected $trueTableName = 'hot_city';

    /*
    * @func 获取热门城市列表
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getHotcityList($where=array(),$field=array()){
        $result = $this->where($where)->field($field)->order('order_num asc')->select();
        return $result;
    }

}
