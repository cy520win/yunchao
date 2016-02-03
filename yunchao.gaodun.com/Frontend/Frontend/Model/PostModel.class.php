<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/5/19
 * Time: 14:35
 */

namespace Frontend\Model;
use Think\Model\RelationModel;
class PostModel extends RelationModel
{

    protected $pk = 'pkid';
    protected $trueTableName = 'post';
    protected $_link = array(
        'MajorType' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'MajorType',
            'foreign_key' => 'major_wish',
            'as_fields' => 'title:major_title'
        ),
        'Enterprise' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Enterprise',
            'foreign_key' => 'enterprise_id',
            'as_fields' => 'full_name:enterprise_name,logo,scale_id,industry_id,ideality'
        ),
        'Category' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'PostCategory',
            'foreign_key' => 'category_id',
            'as_fields' => 'title:category_name'
        ),
        'Industry' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Industry',
            'foreign_key' => 'industry_id',
            'as_fields' => 'title:industry_name'
        )
    );

    protected  $memcached = '';
    public function _initialize() {
        $this->memcached = new \Think\Cache\Driver\Memcache();
    }

    /*
     * @func 获取岗位列表
     * @param array $where
     * @return array
     * @author allen
     */
    public function getPostList($where = array(), $page = 1, $page_number = 10,$order = '')
    {   
        $where['post.is_delete'] = 2;
        $where['post.status'] = !empty($where['post.status']) ? $where['post.status'] : 1;
        $order = $order ? $order : 'create_time desc';
        $field = 'post.*,regions.region_name,IFNULL(post.refresh_time,post.create_time) as create_time';
        $result = $this->field($field)->join('regions ON post.city_id = regions.region_id')->where($where)->relation
        (true)->page($page, $page_number)->order($order)->select();
        return $result;
    }

    /*
     * @func 获取岗位列表数目
     * @param array $where
     * @return array
     * @author allen
     */
    public function getPostTotal($where = array())
    {
        $where['post.is_delete'] = 2;
        $where['post.status'] = 1;
        $result = $this->join('regions ON post.city_id = regions.region_id')->where($where)->count();
        return intval($result);
    }

    /*
    * @function 获取下线职位的总数
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>')  2015-07-27 13:54:57  星期一
    */
    public function getPostTotalOffine($where = array())
    {
        $where['post.is_delete'] = 2;
        $where['post.status'] = 2;
        $result = $this->join('regions ON post.city_id = regions.region_id')->where($where)->count();
        return intval($result);
    }  

    /*
    * @function 
    * @param 
    * @return 
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */

    public function getQueuePost($where){
        if ($where) {
            $where .= ' AND post.status=1 AND is_delete=2';
        } else {
            $where = 'post.status=1 AND is_delete=2';
        }
        $data = $this->join('enterprise on post.enterprise_id=enterprise.pkid')
                    ->field(array('post.pkid', 'post.city_id', 'enterprise.full_name', 'post.education', 'post.week_available', 'post.salary_range', 'enterprise.scale_id', 'enterprise.industry_id', 'post.category_id', 'post.title','post.major_wish','IFNULL(post.refresh_time,post.create_time) as create_time'))
                    ->where($where)->limit(3)->order('create_time desc')->select();
        return $data;

    }
    /*
     * @func 获取岗位详情
     * @param array $where
     * @return array
     * @author allen
     */
    public function getPostInfo($where = array()){
        $field = 'post.*,regions.region_name';
        $result = $this->field($field)->relation(true)->join('regions ON post.city_id = regions.region_id')->where
        ($where)
            ->find();
        return $result;
    }

    /*
     * @func 获取岗位详情
     * @param pkid
     * @return array
     * @author allen
     */
    public function getPostById($pkid = ''){
    	  $result = $this->getDataMemcached($pkid);
    	  if(!$result){
	        $field = 'post.*,regions.region_name';
	        $result = $this->field($field)->relation(true)->
	        	join('regions ON post.city_id = regions.region_id')->
	        	where('pkid='.$pkid)->find();
	        $this->setDataMemcached($pkid,$result);
         }
        return $result;
    }

    public function getPostInfoById($pkid = ''){
        $result = memcache($this->memcached,'Post',$pkid,'get');
        if(empty($result)){
            $field = 'post.*,regions.region_name';
            $result = $this->field($field)->relation(true)->
            join('regions ON post.city_id = regions.region_id')->
            where('post.pkid='.$pkid)->find();
            memcache($this->memcached,'Post',$pkid,'set',$result);
        }
        return $result;
    }

        /*
         * @func 新增岗位信息
         * @param array $data
         * @return array
         * @author allen
         */
        public function postAdd($data = array()){
            $result = $this->data($data)->add();
            return $result;
        }

        /*
         * @func 编辑岗位信息
         * @param array $data
         * @return array
         * @author allen
         */
        public function postUpdate($data = array()){
            $result = $this->data($data)->save();
            if($result !== false){
                memcache($this->memcached,'Post',$data['pkid'],'rm');
            }
            return $result;
        }

        /*
         * @func 相似岗位信息
         * @param array $where
         * @return array
         * @author allen
         */
        public function getSamePostList($where = array()){
            $field = 'post.pkid,post.title,post.enterprise_id,regions.region_name';
            $result = $this->field($field)->join('regions ON post.city_id = regions.region_id')->where($where)->order
            ('create_time desc')->relation('Enterprise')->limit(4)->select();
            return $result;
        }

        //首页职位
        public function getPostHost($id=''){
            if(empty($id)){
                return false;
            }
            if($id==1){//热门
                $where['post.is_hot'] = 1;
            }
            if($id==2){ //财会
            	  $where['post.is_hot'] = 2;
                $where['post.category_id'] = 2;
            }
            if($id==3){ //金融
            	  $where['post.is_hot'] = 2;
                $where['post.category_id'] = 3;
            }
            if($id==4){//综合
                $where['post.is_hot'] = 2;
                $where['_string'] = ' (post.category_id != 2)  AND ( post.category_id != 3) ';
            }
            $where['post.home_show'] = 1;
            $where['post.is_delete'] = 2;
            $where['post.status'] = 1;
            $field = 'post.*,regions.region_name';
            $result = $this->field($field)->join('regions ON post.city_id = regions.region_id')->where($where)->order
            ('order_num desc')->relation(true)->limit(4)->select();
            return $result;
        }
        
    /*
    * @function 
    * @param 主键PKID
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 2015-08-06 10:18:37  星期四
    */
    public function postReflushTime($pid){
        return $this->getFieldByPkid($pid,'refresh_time');
    }

    //主键PKID
    public function existPostPkid($pid){      
        return $this->getFieldByPkid($pid,'pkid');
    }

    //更新职位发布时间
    public function updateReflushTime($pid){
        $data['refresh_time'] = dateTime();
        $date['modify_time']  = $data['refresh_time'];
        $where['pkid'] = $pid;
        $where['enterprise_id'] = session('account.enterprise_id');
        return $this->data($data)->where($where)->save();
    }

    /*
    * @function 企业有效职位列表
    * @param 
    * @return $pkid,$title
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postOptionList(){
        $id = session('account.enterprise_id');
        $list = array();
        if(!empty($id)){
            $list = $this->where(array('enterprise_id' =>$id,'is_delete'=>2,'status'=>1))->order('pkid desc')->getField('pkid,title');
        }
        return $list;
    }

    /*
    * @function 获取职位基本信息，用于匹配学生的实习意向
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postInfoInvite($pkid){
        $id = session('account.enterprise_id');
        $where = array('enterprise_id' =>$id,'is_delete'=>2,'pkid'=>$pkid);
        $result = $this->field(array('pkid','title','category_id','city_id','salary_range','week_available'))->where($where)->find();
        return $result;
    }

    /*
    * @function 通过职位主键获取简历标题
    * @param 职位PKID
    * @return 职位title
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function postTitle($pkid){
        $result = $this->field('title')->find($pkid);
        return $result['title'];
    }

    /*
    * @function 企业详情中,4个热门企业，通过热门职位获取热门企业
    * @param int:$enterprise_id 
    * @return array
    * 致远<george.zou@gaodun.cn> ('<^>') 2015-10-19 16:06:22  星期一
    */
    public function hotEnterprise($enterprise_id=NULL){
        if($enterprise_id && is_integer($enterprise_id)){
          $where['p.enterprise_id'] = array('neq',$enterprise_id);  
        }else{
            return NULL;
        }
        $where['p.is_delete'] = 2 ;
        $where['p.home_show'] = 1;
        $where['p.status'] = 1;
        $start = rand(0,4);
        $field = array('e.pkid','e.full_name','r.region_name as city_name','e.logo','i.title as industry_title');
        $data = $this->table('post as p')->join('inner join enterprise as e on p.enterprise_id=e.pkid')
                    ->join('inner join regions as r on e.city_id=r.region_id')
                    ->join('inner join industry as i on e.industry_id=i.pkid')
                    ->where($where)->field($field)->group('p.enterprise_id')
                    ->order('p.order_num desc')->limit($start,4)->select();
        return !empty($data) ? $data : NULL;
    }
}