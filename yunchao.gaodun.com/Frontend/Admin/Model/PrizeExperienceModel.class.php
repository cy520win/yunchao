<?php
// +----------------------------------------------------------------------
// | 城市地区模型
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Admin\Model;
use Think\Model;
/**
 * 用户模型
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */

class PrizeExperienceModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'prize_experience';

    /*
    * @func 新增保存学员获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function addPrize($data){
        $data['period'] = $data['period'].'-01';
        $data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['language_type'] = 1;
        return $this->data($data)->add();
    }

    /*
    * @func 获取学员获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function stuPrize($sid){
        return self::resumePirzeEng($sid,1);
    }

    //英文获奖经历
    public function resumePirzeEng($sid,$language=1){
        $language = isset($language) ? intval($language) : 1;
        return $this->field(array('pkid','period','description'))->where(array('is_delete'=>2,'student_id'=>$sid,'language_type'=>$language))->order('period desc')->limit(6)->select();
    }

    /*
    * @func 逻辑删除获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function deleteStuPri($id){
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        $data['is_delete'] = 'Y';
        return $this->where(array('pkid'=>$id))->data($data)->save();
    }

    /*
    * @func 更新保存获奖经历
    * @param array
    * @return $last_id
    * @author 致远<george.zou@gaodun.cn>
    */
    public function updateStuPri($data,$where){
        $data['period'] = $data['period'].'-01';
        $data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
        return $this->where($where)->data($data)->save();
    }
}
