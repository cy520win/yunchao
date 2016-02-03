<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学生证书表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-13 14:00:58  星期三
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class StudentCertificateModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'student_certificate';
    protected $deleteType = 1;
    protected $activeType = 2;

    /*
    * @func 新增
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addCertificate($data){
        $data['student_id'] = D('Account')->studentID();
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        return $this->data($data)->add();
    }

    /*
    * @func 
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getcert($id = ''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        return $this->field(array('pkid','finish_time','certificate_id','status','certificate_name'))->where(array('is_delete'=>$this->activeType,'student_id'=>$student_id,'language_type'=>1))->order('finish_time desc')->select();
    }

    /*
    * @func
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteCertificate($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updateCertificate($data,$where){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        return $this->where($where)->data($data)->save();
    }

    /*
    * @func 获取单个证书数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getCertificateInfo($id=false,$field=array()){
        if($id){
            $info = $this->field(array())->where(array('pkid'=>$id))->find();
            return $info;
        }
    }


    /*
    * @function 是否生成证书
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existCertificate($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }
        $number = $this->where(array('student_id'=>$student_id,'is_delete'=>2,'language_type'=>1))->count();
        return $number>0?true:false;
    }

    /* 
    * @func 删除荣誉证书模块
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteCertificateModule($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        $where = array('student_id'=>D('Account')->studentID(),'language_type'=>1);
        return $this->where($where)->data($data)->save();
    }

    /*
    * @function 判断学生证书的唯一性
    * @param $cert_id ，证书主键PKID
    * @param $type ,为TRUE,新增时检测；为FALSE编辑时检测，默认TURE
    * @param $pkid ，学生证书主键PKID
    * @param $language_type ，语言类型，1为中文，2位英文
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function checkOnlyCertificate($cert_id,$type=TRUE,$pkid='',$language_type=1){
        $where['certificate_id'] = $cert_id;
        $where['is_delete'] = 2 ;
        $where['language_type'] = $language_type ;
        $where['student_id'] = session('account.student_id');
        if(!$type){
            $where['pkid'] = array('neq',$pkid);
        }
        $count = $this->where($where)->count();
        return $count > 0 ? TRUE :FALSE;
    }
}
