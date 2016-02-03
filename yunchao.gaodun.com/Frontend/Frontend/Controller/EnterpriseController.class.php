<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：
// +----------------------------------------------------------------------
// | 创建时间 ：
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Frontend\Controller;
use Think\Controller;
use Vendor\PageAdmin;
class EnterpriseController extends CommonController{


    protected $EnterpriseModel; //企业model
    protected $RegionsModel;    //省市model
    protected $TraceSetupController; //推送控制层
    protected $PostModel;       //岗位model
    protected $page_number = 9;
    protected $trace_id = array('invitation_accept' => 2,'resume_post' => 14,'accept_after' => 8,'delivery_wait' => 11);

    public function __construct(){
        parent::__construct();
        $this->EnterpriseModel  = D('Enterprise');
        $this->RegionsModel     = D('Regions');
        $this->PostModel        = D('Post');
        $this->TraceSetupController = A('TraceSetup');
    }

    /*
     * 获取企业详细信息涵括新增与展示页
     * @return array
     * @author allen
     */
    public function index()
    {
        $account = session('account');  //登陆的用户相关信息
        if(!$account) {
            redirect('/Account/logout');
            exit;
        }
        if(!self::checkType()){
            exit;
        };
        $info = $this->EnterpriseModel->getInfoById($account['enterprise_id']);
        $contact_info = $this->EnterpriseModel->getContactInfo();
        if($contact_info){
            $this->assign('contact',$contact_info);
        }
        if($info['email_verify'] != 1){
            $this->assign('verify','not');
        }
        $where['post.enterprise_id'] = $account['enterprise_id'];
        $where['post.status'] = 1;
        #岗位列表  --begin--
        $postTotal = $this->PostModel->getPostTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($postTotal/$this->page_number)); // 获取合法的分页数
        $post = $this->PostModel->getPostList($where,$page,$this->page_number);
        $map = array(
            'enterprise_id' => enInt($account['enterprise_id']),
        );
        $Page = new PageAdmin($postTotal,$this->page_number,$map); //分页类实例化
        $this->assign('postList',$post);
        $this->assign('page',$Page->show());
        $this->assign('app_url',C('APP_URL'));
        $this->assign('list',$this->infoList());

        if(empty($info['full_name'])){
            $this->assign('welfareList',parent::getWelfareList());
            $this->assign('no_post',1);
            $this->display('add');
        }else{
            $info['edit_auth'] = 1;
            $info['account_type'] = session('account.account_type');
            $info['pkid'] = enInt($info['pkid']);
            $this->assign('info',$info);
            if(isMobile()){
                $this->display('/MobileEnterprise/info');
                exit;
            }
            $this->display();
        }
    }

    /*
     * 企业列表页
     */
    public function lists()
    {

        $where = array();
        $param = I('get.') ? I('get.') : I('post.');//获取查询字
        #生成数据库查询条件  --begin--
        isset($param['ct']) ? $where['city_id'] = $param['ct'] : '';
        isset($param['in']) ? $where['industry_id'] = $param['in'] : '';
        isset($param['si']) ? $where['scale_id'] = $param['si'] : '';

        # --end--
        //$where['approve_status'] = 1;//认证状态
        $where['approve_status'] = array('gt',0);//认证状态暂时不做判断
        
        $enterpriseTotal = $this->EnterpriseModel->getTotalOfEnterprise($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($enterpriseTotal/$this->page_number)); // 获取合法的分页数
        $enterpriseList = $this->EnterpriseModel->getListOfEnterprise($where,$page,$this->page_number);

        $map = $param ? $param : array();//生成分页连接参数
        $Page = new PageAdmin($enterpriseTotal,$this->page_number,$map); //分页类实例化

        //热门城市，前5个
        $hotCityList = M('HotCity')->where(array('is_delete' => 2))->order('order_num desc')
            ->limit(5)->getField('region_id,region_name');
        foreach($enterpriseList as $key => $value){
            if(mb_strlen($value['city_name'],'utf8') >= 3){
                $enterpriseList[$key]['city_name'] = mb_substr($value['city_name'],0,mb_strlen($value['city_name'],'utf8') - 1,'utf8');
            }
            $enterpriseList[$key]['logo'] = imageLogoExist(imageExist($value['logo']));
        }

        if(!empty($param['ct'])){
            $result = D('Regions')->field('region_name')->where(array('region_id' => $param['ct']))->find();
            $param['ct'] = $result['region_name'];
        }

        if(empty($param['ct']) && empty($param['in']) && empty($param['si'])){
            $param = array();
        }
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('param',$param);// 查询关键字
        $this->assign('industryList',parent::getIndustryList());
        $this->assign('app_url',C('APP_URL'));
        $this->assign('hotCityList',$hotCityList);
        $this->assign('enterpriseList',$enterpriseList);
        $this->assign('scaleList',$this->getScaleList());
        if(!empty($_GET['p'])){
            $this->assign('p',$_GET['p']);
        }
        if(isMobile()){
            redirect('/MobileEnterprise/lists');
        }
        $this->display('lists');
    }


    /*
     * 详细信息展示页
     */
    public function info(){
        // $id = I('get.id') ? I('get.id') : I('get.enterprise_id');
        // if(!is_numeric($id)){
        //     $id = enInt($id);
        // }
        // /*增加判断id是否为数字  2016-01-07*/
        // if(!is_numeric($id)){
        //     redirect('/Enterprise/lists');
        // }
        // $where['enterprise_id'] = $id;
        // $where['status'] = 1;
        // $info = $this->EnterpriseModel->getInfoById($id);
        // $info['logo'] = imageExist($info['logo']);

        // #岗位列表  --begin--
        // $postTotal = $this->PostModel->getPostTotal($where);//列表总数量
        // $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        // $page = max(1,$page);
        // $page = min($page,ceil($postTotal/$this->page_number)); // 获取合法的分页数
        // $post = $this->PostModel->getPostList($where,$page,$this->page_number);
        // $map = array(
        //     'enterprise_id' => enInt($id),
        // );
        // $Page = new PageAdmin($postTotal,$this->page_number,$map); //分页类实例化
        // #岗位列表   --end--

        // #热门企业  --begin--
        // // $hotEnterprise = $this->EnterpriseModel->getListOfEnterprise(true,1,4);
        // $hotEnterprise = $this->PostModel->hotEnterprise(intval($id));
        // foreach($hotEnterprise as $k=>$v){
        //     $hotEnterprise[$k]['logo'] = imageExist($v['logo']);
        // }
        // #热门企业   --end--

        // $enterprise_id = session('account.enterprise_id');
        // //使用同页面查看详情，判断是否有编辑的权限
        // if($id == $enterprise_id){
        //     $info['edit_auth'] = 1;
        // }

        // if(isMobile()){
        //     $post = $this->PostModel->getPostList($where,$page,$postTotal);
        // }

        // // 修正企业主页链接
        // $info['website'] = $info['website'] ? pregHttp($info['website']) : '';

        // $info['account_type'] = session('account.account_type');
        // $this->assign('hotEnterprise',$hotEnterprise);
        // $this->assign('postList',$post);
        // $this->assign('page',$Page->show());
        // $this->assign('app_url',C('APP_URL'));
        // $this->assign('list',$this->infoList());
        // $this->assign('info',$info);

        $this->display('info');

    }


    //省市联动关联函数
    public function getProvinceList()
    {
        return $this->RegionsModel->parentCity();
    }

    /*
     * 联合数组方便输出
     */
    public function infoList()
    {
        $list = array(
            'industryList'  => parent::getIndustryList(),
            'scaleList'     => parent::getScaleList(),
            'categoryList'  => parent::getCategoryList(),
            'welfareList'   => parent::getWelfareList(),
            'provinceList'  => self::getProvinceList(),
            'eduList'       => getDegreeText(null),
            'rangeList'     => getDaySalaryText(null)
        );
        return $list;
    }

    /*
     * 福利选项
     */
    public function welfareList($welfare_list,$enterprise_id){
        $data = false;
        if(!empty($welfare_list)){
            if(is_array($welfare_list)){
                foreach($welfare_list as $item){
                    $data[] = array(
                        'enterprise_id' => $enterprise_id,
                        'welfare_id'    => $item,
                        'create_time'   => date('Y-m-d H:i:s',NOW_TIME)
                    );
                }
            }
        }
        return $data;
    }

    //获取城市ID
    public function getCityId($name){
        $id = false;
        if(!empty($name)){
            $result = $this->RegionsModel->field('region_id')->where(array('region_name' => array('like',"%" .$name .
            "%"),'region_type' => 2))
                ->find();
            $id = $result['region_id'];
        }
        return $id;
    }

    //获取热门城市
    public function hotcity(){
        $data = $this->getHotCityList();
        foreach ($data as $v) {
            $result[] = $v['region_name'];
        }
        exit(json_encode($result));
    }
}