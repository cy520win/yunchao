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

class StudentModel extends Model {

    protected $pk = 'pkid';
    //protected $trueTableName = 'student';

    public function getNickName($uid){
        return $this->where(array('uid'=>(int)$uid))->getField('nickname');
    }


    /*------------------------------------------------------------*/ 

    /*
    * @func 获取学员列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getStudentList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    public function getPkid($data){
        if($data)
        return $this->where(array('login_email'=>$data))->getField('pkid');
    }
    
   /*
   * 根据条件获取已验证邮箱用户
   */
    public function mailStudentList($where=array()){
        if($where['post_subscribe.expect_city']){
        	$where['post_subscribe.info_type']=1;
        }
        $result = $this->join('left join post_subscribe on student.pkid=post_subscribe.student_id')
                ->field(array('student.pkid','student.account_id','student.name','student.login_email','student.gender','student.major_type','student.graduate_year','student.education','post_subscribe.expect_city','post_subscribe.pkid as sub_id'))
                ->where($where)->order('student.pkid desc')->group('student.pkid')->select();
        return $result;
    }
   
    /*
    * @func 查询学员总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getStudentTotal($where=false){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }


    } 

    /*
    * @func 保存新增的学生数据
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function studentAdd($data=array()){
        if(!empty($data)){
            $account_data['login_email'] = $data['login_email'];
            $account_data['login_pass'] = MD5(Crypt::encrypt(123456)); //默认密码
            $account_data['account_type'] = 2;//默认账户类型
            $account_data['create_time'] = $data['create_time'];
            $account_data['modify_time'] = $data['modify_time'];
            $account_id = D('Account')->accountAdd($account_data);
            if($account_id){
                $data['account_id'] = $account_id;
                $bool = $this->data($data)->add();
                if($bool){
                    return array('status'=>'true','msg'=>'新增学员成功','data'=>$bool);
                }else{
                    // $bool = D('Account')->accountDelete(); to do ...
                    return array('status'=>'true','msg'=>'新增学员失败','data'=>$account_id);
                }
            }else{
                return array('status'=>'false','msg'=>'学员Account添加失败');
            }
        }
    }

    /*
    * @func 获取学员信息
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getStudentinfo($id=false,$field=array()){
        if($id){
            $info = $this->field($field)->where(array('pkid'=>$id))->find();
            return $info;
        }
    }

    /*
    * @func 保存更新学员
    * @param int $id 、array $data
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */

    public function studentUpdata($data=array(),$id=''){
        if(!empty($data)){
            $bool = $this->data($data)->where(array('pkid'=>$id))->save();
            return $bool;
        }
    }

    //account_id to login_email
    public function aidTomail($data){
        $where['account_id'] = array('in',$data);
        return $this->field(array('login_email','account_id'))->where($where)->select();
    }

    /*
    * @func 获取学员列表数据 增加筛选条件
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author allen
    */
    public function getStudentListOutDelete($where=array(),$field='',$order='create_time asc',$page=1,$number=10){
        $field = '*,student.pkid as pkid';
        $result = $this->field($field)->where($where)

            ->order($order)->page($page,$number)->select();
        return $result;
    }

    public function getStudentTotalOutDelete($where=false){
        if($where){
            $total = $this->where($where)
                ->count();
            return intval($total);
        }
    }
}
