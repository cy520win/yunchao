<?php
// +----------------------------------------------------------------------
// | 企业行业模型 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
/**
 * 企业规模模型
 * @author 致远<george.zou@gaodun.cn>
 */

class IndustryModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'industry';

    /*
    * @func 获取企业规模分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getIndustryList($where=array()){
        $result = $this->where(array('is_delete'=>2))->getField('pkid,title');
        return $result;
    }

}
