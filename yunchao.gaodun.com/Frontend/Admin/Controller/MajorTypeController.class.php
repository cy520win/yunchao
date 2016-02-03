<?php
/**
 * Created by PhpStorm.
 * User: allen
 * Date: 2015/5/5
 * Time: 13:04
 */

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class MajorTypeController extends AdminController{


    protected $nowtime;
    protected $page_number = 10 ;// 列表数量
    protected $MajorTypeModel;

    public function _initialize()
    {
        parent::_initialize();
        $this->MajorTypeModel   = D('MajorType');
        $this->nowtime          = date('Y-m-d H:i:s',NOW_TIME);
    }



    /*
     * 加载首页
     */
    public function index()
    {
        $where = array();
        $field = array('pkid as id','title','modify_time','create_time');
        $order = 'create_time desc';
        $param_form = I('post.') ? I('post.') : I('get.');//获取查询字
        #生成数据库查询条件  --begin--
        $param_form['title'] ? $where['title'] = array('like', "%" . $param_form['title'] . "%" ) : '';
        $where['is_delete'] = 2;
        #生成数据库查询条件   --end--

        $majortypetotal = $this->MajorTypeModel->getMajorTypeTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($majortypetotal/$this->page_number)); // 获取合法的分页数
        $majortypelist = $this->MajorTypeModel->getMajorTypeList($where,$field,$order,$page,
            $this->page_number);
        $map = $param_form ? $param_form : array();//生成分页连接参数
        $Page = new PageAdmin($majortypetotal,$this->page_number,$map); //分页类实例化

        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('param_form',$param_form);// 查询关键字
        $this->assign('majortypelist',$majortypelist);
        $this->display();
    }

    /*
     * 新增页面
     */
    public function add()
    {
        $this->display();
    }


    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $info = $this->MajorTypeModel->getMajorTypeById($id);  //订阅器信息
        $this->assign('info',$info);
        $this->display();
    }

    /*
     * 变更数据为删除状态
     * @param $id
     * author allen
     */
    public function delete($id)
    {
        $result = $this->MajorTypeModel->majorTypeDelete($id);
        if($result)
        {
            $this->redirect("MajorType/index",'',1,'删除成功');
        }
        else {
            $this->redirect("MajorType/index",'',1,'删除失败');
        }
    }

    /*
     * 新增与修改
     */
    public function save()
    {
        $data = I('param.');
        I('param.id') ? $id = I('param.id') : '';
        if(isset($id) && !empty($id))
        {
            $data['modify_time'] = $this->nowtime;
            $result = $this->MajorTypeModel->majorTypeUpdate($data,$id);
        }
        else {
            $data['is_delete'] = 2;
            $data['create_time'] = $this->nowtime;
            $result = $this->MajorTypeModel->majorTypeAdd($data);
        }


        if($result)
        {
            $this->redirect("MajorType/index",'',1,'保存成功');
        }
        else {
            $this->redirect("MajorType/add",'',1,'保存失败');
        }
    }
}