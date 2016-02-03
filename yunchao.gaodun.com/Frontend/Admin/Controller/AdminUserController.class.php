<?php
// +----------------------------------------------------------------------
// | 运营后台管理员功能控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Crypt as Crypt;
use Think\Upload as Upload;
use Vendor\PageAdmin as PageAdmin;
class AdminUserController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $AdminUserModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
      $this->AdminUserModel = D('AdminUser'); // 初始化管理员账户模型
      $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
    }

    /**
     * 管理员展示列表、搜索列表
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
      $where = array();
      $field = array();
      $order = 'create_time desc';
      $word_form = I('post.w') ? I('post.w') : I('get.w');//获取查询字
      $word_form ? $where['truename'] = array('like',"%".$word_form."%"): ''; //生成数据库查询条件
      $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';
      $where = !empty($where) ? $where : true;
      $admin_total = $this->AdminUserModel->getAdminTotal($where);//列表总数
      $page = max(1,$page);
      $page = min($page,ceil($admin_total/$this->page_number));
      $admin_data = $this->AdminUserModel->getAdminList($where,$field,$order,$page,$this->page_number); // 分页数据
      $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
      $Page = new PageAdmin($admin_total,$this->page_number,$map);
      $this->assign('page',$Page->show());// 分页显示输出
      $this->assign('word',$word_form);// 分页显示输出
      $this->assign('adminlist',$admin_data);
	    $this->display();
    }
    
    /**
     * 显示管理员添加页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function add(){
	     $this->display();
    }

    /**
     * 执行管理员添加
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function save(){
      if(IS_POST){
          $data['login_email'] = I('post.mail');
          $data['login_pass'] = MD5(Crypt::encrypt(I('post.password')));
          $data['truename'] = I('post.username');
          $data['avatar'] = I('post.headpic');
          $data['mobile'] = I('post.mobile');
          $data['valid_status'] = I('post.status');
          $data['create_time'] = $this->nowtime;
          $data['last_login'] = $this->nowtime;
          $data['modify_time'] = $this->nowtime;
          $data['last_ip'] = get_client_ip();
          $bool = $this->AdminUserModel->adminAdd($data);
          if($bool){
              $this->redirect("AdminUser/index",'',1,'添加成功');
          }else{
              $this->redirect("AdminUser/add",'',1,'添加失败');
          } 
      }
    }
    
    /**
     * 显示管理员编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
      $field = array('pkid','login_email','truename','mobile','valid_status','avatar');
      $this->assign('info',$this->AdminUserModel->getAdmininfo(I('get.id'),$field));
	    $this->display();
    }

    /**
     * 逻辑删除
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function delete(){
        $data = $this->AdminUserModel->adminDelete(I('get.id'));
        if($data){
            $this->redirect("AdminUser/index",'',1,'删除成功');
        }else{
            $this->redirect("AdminUser/index",'',1,'删除失败');
        }
         
    }

    /**
     * 管理员信息更新
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){
  	    if(IS_POST){
            $id = I("post.user_id");
            $data['login_email'] = I('post.mail');
            $data['truename'] = I('post.username');
            $data['avatar'] = I('post.headpic');
            $data['mobile'] = I('post.mobile');
            $data['valid_status'] = I('post.status');
            $data['modify_time'] = $this->nowtime;
            $password = I('post.password');
            if(!empty($password)){
                $data['login_pass'] = MD5(Crypt::encrypt(I('post.password')));
            }
            $bool = $this->AdminUserModel->adminUpdata($data,I("post.user_id"));
            if($bool){
                $this->redirect("AdminUser/index",'',3,'修改成功');
            }else{
                $this->redirect("AdminUser/edit",array('id'=>$id),3,'修改失败');
            }
        }
    }

    /**
     * 上传头像、图片
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function uphead(){
        $upload = new Upload();// 实例化上传类
        $upload->rootPath  =  'Public/Upload/headpic/adminuser/'; // 设置附件上传根目录
        dir_create($upload->rootPath);//创建文件夹
        $head = $upload ->uploadOne($_FILES['headpic']);
        $path = $upload->rootPath.$head['savepath'].$head['savename'];
        $data = array('msg'=>$path,'error'=>'');
        exit(json_encode($data));
   
    }

    /**
     * ajax 验证新增管理员数据
     * @author 致远<george.zou@gaodun.cn>
     */
    public function beforeCheckAdd(){
      if(IS_POST){
        $post = I();
        $result = $this->AdminUserModel->checkAdminAdd($post);
        exit(json_encode($result));
      }
    }

    /**
     * ajax 验证管理员邮箱
     * @author 致远<george.zou@gaodun.cn>
     */
    public function checkMail(){
      if(IS_POST){
          $email = I('post.mail');
          $uid = I('post.uid');
          $bool = $this->AdminUserModel->checkUpdataMailUnique($email,$uid);
          exit(json_encode($bool));
      }
     
    }
}