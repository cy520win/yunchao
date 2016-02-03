<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/5/7
 * Time: 18:19
 */

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;

class HotCityController extends AdminController{

    protected $HotCityModel;    //热门城市
    protected $page_number;

    public function _initialize()
    {
        parent::_initialize();
        $this->HotCityModel = D('HotCity');
        $this->page_number = 10;
    }


    /*
    * 一级省与二级市的列表
    */
    public function provinceList()
    {
        $result = D('Regions')->parentCity();
        return $result;
    }

    public function cityList()
    {
        $result = D('Regions')->getCityList();
        return $result;
    }

    /*
     * 列表页
     */
    public function index()
    {
        $where = array();
        $field = array('pkid as id','region_id','region_name','order_num');

        I('post.region_name') ? $where['region_name'] = array('like',"%". I('post.region_name') ."%") : '';

        $where['is_delete'] = '2';
        $hotCityTotal = $this->HotCityModel->getHotCityTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($hotCityTotal/$this->page_number)); // 获取合法的分页数
        $hotCityList = $this->HotCityModel->getHotcityList($where,$field,$page,$this->page_number);
        $Page = new PageAdmin($hotCityTotal,$this->page_number,$where); //分页类实例化

        $this->assign('where',I('post.'));
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('hotCityList',$hotCityList);
        $this->display();
    }

    /*
     * 新增页面
     */
    public function add()
    {
        $provinceList = $this->provinceList();
        $this->assign('provinceList',$provinceList);
        $this->display();
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $result = $this->HotCityModel->find($id);
        $this->assign('info',$result);
        $this->display();
    }

    //新增热门城市
    public function save()
    {
        $data = I('post.');
        $cityList = $this->cityList();
        switch($data['region_id'])
        {
            case '120200':
                $data['region_name'] = '天津市';
                break;
            case '110200':
                $data['region_name'] = '北京市';
                break;
            case '310200':
                $data['region_name'] = '上海市';
                break;
            case '500200':
                $data['region_name'] = '重庆市';
                break;
            default:
                $data['region_name'] = $cityList[$data['region_id']];
                break;
        }
        $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $result = $this->HotCityModel->hotCityAdd($data);

        if($result !== false) {
            $this->redirect('HotCity/index','',1,'保存成功');
        } else{
            $this->redirect('HotCity/index','',1,'保存失败');
        }
    }

    /*
     * 更新热门城市序号
     */
    public function update()
    {
        $data = I('post.');
        $result = $this->HotCityModel->where(array('pkid' => $data['id']))->field('order_num')->data(array
        ('order_num' => $data['order_num']))
            ->save();

        if($result !== false) {
            $this->redirect('HotCity/index','',1,'更新成功');
        } else{
            $this->redirect('HotCity/index','',1,'更新失败');
        }
    }

    //删除热门城市
    public function delete($id)
    {
        $result = $this->HotCityModel->field('is_delete')->data(array('is_delete' => 1))->where(array('pkid' =>
                $id))->save();
        if($result !== false){
            $this->redirect('HotCity/index','',1,'删除成功');
        }else{
            $this->redirect('HotCity/index','',1,'删除失败');
        }
    }

    //验证重复
    public function checkExist()
    {
        $region_id = I('post.region_id');
        if($this->HotCityModel->where(array('region_id' => $region_id,'is_delete' => 2))->find()){
            exit(json_encode(array('result' => 'exist')));
        }else{
            exit(json_encode(array('result' => 'empty')));
        }
     }
    
}