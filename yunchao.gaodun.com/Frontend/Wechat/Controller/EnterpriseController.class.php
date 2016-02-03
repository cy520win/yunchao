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

namespace Wechat\Controller;
use Think\Controller;
class EnterpriseController extends Controller {

	public function __construct(){
		parent::__construct();
	}

    public function index(){
        $this->display();
    }

    // 信息完善
    public function improve_info(){
    	$this->display();
    }

    // 实习审核
    public function practice_audit(){
    	$this->display();
    }

    // 实习日志
    public function practice_log(){

    }

    //企业信息
    public function enterprise_info(){

    }
}