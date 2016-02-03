<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：实习经历表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-12 17:03:44  星期二
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class PracticeModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'practice';
    public $deleteType = 1;//删除
    public $activeType = 2;//有效

    /*
    * @func 新增
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addPractice($data){
        $data['student_id'] = session('account.student_id');
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        return $this->data($data)->add();
    }

    /*
    * @func $student_id 获取学生实践经历
    * @param $student_id
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPractice($id=''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        return $this->field(array('pkid','start_time','finish_time','organization','quarters','content'))->where(array('is_delete'=>$this->activeType,'student_id'=>$student_id,'language_type'=>1))->order('start_time desc')->select();             
    }

    /*
    * @func $pkid 获取学生实践经历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPracticePkid($id){
        return $this->field(array('pkid','start_time','finish_time','organization','quarters','content'))->where(array('is_delete'=>$this->activeType,'pkid'=>$id))->find();          
    }

    /*
    * @func 逻辑删除学生实践经历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function deletePractice($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新单个学员实践经历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updatePractice($data,$where){
        $data['modify_time'] = dateTime();
        return $this->data($data)->where($where)->save();
    }

    /*
    * @function 是否生成实习经历
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existPractice($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }
        $number = $this->where(array('student_id'=>$student_id,'is_delete'=>$this->activeType,'language_type'=>1))->count();
        return intval($number)>0?true:false;
    }

    /*
    * @func 逻辑删除学生实践经历
    * @param $pkid
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function deletePracticeModule(){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        $where = array('student_id'=>D('Account')->studentID(),'language_type'=>1);
        return $this->where($where)->data($data)->save();
    }

}
