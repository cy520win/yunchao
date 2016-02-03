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

namespace Frontend\Model;
use Think\Model\RelationModel;
/**
 * 企业模型
 * @author  致远<george.zou@gaodun.cn>
 */

class EnterpriseModel extends RelationModel {

    protected $pk = 'pkid';
    protected $trueTableName = 'enterprise';

    protected $_link = array(
        /*'EnterpriseWelfare' => array(
            'mapping_type'  => self::HAS_MANY,
            'class_name'    => 'EnterpriseWelfare',
            'foreign_key'   => 'enterprise_id',
            'mapping_name'  => 'welfare_id',
            'mapping_fields'=> 'welfare_id',
            'condition'     => 'enterprise_welfare.is_delete = 2'
        ),关闭福利*/
        'Scale'             => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Scale',
            'foreign_key'   => 'scale_id',
            'as_fields'     => 'title:scale_title,description:scale_description'
        ),
        'Industry'          => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Industry',
            'foreign_key'   => 'industry_id',
            'as_fields'     => 'title:industry_title'
        ),
    );

    protected  $memcached = '';
    public function _initialize() {
        $this->memcached = new \Think\Cache\Driver\Memcache();
    }

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
    public function getEnterpriseList($where=array(),$field=array(),$order='create_time asc'){
        $result = $this->field($field)->where($where)->order($order)->select();
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
            actionLogAdd(7,session('account'));
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

    public function getEnterpriseAccount(){
    return $this->field(array('pkid','full_name as name'))->where(array('account_id'=>session('account.account_id')))->find();
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
            memcache($this->memcached,'Enterprise',$id,'rm');
            $this->rmMemcacheForPost($id);
            actionLogAdd(7,session('account'));
            return $bool;
        }
    }

    /*
    * @func account_id更新企业的邮箱状态
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function upMailStatus($id=''){
        if(!empty($id)){
            $result = $this->field('pkid')->where(array('account_id' => $id))->find();
            memcache($this->memcached,'Enterprise',$result['pkid'],'rm');
            return $this->data(array('email_verify'=>1,'modify_time'=>date('Y-m-d H:i:s',NOW_TIME)))->where(array('account_id'=>$id))->save();
        }
    }

    /**----------------------------分割线---------------------------------------*/ 

    /*
    * @func 添加企业基本信息
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */

    public function addBaseCompany($data=array()){
        if(!empty($data)){
            $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $data['modify_time'] =  $data['create_time'];
            return $this->data($data)->add();
        }
    }

    public function getMailStatus($mail=""){
        if($mail){
            $result = $this->where(array('login_email'=>$mail))->field(array('email_verify'))->find();
            return $result;
        }
    }

    //获取企业PKID
    public function getEnterpriseId($aid){
        return $this->getFieldByAccount_id($aid,'pkid');
    }

    /**----------------------------分割线专题活动---------------------------------------*/
    /*
     * 根据条件获取企业信息
     * @param array $where
     * @return array
     * @author allen
     */
    public function getInfoByWhere($where = array()){
        if($where) {
            $result = $this->field('enterprise.*,regions.region_name as city_name')->join('left join regions ON enterprise.city_id = regions.region_id')->where($where)
                    ->relation(true)->find();
            return $result;
        }
    }

    //判断企业是否有邀请码
    public function getExistInvite($where){
        $field = array('inviter_code');
        $data = $this->field($field)->where($where)->find();
        return $data['inviter_code'];
    }

    /*
     * 根据ID获取企业信息
     * @param array $where
     * @return array
     * @author allen
     */
    public function getInfoById($id){
        $result = memcache($this->memcached,'Enterprise',$id,'get');
        if(empty($result)){
            $result = $this->field('enterprise.*,regions.region_name as city_name')
                ->join('left join regions ON enterprise.city_id = regions.region_id')
                ->where(array('enterprise.pkid' => $id))
                ->relation(true)->find();
            memcache($this->memcached,'Enterprise',$id,'set',$result);
        }

        return $result;
    }
   
   /*
   * 暑期专题活动专用
   */
    public function getListByEnterprise(){
        $result = $this->field('enterprise.*,regions.region_name as city_name')->join('regions ON enterprise.city_id=regions.region_id')
            ->where(array('enterprise.approve_status' => 2))->relation(true)->order('order_num desc')->select();
        return $result;
    }

    /**----------------------------分割线专题活动结束---------------------------------------*/
    /*
     * 根据条件获取企业列表信息，实现分页及查询功能
     * @param array $where string $order int $page int $page_number
     * @return array
     * @author allen
     */
    public function getListOfEnterprise($where = array(),$page = 1,$page_number = 10){
        $order = 'modify_time desc';
        $result = $this->field('enterprise.*,regions.region_name as city_name')->join('regions ON enterprise.city_id=regions.region_id')
            ->where($where)->order($order)->page($page,$page_number)->relation(true)->select();
        return $result;
    }

    public function getTotalOfEnterprise($where=false){
        if($where){
            $total = $this->join('regions ON enterprise.city_id=regions.region_id')->where($where)->count();
            return intval($total);
        }
    }

    //更新企业的邀请码和二维码字段
    public function upInviteCode($data){
        return $this->data($data)->where(array('pkid'=>session('account.enterprise_id')))->save();
    }

    //通过邀请码获取企业PKID
    public function enterCodeID($code){
        $pkid = $this->getFieldBySelfCode($code,'pkid');
        return $pkid;
    }

    //更新企业的邀请码
    public function addInviteCode($account_id,$code){
        $data['modify_time'] = dateTime();
        $data['inviter_code'] = $code;
        $where['account_id'] = $account_id;
        return $this->data($data)->where($where)->save();
    }

    //更新企业积分
    public function updateEnterScore($pkid,$score){
        $where['pkid'] = $pkid;
        $data['score_amount'] = $score;
        $data['modify_time'] = dateTime();
        return $this->data($data)->where($where)->save();
    }

    //更新企业信息后，批量删除职位相关的缓存
    public function rmMemcacheForPost($id){
        if($id){
            $data = M('Post')->field('pkid')->where(array('enterprise_id' => $id))->select();
            if(!empty($data)){
                foreach ($data as $value) {
                    memcache($this->memcached,'Post',$value['pkid'],'rm');
                }
            }
        }
    }

    //根据邮箱获取inviter_code
    public function getCodeByMail($mail){
        return $this->getFieldByLoginEmail($mail,'inviter_code');
    }

    //通过pkid 获取inviter_code
    public function getPostTotal($pkid){
        $where['enterprise_id'] = $pkid;
        $where['is_delete']  = 2 ;
        $total = M('post')->where($where)->count();
        return $total;
    }

    /*
    * @function 新增或编辑联系人信息
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function updateContact($data){
        $contactModel = M('enterprise_contact');
        $where['type'] = 1;
        $where['enterprise_id'] = session('account.enterprise_id');
        $contact_id = $contactModel->where($where)->order('pkid desc')->getField('pkid');
        if($contact_id>0){
            $where['pkid'] = $contact_id;
            $contactModel->data($data)->where($where)->save();
        }else{
            $data['type'] = 1;
            $data['create_time'] = dateTime();
            $data['modify_time'] = dateTime();
            $data['enterprise_id'] = session('account.enterprise_id');
            $contactModel->data($data)->add();
        }
    }

    /*
    * @function 获取企业输入的默认联系人
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function getContactInfo(){
        $where['type'] = 1;
        $where['enterprise_id'] = session('account.enterprise_id');
        return M('enterprise_contact')->where($where)->order('pkid desc')->find();
    }

    /*
    * @function 判断企业是否填写基本信息
    * @param int:$pkid 主键PKID
    * @return bool 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function chkEnterBase($pkid=''){
        $pkid = $pkid ? $pkid : session('account.enterprise_id');
        $data = $this->getEnterpriseinfo($pkid,'full_name');
        return empty($data['full_name']) ? FALSE : TRUE;
    }
}
