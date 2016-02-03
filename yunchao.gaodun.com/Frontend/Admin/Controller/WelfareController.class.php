<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/5/8
 * Time: 10:49
 */

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;

class WelfareController extends AdminController{

    protected $WelfareModel;    //福利模型
    protected $page_number;

    public function _initialize()
    {
        parent::_initialize();
        $this->WelfareModel = D('Welfare');
        $this->page_number = 10;
    }

    /*
     * 首页展示
     */
    public function index()
    {
        $where = true;
        $field = array('pkid as id','title','create_time');
        $where['status'] = 1;

        $welfareTotal = $this->WelfareModel->getWelfareTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($welfareTotal/$this->page_number)); // 获取合法的分页数
        $welfareList = $this->WelfareModel->getWelfareList($where,$field,$page,$this->page_number);
        $Page = new PageAdmin($welfareTotal,$this->page_number); //分页类实例化

        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('welfareList',$welfareList);
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
        $result = $this->WelfareModel->find($id);
        $this->assign('info',$result);
        $this->display();
    }

    /*
     * 检查数据的情况
     */
    public function checkMessage()
    {
        $data = I('post.');
        $title = I('post.title');
        if(empty($title)){
            exit(json_encode(array('title' => '名称不能为空')));
        }
        if(isset($data['pkid']) && !empty($data['pkid'])){
            $result = $this->WelfareModel->where($data)->find();
            if(!empty($result)){
                return true;
            }
        }
        if($this->WelfareModel->where(array('title' => $data['title']))->find())
        {
            exit(json_encode(array('title' => '名称不能重复')));
        }
        if(strlen($title)>5*3){
            exit(json_encode(array('title' => '名称不能超过5个中文')));
        }
    }

    /*
     * 保存数据
     */
    public function save()
    {
        $data = I('post.');
        if(!empty($data['pkid']) && isset($data['pkid']))
        {
            $result = $this->WelfareModel->data($data)->save();
        }
        else{
            $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $result = $this->WelfareModel->data($data)->add();
        }
        if($result !== false)
        {
            $this->redirect('Welfare/index','',1,'保存成功');
        }
        else{
            $this->redirect('Welfare/index','',1,'保存失败');
        }
    }

    /*
     * 删除福利
     */
    public function delete($id)
    {
        $result = $this->WelfareModel->delete($id);
        if($result)
        {
            $this->redirect('Welfare/index','',1,'删除成功');
        }
        else{
            $this->redirect('Welfare/index','',1,'删除失败');
        }
    }

    //删除前确认
    public function checkDelete()
    {
        $id = I('post.id');
        $info = M('EnterpriseWelfare')->where(array('welfare_id' => $id,'is_delete' => 2))->select();
        if(!empty($info))
        {
            exit(json_encode(array('msg' => '该福利有企业正在使用,是否确认删除？')));
        }
        else{
            exit(json_encode(array('msg' => 0)));
        }
    }
}