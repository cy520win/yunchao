<?php
// +----------------------------------------------------------------------
// | 企业福利表模型
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;

class WelfareModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'welfare';


    /*
    * @func 企业福利列表
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function welfareList(){
        $result = $this->field(array('pkid','title'))->where(array('status'=>1))->order('pkid asc')->select();
        return $result;
    }

    /*
    * @func 新增企业福利
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function welfareAdd($data){

    }

    /*
     * @func 获取福利列表
     * @param array $where string $field int $page int $page_number
     * @return array
     * @author allen
     */
    public function getWelfareList($where = array(),$field = true,$page = 1,$page_number = 10)
    {
        $result = $this->field($field)->where($where)->order('create_time desc')->page($page, $page_number)->select();
        return $result;
    }

    /*
     * @func 获取福利总数
     * @param array $where
     * @return int
     * @author allen
     */
    public function getWelfareTotal($where = true)
    {
        $int = $this->where($where)->count();
        return intval($int);
    }

}
