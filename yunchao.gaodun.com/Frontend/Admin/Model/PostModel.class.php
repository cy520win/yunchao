<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/4/30
 * Time: 11:16
 */

namespace Admin\Model;
//use Think\Cache\Driver\Memcache;
use Think\Model\RelationModel;

class PostModel extends RelationModel{
    protected $pk = 'pkid';
    //protected $trueTableName = 'post';
    protected $patchValidate = true;
   /*  protected $memcached = '';

    public function _initialize(){
        $this->memcached = new Memcache();
    } */

    //创建关联查询获取企业信息
    protected $_link = array(
        'Enterprise' => array(
            'mapping_type'  => self::BELONGS_TO,
            'class_name'    => 'Enterprise',
            'foreign_key'   => 'enterprise_id',
            'mapping_fields'=> 'full_name,telephone',
            'as_fields'     => 'full_name,telephone'
        )
    );

    protected $_validate = array(
        array('province_id',0,'请选择省份',1,'notequal'),
        array('title','require','请填写岗位名称'),
        array('current_grade','require','请选择在读年级',2),
        array('address','require','请填写地址'),
        array('quota','require','请填写岗位数量'),
        array('description','require','请填写描述')
    );

    /*
     * @func 获取岗位列表
     * @param array $where, array $field, string $order, int $page, int $number
     * @return array
     * author allen
     */
    public function getPostList($where = array(),$field = array(),$order = 'create_time desc',$page = 1,$number = 10)
    {
        $result = $this->field($field)->where($where)->order($order)->page($page,$number)->relation(true)->select();
        return $result;
    }

    /*
    * @function 岗位分页列表
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postList($where = array(),$field = array(),$order = 'create_time desc',$page = 1,$number = 10){
        $result = $this->table('post')->join('inner join enterprise on post.enterprise_id=enterprise.pkid')->field($field)->where($where)->order($order)->page($page,$number)->select();
        return $result;
    }


    /*
     * @func 获取职位列表数量
     * @param array $where
     * @return int
     * author allen
     */
    public function getPostTotal($where = false)
    {
        if($where)
        {
            $total = $this->where($where)->count();
            return intval($total);
        }
    }

    /*
    * @function 职位列表总数
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postTotal($where=array()){
        $total = $this->table('post')->join('inner join enterprise on post.enterprise_id=enterprise.pkid')->where($where)->count();
        return $total>0 ? intval($total) : FALSE;
    }


    /*
     * @func 获取单条数据
     * @param $id
     * @return array
     * author allen
     */
    public function getInfo($id ='')
    {
        if($id)
        {
            $info = $this->relation(true)->find($id);
            return $info;
        }
    }

    /*
    * @func 新增岗位信息
    * @param array
    * @return int
    * @author allen
    */
    public function postAdd($data=array())
    {
        if(!empty($data))
        {
            $bool = $this->data($data)->add();
            return $bool;
        }
    }

    /*
    * @func 更新岗位信息
    * @param array
    * @return int
    * @author allen
    */
    public function postUpdate($data=array())
    {
        if(!empty($data))
        {
            $bool = $this->data($data)->save();
            //$this->memcached->rm(md5('Post_' . $data['pkid']));
            S(md5('Post_' . $data['pkid']),'');
            return $bool;
        }
    }

    /*
    * @func 删除岗位信息
    * @param array
    * @return int
    * @author allen
    */
    public function postDelete($id)
    {
        if(!empty($id))
        {
            $bool = $this->where(array('pkid' => $id))->data(array('is_delete' => 'Y'))->save();
            return $bool;
        }
    }

    /*
    * @func 岗位名称信息
    * @param array
    * @return int
    * @author allen
    */
    public function getPostTitle()
    {
        $result = $this->where(array('is_delete' => 'N'))->getField('pkid,title');
        return $result;
    }

    // 删除缓存职位信息,职位PKID
    public function postCacheDel($id){
        //$delete = memcache($this->memcached,'Post',$id,'rm');
        $delete = S('Post',$id,'');
        return $delete;
    }
}