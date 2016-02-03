<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/12/16
 * Time: 16:43
 */

namespace Frontend\Model;
use Think\Model;
class TagRelationModel extends Model{

    protected $trueTableName = 'tag_relation';
    protected $pk = 'pkid';

    /*
     * @function 获取职位内福利标签
     * @param array $where
     */
    public function returnTagRelationLists($where = array()){
        return $this->field('tr.title,tag_relation.tag_id')
            ->where($where)
            ->join('tag as tr ON tr.pkid=tag_relation.tag_id')
            ->select();
    }

    /*
     * @function 更新职位福利标签
     * @param array $data
     */
    public function tagRelationSave($data,$where){
        return $this->data($data)->where($where)->save();
    }

    /*
     * @function 新增职位福利标签
     * @param array $data
     */
    public function tagRelationAdd($data){
        return $this->addAll($data);
    }
}