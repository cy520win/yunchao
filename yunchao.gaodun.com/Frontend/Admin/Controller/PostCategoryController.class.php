<?php
// +----------------------------------------------------------------------
// | 岗位控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class PostCategoryController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $nowtime;
    protected $industry_list;
    protected $PostCategoryModel;

    public function _initialize(){
	    parent::_initialize();
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
        $this->industry_list = $this->getBusIndustry(); //行业类型
        $this->PostCategoryModel =  D('PostCategory'); //岗位分类表模型
    }

    /**
     * 分类列表
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $result = $this->PostCategoryModel->getPostAll();
        $this->assign('cate',$result);
    	$this->display();
    }

    public function english(){
        $result = $this->PostCategoryModel->getPostAll();
        $this->assign('cate',$result);
        $this->display('index_english');
    }

    public function englishSave(){
        if(IS_POST){
            $data = I('post.title_en');
            foreach ($data as $k=>$v){
                $bool = $this->PostCategoryModel->setCategoryEnglish(intval($k),$v);
                if(!$bool){
                    redirect('/admin.php?s=/Admin/PostCategory/english',2,'设置失败');
                }
                $bool_arr[] = $bool;
            }
            if(!empty($bool_arr)){
                redirect('/admin.php?s=/Admin/PostCategory/english',2,'设置成功');
            }else{
                redirect('/admin.php?s=/Admin/PostCategory/english');
            }
        }
    }
    
    /**
     * 保存分类数据
     * @author 致远<george.zou@gaodun.cn>
     */
    public function add(){
	    if(IS_POST){
            $status = 'unknow';
            $msg = '未知错误';
            //检查同级分类下的名称唯一性
            $where_three['title'] = I('post.t');
            $where_three['parent_id'] = array('eq',I('post.p'));
            $where_three['pkid'] = array('neq',I('post.v'));
            $bool = $this->PostCategoryModel->checkChildeName($where_three);
            if($bool>0){
                exit(json_encode(array('msg'=>'同级分类名重复，操作失败','status'=>false)));
            };

            //修改分类
            if(!empty($_POST['v'])){
                $data_two['title'] =  I('post.t');
                $data_two['modify_time'] = $this->nowtime;
                $where_two['pkid'] = I('post.v'); 
                $bool = $this->PostCategoryModel->postUpdate($data_two,$where_two);
                if($bool){
                    $msg = '修改成功';
                    $status = true;
                }else{
                    $msg = '修改失败';
                    $status = false;
                }
            }
            //添加分类
            if(empty($_POST['v'])){
                $data_new['title'] = I('post.t'); //分类名
                $data_new['parent_id'] = I('post.p');//分类父类pkid
                $data_new['create_time'] = $this->nowtime;
                $data_new['modify_time'] = $this->nowtime;
                $bool = $this->PostCategoryModel->postAdd($data_new);
                if($bool){
                    $msg = '添加成功';
                    $status = true;
                }else{
                    $msg = '添加失败';
                    $status = false;
                }          
            }
            exit(json_encode(array('status'=>$status,'msg'=>$msg)));
        }
    }

    /**
     * 逻辑删除分类数据
     * @author 致远<george.zou@gaodun.cn>
     */
    public function del(){
        if(IS_POST){
            $result = $this->PostCategoryModel->postDelete(I('post.v'));
            exit(json_encode($result));
        }
    }
}