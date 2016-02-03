<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/5/8
 * Time: 10:49
 */

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class TagController extends AdminController{

    protected $TagModel;    //标签模型
    protected $page_number;

    public function _initialize()
    {
        parent::_initialize();
        $this->TagModel = D('Tag');
        $this->page_number = 10;
    }

    /*
     * 首页展示
     */
    public function index(){   
        $field = array('pkid as id','title','type','range','create_time','modify_time','is_delete');
        $param_form = I('post.') ? I('post.') : I('get.');//获取查询字
        I('get.tag') ? $param_form['tag'] = I('get.tag') : '';
        $param_form['title'] ?  $where['title'] = array('like',"%".$param_form['title']."%") : '';
        $param_form['range'] ? $where['range'] = array('like',"%".$param_form['range']."%"): '';
        $param_form['tag']=='user' ? $where['type'] = 2 : $where['type'] = 1;
        $begin_time = trim(I('post.time') ? I('post.time') : I('get.time'));  
        $end_time = trim(I('post.end') ? I('post.end') : I('get.end'));  
        if(!empty($begin_time)&&!empty($end_time)){            
            $where['create_time'] = array(array('egt',$begin_time.' 00:00:00'),array('elt',$end_time.' 23:59:59'),'and'); 
        }
        if(!empty($begin_time)&&empty($end_time)){           
            $where['create_time'] = array('egt',$begin_time.' 00:00:00'); 
        }  
        if(empty($begin_time)&&!empty($end_time)){
            $where['create_time'] = array('elt',$end_time.' 23:59:59');  
        }  
        $where['is_delete'] = 2;        
        $tagTotal = $this->TagModel->getTagTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($tagTotal/$this->page_number)); // 获取合法的分页数
        $tagList = $this->TagModel->getTagList($where,$field,$page,$this->page_number);
        if(!empty($tagList)){
            foreach ($tagList as $key => $value) {                
                switch ($value['type']) {
                    case '2':
                        if($value['range']==1){
                            $tagList[$key]['typer'] = D('Enterprise')->where(array('account_id'=>$value['account_id']))->getField('full_name');
                        } 
                        if($value['range']==2){
                            $tagList[$key]['typer'] = D('Enterprise')->where(array('account_id'=>$value['account_id']))->getField('full_name');
                        }
                        if($value['range']==3){
                            $tagList[$key]['typer'] = D('Student')->where(array('account_id'=>$value['account_id']))->getField('name');
                        }
                        if($value['range']==4){
                            $tagList[$key]['typer'] = D('Student')->where(array('account_id'=>$value['account_id']))->getField('name');
                        }                       
                        break;
                    
                    default:
                        if($value['account_id']==0){
                            $tagList[$key]['typer'] = "系统";
                        }
                        break;
                }
                if(strpos($value['range'],',')){
                  $rangeArr = explode(',',$value['range']);
                  $bb = "";
                  foreach ($rangeArr as $k => $v) {
                    if($v==1){
                        $bb.= "职位,"; 
                    }
                    if($v==2){
                        $bb.= "企业,"; 
                    }
                    if($v==3){
                        $bb.= "简历,"; 
                    }
                    if($v==4){
                        $bb.= "学生,"; 
                    }
                  } 
                  $aa  = rtrim($bb,','); 
                  $tagList[$key]['rangeInfo'] = $aa;                 
                }else{
                  switch ($value['range']) {
                        case '1':
                            $cc = "职位"; 
                        break;
                        case '2':
                            $cc = "企业"; 
                        break;                                     
                        case '3':
                            $cc = "简历"; 
                        break; 
                        case '4':
                            $cc = "学生"; 
                        break; 
                    }
                    $tagList[$key]['rangeInfo'] = $cc;                  
                }
            }    
        } 
        $map = $param_form ? $param_form : array();//生成分页连接参数
        $Page = new PageAdmin($tagTotal,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('param_form',$param_form);// 查询关键字
        $this->assign('tagList',$tagList); 
        $this->assign('rangeList',AdminController::getRange());          
        $this->display();
    }    

    /*
     * 新增页面
     */
    public function add(){
        $this->display();
    }

    /*
     * 编辑页面
     */
    public function edit($id){      
        $info = $this->TagModel->find($id);        
        if(strpos($info['range'],',')){
          $rangeArr = explode(',',$info['range']);
          $arr = array();
          foreach ($rangeArr as $key => $value) {
            $arr[$value] = $value;
          } 
          $this->assign('rangeArr',$arr);
        }else{
          $range[] = $info['range'];
          $this->assign('range',$range);        
        }       
        $this->assign('info',$info);        
        $this->display();
    }

    /*
     * 检查数据的情况
     */
    public function checkMessage(){
        $data = I('post.');
        $title = I('post.title');
        $range = I('post.range');
        if(empty($title)){
            exit(json_encode(array('title' => '标签名称不能为空')));
        }
        if(empty($range)){
            exit(json_encode(array('range' => '标签范围必须至少选中一项')));
        }        
        if(isset($data['pkid']) && !empty($data['pkid'])){
            $result = $this->TagModel->where($data)->find();
            if(!empty($result)){
                return true;
            }
        }
        if($this->TagModel->where(array('title' => $data['title']))->find())
        {
            exit(json_encode(array('title' => '标签名称不能重复')));
        }
        if(strlen($title)>10*3){
            exit(json_encode(array('title' => '标签名称不能超过10个中文')));
        }
    }

    /*
     * 保存数据
     */
    public function save(){
        $data = I('post.');   
        if(!empty($data['range'])){
            $str = "";
            foreach ($data['range'] as $key => $value) {
                $str.=$value.',';
            }   
            $data['range'] = rtrim($str,',');                
        }             
        if(!empty($data['pkid']) && isset($data['pkid'])){              
            $result = $this->TagModel->data($data)->save();
        }else{
            $data['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $data['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
            $result = $this->TagModel->data($data)->add();
        }
        if($result !== false){
            $this->redirect('Tag/index','',1,'保存成功');
        }else{
            $this->redirect('Tag/index','',1,'保存失败');
        }
    }

    /*
     * 删除福利
     */
    public function delete(){
        $id = I('get.id');
        $tag = I('get.tag');
        if($tag=='sys'){
            $result = $this->TagModel->where(array('pkid'=>$id))->save(array('is_delete'=>1));
            if($result){              
                $this->redirect('Tag/index','',1,'删除成功');
            }else{               
                $this->redirect('Tag/index','',1,'删除失败');
            }
        }else{
            $result = $this->TagModel->where(array('pkid'=>$id))->delete();
            if($result){                
                $this->redirect('Tag/index',array('tag' =>'user'),1,'删除成功');
            }else{
                $this->redirect('Tag/index',array('tag' =>'user'),1,'删除失败');
            }
        }
    }

    public function change(){
        $id = I('post.id');        
        $type = I('post.type');       
        $title = I('post.title');       
        $range = I('post.range');       
        $is_delete = I('post.is_delete');
        if($is_delete==1){
            exit(json_encode(array('msg'=>false,'error' => '此标签已被删除，不能转化')));
        }else{
            if($this->TagModel->where(array('title' => $title,'type'=>1,'is_delete'=>2))->find()){
                exit(json_encode(array('msg'=>false,'error' => '标签已存在')));
            }else{
                $bool = $this->TagModel->add(array('title'=>$title,'range'=>$range,'create_time'=>date('Y-m-d H:i:s',NOW_TIME),'modify_time'=>date('Y-m-d H:i:s',NOW_TIME)));
                if($bool){
                    exit(json_encode(array('msg'=>true,'error' => '转化成功')));
                }else{
                     exit(json_encode(array('msg'=>false,'error' => '转化失败')));
                }
            }
        } 
    } 
   
}