<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学员证书表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-13 11:54:48  星期三
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class CertificateModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'certificate';


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
    * @func 获取单个证书数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateName($id=false,$field=array()){
        if($id){
            $info = $this->field(array('full_name'))->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    public function getCertFullName($id=false,$field=array()){
        if($id){
            $info = $this->field(array('full_name'))->where(array('pkid'=>$id))->find();
            return $info['full_name'];
        }
    }

    /*
    * @func 获取单个证书数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateInfo($id=false,$field=array()){
        if($id){
            $info = $this->field(array())->where(array('pkid'=>$id,'is_delete'=>2))->find();
            return $info;
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
    public function certList($type_id){
        if($type_id){
            return $this->where(array('is_delete'=>2,'certificate_type'=>$type_id))->field(array('pkid','full_name',
                    'full_name_en')
            )->select();
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
        return $this->field(array('full_name','pkid','full_name_en'))->where(array('certificate_type'=>$pid, 'is_delete'=>2))->select();
    }
}
