<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/12
 * Time: 9:49
 */

namespace Frontend\Model;
use Think\Model;

class EnterpriseWelfareModel extends Model{

    protected $pk = 'pkid';
    protected $trueTableName = 'enterprise_welfare';

    /*
     * 删除数据
     * @param array $where
     * @return int || bool
     * @author allen
     */
    public function enterpriseWelfareDelete($where = array()){
        if(!empty($where)){
            $result = $this->where($where)->delete();
            return $result;
        }
    }

    /*
     * 批量新增数据
     * @param array $data
     * @return int
     * @author allen
     */
    public function enterpriseWelfareAddAll($data = array()){
        if(!empty($data)){
            $result = $this->addAll($data);
            return $result;
        }
    }
}