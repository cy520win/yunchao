<?php
// +----------------------------------------------------------------------
// | 文件类型 ：控制器 模型 函数 视图模板 功能模块 类库
// +----------------------------------------------------------------------
// | 文件说明 ：消息中心
// +----------------------------------------------------------------------
// | 创建时间 ：2015-07-06 13:36:39  星期一
// +----------------------------------------------------------------------
// | 作者 : 致远<george.zou@gaodun.cn> 
// +----------------------------------------------------------------------
namespace Admin\Controller;
use Vendor\PageAdmin;
use Think\Upload;
class MessageController extends AdminController{

    protected $MessageModel;
    protected $total;
    protected $where;   
    protected $page_number = 10;
    protected $page_current = 1;
    protected $sleep_time = 10;//5分钟

    public function _initialize(){
        parent::_initialize();
        $this->MessageModel = M('message');
    }

    /*
    * @function 首页列表视图
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function index(){
        $where['is_delete'] = 2;
        $map=array();
        $field = array('pkid','msg_type','title','content','send_type','target_type','send_time','create_time');
        $word_form = trim(I('post.w') ? I('post.w') : I('get.w'));
        $date_form = trim(I('post.d') ? I('post.d') : I('get.d'));
        if($word_form){
            $where['title'] = array('like',"%".$word_form."%");
            $map['w'] = $word_form;
        }
        if($date_form){
            $where['send_time'] = array('elt',$date_form.' 23:59:59');
            $map['d'] = $date_form;
        }
        $this->total = $this->MessageModel->where($where)->count();
        $this->page_current = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) :$this->page_current;
        $$this->page_current = max(1,$this->page_current);
        $this->page_current = min($this->page_current,ceil($this->total/$this->page_number));
        $data = $this->MessageModel->field($field)->where($where)->page($this->page_current,$this->page_number)->order('pkid desc')->select();
        $page_show = new PageAdmin($this->total,$this->page_number,$map);

        $this->assign('page',$page_show->show());
        $this->assign('data',$this->eachData($data));
        $this->assign('map',$map);
        $this->display('Message:index');
    }

    public function info(){
        $msg_id = $_GET['id'] ? intval(I('get.id')) : '';
        $info = $this->MessageModel->field(array('title','content','pkid'))->find($msg_id);
        $this->assign('info',$info);
        $this->display('Message:info');
    }

    //遍历处理数据
    protected function eachData($data=''){
        if($data){
            foreach($data as $k=>$v){
                $data[$k]['msg_type'] = $this->dataText('msg_type',$v['msg_type']);
                $data[$k]['send_type'] = $this->dataText('send_type',$v['send_type']);
                $data[$k]['target_type'] = $this->dataText('target_type',$v['target_type']);
            }
            return $data;
        }
    }

    protected function dataText($type,$key){
        $txt = array(
                'target_type' => array(1=>'企业',2=>'学生',3=>'企业和学生'),
                'send_type' => array(1=>'及时发送',2=>'定时发送'),
                'msg_type' => array(1=>'官方公告',2=>'系统提醒')
            );
        return $txt[$type][$key];
    }

    /*
    * @function 加载新增邮件视图
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function add(){
        for($i=0;$i<24;$i++){
            $len = strlen($i);
            if($len==1){
                $hour[] = '0'.$i;
            }else{
                $hour[] = "{$i}";
            }
        }
        for($i=0;$i<60;$i++){
            $len = strlen($i);
            if($len==1){
                $minute[] = '0'.$i;
            }else{
                $minute[] = "{$i}";
            }
        }
        $date['hour'] = $hour;
        $date['minute'] = $minute;
        $this->assign('date',$date);
        $this->display('Message:add');
    }

    /*
    * @function 保存新增邮件数据
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function save(){
        if(IS_POST && $_POST){
            $post = I('post.');
            $bool = TRUE;
            if($post['send_type']==2){
                $post['send_time'] = $post['send_time_date']['d'].' '.$post['send_time_date']['h'].':'.$post['send_time_date']['m'].':00';
                if(strtotime($post['send_time']) < NOW_TIME){
                    $bool = FALSE;
                }
            }else{
                $post['send_time'] = date('Y-m-d H:i:s',NOW_TIME);
            }
            unset($post['send_time_date']);
            foreach($post as $k=>$v){
                if(!$v){
                    unset($post);
                    unset($_POST);
                    $bool = FALSE;
                    break; 
                }
            }
            if(!$bool){
                redirect('admin.php?s=/Admin/Message/add',2,'输入*必填项或发送时间设置错误');
                exit();
            }
            $result = $this->saveMsg($post);
            if($result['status']){
                    $bool = $this->msgAddQueue($result['data'],$post['target_type']);
                    if($bool){
                        redirect('admin.php?s=/Admin/Message/index',2,'操作成功');
                    }else{
                        redirect('admin.php?s=/Admin/Message/index',2,'操作失败');
                    }
            }else{
                redirect('admin.php?s=/Admin/Message/add',2,'操作失败');
            }
        }
    }

    /*
    * @function 将消息写入消息队列
    * @param int:$msg_id 消息主键PKID
    * @param int:$account_type {1:企业;2:学生;3:企业和学生}
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function msgAddQueue($data='',$account_type=3){
        $account_type == 3 ? $account_type='1 OR 2' : '';
        $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $data['status'] = 1 ;
        $data['is_delete'] = 2;
        $sql ="INSERT INTO `message_queue` (`msg_id`,`create_time`,`modify_time`,`status`,`is_delete`,`title`,`content`,`send_time`,`account_id`) SELECT {$data[msg_id]},'{$data[create_time]}','{$data[modify_time]}',{$data[status]},{$data[is_delete]},'{$data[title]}','{$data[content]}','{$data[send_time]}',account.pkid FROM account WHERE account.valid_status='1' AND account.is_delete='2' AND account.account_type={$account_type}";
        return M()->execute($sql);
    }

    /*
    * @function 新增保存消息
    * @param array:$data
    * @param int:$msg_id，消息主键PKID，默认NULL,表示新增，否则为编辑
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    protected function saveMsg($data){
            $data['msg_type'] = 1 ;
            $data['is_delete'] = 2 ;
            $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $last_id = M('message')->data($data)->add(); 
                $return['title'] = $data['title'];
                $return['content'] = $data['content'];
                $return['msg_id'] = $last_id > 0 ? $last_id : '';
                $return['send_time'] = $data['send_time'];
            $result['status'] = $last_id > 0 ? TRUE : FALSE ;
            $result['data'] = $return;
            return $result; 
    }


    /**
     * 上传头像、图片
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function loadAttach(){
        $upload = new Upload();
        $upload->rootPath  =  'Public/Upload/attached/';
        $upload->exts = array('jpeg','jpg','png','bmp');
        $upload->maxSize = 1024*1024*10;
        dir_create($upload->rootPath);
        $head = $upload ->uploadOne($_FILES['imgFile']);
        $error = $upload->getError();
        if($head){
            $path = C('APP_URL').'/'.$upload->rootPath.$head['savepath'].$head['savename'];
            $data = array('error' =>0,'url' =>$path);    
            exit(json_encode($data));        
        }else{
            $data = array('error' =>1,'msg'=>$error,'data'=>$_FILES['imgFile']['name']); 
            dump($data);exit();
        }
    }

    /*
    * @function 删除消息
    * @param 
    * @return 
    * yangfuyi@gaodun.cn 
    */
    public function delete(){
        $id = $_GET['id'] ? intval(I('get.id')) : '';
        $data['is_delete'] = 1;
        $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $result =  M('message')->data($data)->where(array('pkid'=>$id))->save();
        if($result){
            $bool = M('message_queue')->where(array('msg_id'=>$id))->delete();
            if($bool){
                redirect('admin.php?s=/Admin/Message/index',1,'删除成功');
            }else{
                redirect('admin.php?s=/Admin/Message/index',1,'删除失败');
            }            
        }else{
            redirect('admin.php?s=/Admin/Message/index',1,'删除失败');
        }
    }
}