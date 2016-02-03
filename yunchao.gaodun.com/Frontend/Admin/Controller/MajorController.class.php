<?php
// +----------------------------------------------------------------------
// | 财经专业控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class MajorController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $MajorModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
        $this->MajorModel = D('Major'); // 财经专业表模型
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
    }

    /**
     * 校园职务列表、校园职务搜索
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        $field = array();
        $order = 'create_time desc';
        $word_form = I('post.w') ? I('post.w') : I('get.w');//获取查询字
        $word_form ? $where['title|description'] = array('like',"%".$word_form."%"): ''; //生成数据库查询条件
        $where = !empty($where) ? $where : array();
        $where['is_delete'] = 2; //状态为未删除
        $major_total = $this->MajorModel->getMajorTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($major_total/$this->page_number)); // 获取合法的分页数
        $major_data = $this->MajorModel->getMajorList($where,$field,$order,$page,$this->page_number); // 分页数据
        foreach($major_data as $k=>$v){
            $major_data[$k]['type_id'] = AdminController::getCardType($v['type_id']);
        }
        $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
        $Page       = new PageAdmin($major_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('word',$word_form);// 查询关键字
        $this->assign('major',$major_data);
    	$this->display();
    }
    
    /**
     * 加载新增专业页面
     * @author 致远<george.zou@gaodun.cn>
     */
    public function add(){
        $this->assign('type',AdminController::getCardType());
	    $this->display();
    }

    /**
     * 保存新增新增专业
     * @author 致远<george.zou@gaodun.cn>
     */
    public function save(){
        if(IS_POST){
            $data = I();
            $data['create_time'] = $this->nowtime;
            $data['modify_time'] = $this->nowtime;
            $bool = $this->MajorModel->majorAdd($data);
             if($bool){
                 $this->redirect("Major/index",'',1,'添加成功');
             }else{
                 $this->redirect("Major/add",'',1,'添加失败');
             }
        }
    }
    
    /**
     * 加载专业编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
        $field = array();
        $info = $this->MajorModel->getMajorinfo(I('get.id'),$field);
        $type = AdminController::getCardType();
        foreach ($type as $k => $v){
            $type_new['val'] = $k;
            $type_new['text'] = $v;
            if(intval($info['type_id'])==$k){
                $type_new['check'] = true; 
            }else{
                $type_new['check'] = false; 
            }
            $info['type'][] = $type_new;
        }
        $this->assign('info',$info);
	    $this->display();
    }

    /**
     * 保存更新财经专业数据
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){
	   if(IS_POST){
            $post = I();
            $post['modify_time'] = $this->nowtime;
            $bool = $this->MajorModel->majorUpdata($post,$post['major_id']);
            if($bool){
                $this->redirect("Major/index",array('id'=>$post['major_id']),1,'修改成功');
            }else{
                $this->redirect("Major/index",array('id'=>$post['major_id']),1,'修改失败');
            }
        }  
    }


    /**
     * 逻辑删除财经专业
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
    */
    public function delete(){
        if($this->MajorModel->majorDelete(I('get.id'))){
            $this->redirect("Major/index",'',1,'删除成功');
        }else{
            $this->redirect("Major/index",'',1,'删除失败');
        }
    }
}