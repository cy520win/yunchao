<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：学员专业类型表模型
// +----------------------------------------------------------------------
// | 创建时间 ：2015-05-08 17:19:13  星期五
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class MajorTypeModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'major_type';

    /*
     * 获取专业名称
     * @return array
     * author allen
     */
    public function getMajorTitle() {
        $result = $this->where(array('is_delete' => 2))->getField('pkid,title');
        return $result;
    }

    /*------------------------------------------------------------*/ 

    /*
    * @func 获取专业分类列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getMajorTypeList($where=array(),$field=array(),$order='create_time asc',$page=1,$number=10){
        $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }

    /*
    * @func 条件获取专业分类列表数据
    * @param array $where 、array $field、string $order、int $page、int $number
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 

    public function getMajorTypeData(){
        $result = $this->field(array('pkid','title'))->where(array('valid_status'=>1))->select();
        return $result;
    }

    public function getMajorType($where=array(),$field=array()){
        $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 获取专业分类KEY-VALUE数组
    * @param
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeSelect($type = 'CH'){
        $field = array(
            'CH' => 'pkid,title',
            'EN' => 'pkid,title_en'
        );
        $result = $this->where('is_delete=2')->getField($field[$type]);
        return $result;
    }

    /*
    * @func 查询专业分类总数
    * @param array $where 
    * @return int
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeTotal($where=array()){
        if($where){
            $total = $this->where($where)->count();
            return intval($total);           
        }
    } 

    /*
    * @func 获取单个专业数据
    * @param int $id  primarykey 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */
    public function getMajorTypeById($id=false,$field=array()){
        if($id){
            return $this->field($field)->where(array('pkid'=>$id))->find();
        }
    }

    public function getMajorText($where){
        return $this->where(array('pkid'=>array('like',$where)))->field(array('title','pkid'))->select();
    }
  
}
