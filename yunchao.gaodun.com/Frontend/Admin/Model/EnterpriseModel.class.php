<?php
// +----------------------------------------------------------------------
// | 企业账户模型 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Model;
//use Think\Cache\Driver\Memcache;
use Think\Model;
/**
 * 企业模型
 * @author  致远<george.zou@gaodun.cn>
 */

class EnterpriseModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'enterprise';
    protected $memcached = '';
    /* public function _initialize(){
        $this->memcached = new Memcache();
    } */

    /*
    * @func 查询企业总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getEnterpriseTotal($where=false){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }
    } 

    /*
    * @func 获取企业列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getEnterpriseList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    public function getEnterpriseMail($where=array()){
        $result = $this->field(array('login_email','full_name','scale_id','city_id','industry_id','account_id'))->where($where)->order('order_num desc,pkid desc')->select();
        return $result;
    }

    /*
    * @func 保存新增企业信息
    * @param array $data
    * @return int $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function enterpriseAdd($data=array()){
    	if(!empty($data)){
    		$last_id = $this->data($data)->add();
    		return $last_id;
    	}
    }

    /*
    * @func 获取企业信息
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getEnterpriseinfo($id=false,$field=array()){
        if($id){
            $info = $this->field($field)->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    /*
    * @func 保存企业数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function enterpriseUpdata($data=array(),$id=''){

        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            //$this->memcached->rm(md5('Enterprise_' . $id));
            S(md5('Enterprise_' . $id),'');
            return $bool;
        }
    }


    /*
     * 获取企业名称
     * @return array
     * author allen
     */
    public function getEnterpriseTitle($where = array()) {
        $result = $this->where($where)->getField('pkid,full_name,telephone'); //subscribe_email,
        return $result;
    }

    //account_id to login_email
    public function aidTomail($data){
        $where['account_id'] = array('in',$data);
        return $this->field(array('login_email','account_id'))->where($where)->select();
    }


    //增加过滤
    public function getEnterpriseListOutDelete($where=array(),$field=array(),$order='create_time asc',$page=1, $number=10){
        $result = $this->field($field)->where($where)
            ->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 查询企业总数
    * @param array $where
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getEnterpriseTotalOutDelete($where=false){
        if($where){
            $total = $this->where($where)
                ->count();
            return intval($total);
        }
    }

}
