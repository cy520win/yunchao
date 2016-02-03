<?php
// +----------------------------------------------------------------------
// | 标签模型
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : <yangfuyi@gaodun.cn> 
// +----------------------------------------------------------------------

namespace Admin\Model;
use Think\Model;
class TagModel extends Model {
    protected $pk = 'pkid';
    //protected $trueTableName = 'tag';

    /*
    * @func 列表
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function tagList(){
        $result = $this->field(array('pkid','title'))->where(array('status'=>1))->order('pkid asc')->select();
        return $result;
    }

    /*
    * @func 新增标签
    * @param array
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function tagAdd($data){

    }

    /*
     * @func 获取标签列表
     * @param array $where string $field int $page int $page_number
     * @return array
     * @author allen
     */
    public function getTagList($where = array(),$field = array(),$page = 1,$page_number = 10,$order=array('is_delete'=>'desc','create_time'=>'desc'))
    {
        $result = $this->field($field)->where($where)->order($order)->page($page, $page_number)->select();
        return $result;
    }

    /*
     * @func 获取福利总数
     * @param array $where
     * @return int
     * @author allen
     */
    public function getTagTotal($where = true)
    {
        $int = $this->where($where)->count();
        return intval($int);
    }

}
