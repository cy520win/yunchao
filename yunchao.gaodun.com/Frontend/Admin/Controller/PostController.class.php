<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/4/30
 * Time: 11:01
 */

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class PostController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $PostModel;
    protected $MajorTypeModel;
    protected $RegionsModel;
    protected $EnterpriseModel;
    protected $CategoryModel;
    protected $nowtime;
    protected $salary_range = array(); //日薪
    protected $current_grade = array(); //年级
    protected $week_available = array(); //天数
    protected $enterprise_list = array();//企业
    protected $graduate_year = array();//毕业年份

    public function _initialize()
    {
        parent::_initialize();
        $this->PostModel        = D('Post');
        $this->RegionsModel     = D('Regions');
        $this->nowtime          = date('Y-m-d H:i:s',NOW_TIME);
        $this->salary_range     = parent::getSalaryArr();
        $this->current_grade    = parent::getGradeArr();
        $this->graduate_year    = parent::getGraduateArr();
        $this->week_available   = parent::getWorkDayArr();
        $this->enterprise_list  = parent::getEnterpriseList();
    }

    /*
     * 获取专业列表
     * @reutrn array
     * author allen
     */
    public function getMajorList()
    {
        if(!$this->MajorTypeModel)
        {
            $this->MajorTypeModel = D('MajorType');
        }
        $major_list = $this->MajorTypeModel->getMajorTitleAdd();  //获取专业列表
        return $major_list;
    }

    /*
     * 获取岗位列表
     * @return array
     * author allen
     */
    public function getCategoryList()
    {
        if(!$this->CategoryModel)
        {
            $this->CategoryModel = D('PostCategory');
        }
        $cate_list = $this->CategoryModel->getCategoryTitle();
        return $cate_list;
    }

    /*
     * 省市联动
     */
    public function provinceList()
    {
        $result = $this->RegionsModel->parentCity();
        return $result;
    }

    public function cityList()
    {
        $id = I('get.parentId');
        $result = $this->RegionsModel->childCity($id);
        $str = "<option value='0'>市</option>";
        foreach($result as $value)
        {
            $str .= "<option value=".$value['region_id']. ">" . $value['region_name'] . "</option>";
        }
        echo $str;
    }

    /*
     * @func 获取在读年级
     * @return string
     * @author allen
     */
    public function gradeInfo($grade = '')
    {
        $gradelist = $this->current_grade;
        if(!empty($grade))
        {
            $grade = explode(',',$grade);
            foreach($grade as $key => $value)
            {
                $gradeInfo[] = $gradelist[$value];
            }
            $gradeInfo = implode(',',$gradeInfo);
            return $gradeInfo;
        }
    }

    /*
     * @func 获取在读年级
     * @return string
     * @author allen
     */
    public function graduateInfo($graduate = '')
    {
        $graduatelist = $this->graduate_year;
        if(!empty($graduate))
        {
            $graduate = explode(',',$graduate);
            foreach($graduate as $key => $value)
            {
                $graduateInfo[] = $graduatelist[$value];
            }
            $graduateInfo = implode(',',$graduateInfo);
            return $graduateInfo;
        }
    }

    /*
     * 职位列表数据展示，搜索
     * author allen
     */
    public function index()
    { 
        $where = array();
        $field = array('post.pkid as id','post.title','post.week_available','post.keep_on','post.education','post.major_wish','post.salary_range','post.current_grade','post.enterprise_id','post.is_hot','post.home_show','post.order_num','post.graduate_year','post.create_time','post.refresh_time','enterprise.full_name','enterprise.telephone','post.join_activity');
        $order = 'post.order_num desc,post.create_time desc';
        $param_form = I('post.') ? I('post.') : I('get.');//获取查询字
        isset($_GET['tag']) && I('get.tag') ? $param_form['tag'] = I('get.tag') : '';
        #生成数据库查询条件  --begin--
        isset($param_form['title']) && $param_form['title'] ?  $where['post.title|enterprise.full_name'] = array('like',"%".$param_form['title']."%") : '';
        isset($param_form['education']) &&  $param_form['education'] ? $where['post.education'] = $param_form['education'] : '';
        isset($param_form['salary']) &&  $param_form['salary'] ? $where['post.salary_range'] = $param_form['salary'] : '';
        isset($param_form['active']) &&  $param_form['active'] ? $where['post.status'] = $param_form['active'] : '';
        $where['post.is_delete'] = 2;
        $where['post.create_time'] = array('neq','0000-00-00 00:00:00');
        $param_form['tag']=='offline' ? $where['post.status'] = 2 : $where['post.status'] = 1;
        # --end--

        $post_total = $this->PostModel->postTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = min($page,ceil($post_total/$this->page_number)); // 获取合法的分页数
        $post_list = $this->PostModel->postList($where,$field,$order,$page,$this->page_number);
        $map = $param_form ? $param_form : array();//生成分页连接参数
        $Page = new PageAdmin($post_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('param_form',$param_form);// 查询关键字
        $this->assign('postlist',$post_list);
        $this->assign('list',$this->infoList());
        $this->display();
    }

    /*
     * 新增页面
     * author allen
     */
    public function add()
    {
        $list = $this->infoList();
        $this->assign('list', $list);
        $this->display();
    }

    /*
     * 编辑页面
     * @param $id
     * author allen
     */
    public function modify($id)
    {
        $list = $this->infoList();
        $info = $this->PostModel->getInfo($id);  

//        #在读年级处理 --begin--
//        if(!empty($info['current_grade']))
//        {
//            if(strlen($info['current_grade']) > 1)
//            {
//                $gradeList = explode(',',$info['current_grade']);
//                foreach($gradeList as $v)
//                {
//                    $gradeInfo[$v] = $v;
//                }
//            }
//            else{
//                $gradeInfo[$info['current_grade']] = $info['current_grade'];
//            }
//            $info['gradeInfo'] = $gradeInfo;
//        }
//        #在读年级处理  --end--

        #在读年级处理 --begin--
        if(!empty($info['graduate_year']))
        {
            if(strlen($info['graduate_year']) > 1)
            {
                $graduateList = explode(',',$info['graduate_year']);
                foreach($graduateList as $v)
                {
                    $graduateInfo[$v] = $v;
                }
            }
            else{
                $graduateInfo[$info['graduate_year']] = $info['graduate_year'];
            }
            $info['graduateInfo'] = $graduateInfo;
        }
        #在读年级处理  --end--

        //获取市列表，假如存在市的ID
        if(!empty($info['province_id']))
        {
            $list['citylist'] = $this->RegionsModel->childCity($info['province_id']);
        }

        /*-------------2015-07-14 18:31:24  星期二-------------*/ 
        $major_string = $info['major_wish'];
        $major_arr = explode(',',$major_string);
        /*--------------2015-07-14 18:31:27  星期二------------*/ 

        $this->assign('major',$major_arr);
        $this->assign('info',$info);
        $this->assign('list',$list);
        $this->display();
    }

    /*
     * 变更数据为删除状态
     * @param $id
     * author allen
     */
    public function delete($id)
    {
        $result = $this->PostModel->postDelete($id);
        if($result)
        {
            $this->redirect("Post/index",'',1,'删除成功');
        }
        else {
            $this->redirect("Post/add",'',1,'删除失败');
        }
    }

    /*
     * 组织页面需求相关数组
     */
    public function infoList()
    {
        $majorlist = $this->getMajorList();
        $categorylist = $this->getCategoryList();
        $provincelist = $this->provinceList();
        $array = array(
            'majorlist' => !empty($majorlist) ? $majorlist : array('暂无添加专业'),
            'categorylist' => !empty($categorylist) ? $categorylist : array('暂无添加岗位'),
            'enterpriselist' => $this->enterprise_list,  //todo
            'weeklist' => $this->week_available,
            'edulist' => parent::getDegreeArr2(),
            'salarylist' => $this->salary_range,
            'gradelist' => $this->current_grade,
            'provincelist' => $provincelist,
            'graduatelist' => $this->graduate_year
        );
        return $array;
    }

    /*
     * 保存新增
     */
    public function save()
    {
        $data = I('param.');
        I('param.pkid') ? $data['pkid'] = I('param.pkid') : '';
        $data['is_delete'] = 2;
        $data['order_num'] = !empty($data['order_num']) ? $data['order_num'] : 0;
        $data['graduate_year'] = implode(',',$data['graduate_year']);
        $data['major_wish'] = !empty($data['major_wish'])?implode(',',$data['major_wish']):'';
        if($data['province_id'] > 650000){ //大于650000为特别行政区和海外
            $data['city_id'] = $data['province_id'];
        }
        if(isset($data['pkid']) && !empty($data['pkid']))
        {
            $data['modify_time'] = $this->nowtime;
            $result = $this->PostModel->save($data);
            $cache = $this->PostModel->postCacheDel(I('param.pkid'));//更新成功后删除缓存
        }
        else {
            $data['create_time'] = $this->nowtime;
            $result = $this->PostModel->postAdd($data);
        }
        if($result)
        {
            $this->redirect("Post/index",'',1,'保存成功');
        }
        else {
            $this->redirect("Post/add",'',1,'保存失败');
        }
    }

    /*
     * 验证数据的正确性
     */
    public function checkMessage()
    {
        $data = I('post.');
        if(!$this->PostModel->create($data))
        {
            exit(json_encode($this->PostModel->getError()));
        }
    }

    //设置职位是否参加活动
    public function setJoin(){
        if(IS_POST){
            $post = I('post.');
            $pkid_str = trim($post['data'],',');
            $pkid_arr = explode(',',$pkid_str);
            $cate_id = intval($post['id']);
            if(is_array($pkid_arr)){
                $where['pkid'] = array('in',$pkid_arr);
                $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
                $data['join_activity'] = $cate_id; 
                $bool = $this->PostModel->data($data)->where($where)->save();
                if($bool){
                    $result = array('status'=>TRUE,'msg'=>'');
                }else{
                    $result = array('status'=>FALSE,'msg'=>'add join fail');
                }
                exit(json_encode($result));               
            }
        }
    }

}

