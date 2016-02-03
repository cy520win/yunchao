<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/12/16
 * Time: 13:30
 */

namespace Frontend\Model;
use Think\Model;
class TagModel extends Model{

    protected $trueTableName = 'tag';
    protected $pk = 'pkid';

    /*
     * @function 根据条件返回相应数组
     * @date 2015-12-16
     * @author allen
     */
    public function returnDataByFields($where = array(),$getField = 'pkid'){
        return $this->where($where)->getField($getField);
    }

    /*
     * @function 新增tag
     * @date 2015-12-16
     * @author allen
     */
    public function tagAdd($data){
        return $this->data($data)->add();
    }
}