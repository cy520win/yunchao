<?php
// +----------------------------------------------------------------------
// | 学员账户模型 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
use Think\Crypt as Crypt;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class CertificateModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'certificate';

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }


    /*------------------------------------------------------------*/ 

    /*
    * @func 获取证书列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getCertificateList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 查询证书总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateTotal($where=array()){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }


    } 

    /*
    * @func 保存新增的证书数据
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function certificateAdd($data=array()){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;
        }
    }

    /*
    * @func 获取单个证书数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateinfo($id=false,$field=array()){
        if($id){
            $info = $this->field($field)->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    public function resumeCertTitleEng($id=''){
        if($id){
            $info = $this->field('full_name_en')->where(array('pkid'=>$id))->find();
            return $info['full_name_en'];    
        }
    }

    /*
    * @func 保存更新证书数据
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function certificateUpdata($data=array(),$id=''){
        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 保存更新证书数据
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function certificateDelete($id){
        if($id){
            $bool = $this->data(array('is_delete'=>1,'modify_time'=>date("Y-m-d H:i:s",NOW_TIME)))->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    /*
    * @func 保存更新证书数据
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function certificateMajorCard($parent_id){
        if($parent_id){
            return $this->where(array('is_delete'=>2,'certificate_type'=>$parent_id))->field(array('pkid','full_name'))->select();
        }
    }

    /*
    * @func 获取证书分类
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCartType($cid){
        return $this->field(array('certificate_type'))->find($cid);
    }

    /*
    * @func 获取某分类下所有证书
    * @param int $id=pkid
    * @return bool
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCartTypeList($pid){
        return $this->field(array('full_name','pkid'))->where(array('certificate_type'=>$pid))->select();
    }
}
