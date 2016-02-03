<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：岗位分类表模型
// +----------------------------------------------------------------------
// | 创建时间 ：
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Model;
use Think\Model;
class PostCategoryModel extends Model {

    protected $pk = 'pkid';
    protected $trueTableName = 'post_category';

    /*
    * @func 获取企业岗位条件分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPostList($where=array(),$field=array()){
        empty($where) ? $where['parent_id']=0 : $where;
        empty($field) ? $field = array('pkid','parent_id','title') : $where;
        $where['is_delete']=2;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 获取企业岗位条件分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPostParentCate($where=array(),$field=array()){
        $field = array('pkid','title','title_en');
        $where['is_delete']=2;
        $where['parent_id']=0;
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /*
    * @func 获取企业岗位所有分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getPostAll($where=array(),$field=array()){
        empty($where) ? $where['parent_id']=0 : '';
        empty($field) ? $field = array('pkid','parent_id','title') : '';
        $where['is_delete'] = 2;
        $result = $this->field($field)->where($where)->select();
        foreach ($result as $k => $v) {
                $where_new['parent_id'] = $v['pkid'];
                $reslut_new = self::getPostAll($where_new);
                if($reslut_new){
                    $result[$k]['second']= $reslut_new;
                }       
        } 
        return $result;
    }


    /*
    * @func 添加岗位分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function postAdd($data){
        if(!empty($data)){
            $last_id = $this->data($data)->add();
            return $last_id;           
        }

    }

    /*
    * @func 保存更新分类名称
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function postUpdate($data=array(),$where=array()){
        if(!empty($where)){
            return $this->data($data)->where($where)->save();
        }
    }

    /*
    * @func 更改分类状态
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function postDelete($id){
        if($id){
            $result = self::getChildNum(array('parent_id'=>$id));
            if($result>0){
                return array('msg'=>'该分类下还有子分类，不能删除','status'=>false);
            }else{
                $data['is_delete'] = 1 ;
                $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
                $bool = $this->data($data)->where(array('pkid'=>$id))->save();
                if($bool){
                    return array('msg'=>'删除成功','status'=>true);
                }else{
                    return array('msg'=>'删除失败','status'=>false);
                }
            }
        }
    }

    /*
    * @func 某个分类的所有子分类数量
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getChildNum($where=array()){
        if(!empty($where)){
            $where['is_delete'] = array('eq',2);
            return $this->where($where)->count();           
        }
    }

    /*
    * @func 验证同级父类下的分类名称
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function checkChildeName($where=array()){
        if(!empty($where)){
            $where['is_delete'] = array('eq',2);
            return $this->where($where)->count();           
        }
    }

    /*
    * @func 一级岗位分类
    * @param array 
    * @return array
    * @author 致远<george.zou@gaodun.cn>
    */ 
    public function getParentCate(){

        return $this->where(array('is_delete'=>2,'parent_id'=>0))->field(array('pkid','title'))->select();
    }


    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function getCategoryText($id='',$type='CH'){
        if($id){
            $typeArr = array('CH' => 'title','EN' => 'title_en');
            $data = $this->field(array($typeArr[$type]))->where(array('is_delete'=>2))->find($id);
            return $data;            
        }
    }



    /*
     * 获取岗位分类列表
     * @return array
     * @author allen
     */
    public function getCategoryTitle()
    {
        $result = $this->where(array('is_delete' => 2))->getField('pkid,title');
        return $result;
    }

}
