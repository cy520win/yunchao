<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：获奖荣誉表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-13 08:51:39  星期三
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class PrizeExperienceModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'prize_experience';
    protected $deleteType = 1;//删除
    protected $activeType = 2;//生效

    /*
    * @func 新增保存学员获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addPrize($data){
        $data['student_id'] = session('account.student_id');
        $data['create_time'] = dateTime();
        $data['modify_time'] = $data['create_time'];
        return $this->data($data)->add();
    }

    /*
    * @func 获取学员获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPrize($id = ''){
        if($id){
            $student_id = $id;
        }else{
            $student_id = session('account.student_id');
        }
        return $this->field(array('pkid','period','description'))->where(array('is_delete'=>$this->activeType,'student_id'=>$student_id,'language_type'=>1))->order('period desc')->limit(6)->select();
    }

    /*
    * @func $pkid = 获取学员获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPrizePkid($pkid){
        return $this->field(array('pkid','period','description'))->where(array('is_delete'=>$this->activeType))->find($pkid);
    }

    /*
    * @func 逻辑删除获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deletePrize($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新保存获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updatePrize($data,$where){
        $data['modify_time'] = dateTime();
        return $this->where($where)->data($data)->save();
    }

    /*
    * @function 是否生成获奖荣誉
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function existPrize($id=""){
        if($id){
            $student_id = $id;
        }else{
            $student_id = D('Account')->studentID();
        }
        $number = $this->where(array('student_id'=>$student_id,'is_delete'=>2,'language_type'=>1))->count();
        return $number!=0?true:false;
    }

    /*
    * @func 删除获奖荣誉模块
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deletePrizeModule($id){
        $data['modify_time'] = dateTime();
        $data['is_delete'] = $this->deleteType;
        $where = array('student_id'=>D('Account')->studentID(),'language_type'=>1);
        return $this->where($where)->data($data)->save();
    }
}
