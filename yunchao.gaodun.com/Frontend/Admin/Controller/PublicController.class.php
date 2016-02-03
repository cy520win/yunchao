<?php
// +----------------------------------------------------------------------
// | 管理员未登陆状态公共控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-08 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------


namespace Admin\Controller;
use Think\Controller;
/**
 * 
 * @author 致远<george.zou@gaodun.cn>
 */
class PublicController extends Controller {

    /**
     * 管理员用户登录
     * @author 致远<george.zou@gaodun.cn>
     */
    public function login(){
      if(session('login')){
          $this->redirect('Student/index');
      }else{
          $this->display();
      }
    }

     /**
     * 管理员安全退出
     * @author 致远<george.zou@gaodun.cn>
     */
    public function logout(){
        AdminController::logout();
    }

    
    /**
     * 验证管理员登陆账户密码
     * @author 致远<george.zou@gaodun.cn>
     */
    public function checkLogin(){
      if(IS_POST){
          $data['username'] = I('post.username');
          $data['passwd'] = I('post.passwd');
          $result = D('AdminUser')->checkAdminLogin($data);
          if($result['status']=='true'){
              session('admin_id',$result['data']['pkid']);
              session('admin_name',$result['data']['truename']);
              session('admin_headpic',$result['data']['avatar']);
              session('login',true);
              session('hash',md5(Sha1($result['data']['pkid'],true)));
          }
          exit(json_encode($result));
      }
    }
}
?>