<?php
// +----------------------------------------------------------------------
// | 企业规模控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class ScaleController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $ScaleModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
        $this->ScaleModel = D('Scale'); // 初始化证书表模型
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
    }

    /**
     * 企业规模列表、企业规模搜索
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
        $Scale_total = $this->ScaleModel->getScaleTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($Scale_total/$this->page_number)); // 获取合法的分页数
        $Scale_data = $this->ScaleModel->getScaleList($where,$field,$order,$page,$this->page_number); // 分页数据
        $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
        $Page       = new PageAdmin($Scale_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('word',$word_form);// 查询关键字
        $this->assign('scale',$Scale_data);
    	$this->display();
    }
    
    /**
     * 加载新增企业规模页面
     * @author 致远<george.zou@gaodun.cn>
     */
    public function add(){
	    $this->display();
    }

    /**
     * 保存新增新增企业规模
     * @author 致远<george.zou@gaodun.cn>
     */
    public function save(){
        if(IS_POST){
            $data = I();
            $data['create_time'] = $this->nowtime;
            $data['modify_time'] = $this->nowtime;
            $bool = $this->ScaleModel->ScaleAdd($data);
            if($bool){
                $this->redirect("Scale/index",'',1,'新增成功');
            }else{
                $this->redirect("Scale/index",'',1,'新增失败');
            }
        }
    }
    
    /**
     * 加载规模编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
        $field = array();
        $this->assign('info',$this->ScaleModel->getScaleinfo(I('get.id'),$field));
	    $this->display();
    }

    /**
     * 更新规模
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){
	   if(IS_POST){
            $post = I();
            $post['modify_time'] = $this->nowtime;
            $bool = $this->ScaleModel->ScaleUpdata($post,$post['scale_id']);
            if($bool){
                $this->redirect("Scale/index",array('id'=>$post['scale_id']),1,'修改成功');
            }else{
                $this->redirect("Scale/index",array('id'=>$post['scale_id']),1,'修改失败');
            }
        }  
    }


    /**
     * 逻辑删除证书
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
    */
    public function delete(){
        if($this->ScaleModel->ScaleDelete(I('get.id'))){
            $this->redirect("Scale/index",'',1,'删除成功');
        }else{
            $this->redirect("Scale/index",'',1,'删除失败');
        }
    }
}