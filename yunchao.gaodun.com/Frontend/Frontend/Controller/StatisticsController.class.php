<?php
// +----------------------------------------------------------------------
// | 前台首页控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-08 14:44:55 ，最后更新 2015-06-05 10:06:30  星期五
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------
namespace Frontend\Controller;
class StatisticsController extends CommonController {

	public function _initialize(){
		parent::_initialize();
	}

    /*
    * @author 致远<george.zou@gaodun.cn>    
    * @remark 最后更新时间
    */
    public function index(){
    	$this->display('index');
    }
}