<?php
// +----------------------------------------------------------------------
// | 行业证书控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Vendor\PageAdmin as PageAdmin;
class CertificateController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $CertificateModel ;
    protected $nowtime;

    public function _initialize(){
	    parent::_initialize();
        $this->CertificateModel = D('Certificate'); // 初始化证书表模型
        $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
    }

    /**
     * 证书列表、搜索列表
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        $field = array();
        $order = 'create_time desc';
        $word_form = I('post.w') ? I('post.w') : I('get.w');//获取查询字
        $word_form ? $where['full_name|description'] = array('like',"%".$word_form."%"): ''; //生成数据库查询条件
        $where = !empty($where) ? $where : array();
        $where['is_delete'] = 2; //证书状态为未删除
        $certificate_total = $this->CertificateModel->getCertificateTotal($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($certificate_total/$this->page_number)); // 获取合法的分页数
        $certificate_data = $this->CertificateModel->getCertificateList($where,$field,$order,$page,$this->page_number); // 分页数据
        $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
        $Page       = new PageAdmin($certificate_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('word',$word_form);// 查询关键字
        foreach ($certificate_data as $key => $value) {
            $certificate_data[$key]['description'] = str_cut($certificate_data[$key]['description']);
            $certificate_data[$key]['certificate_type'] = self::getCertificateType($certificate_data[$key]['certificate_type']);
        }
        $this->assign('certificate',$certificate_data);
    	$this->display();
    }
    
    /**
     * 加载新增证书页面
     * @author 致远<george.zou@gaodun.cn>
     */
    public function add(){
        $this->assign('type',self::getCertificateType());
	    $this->display();
    }

    /**
     * 保存新增证书
     * @author 致远<george.zou@gaodun.cn>
     */
    public function save(){
        if(IS_POST){
            $data = I();
            $data['create_time'] = $this->nowtime;
            $data['modify_time'] = $this->nowtime;
            $bool = $this->CertificateModel->certificateAdd($data);
            if($bool){
                $this->redirect("Certificate/index",'',1,'添加成功');
            }else{
                $this->redirect("Certificate/add",'',1,'添加失败');
            }
        }
    }
    
    /**
     * 加载证书编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
        $field = array();
        $info = $this->CertificateModel->getCertificateinfo(I('get.id'),$field);
        $type = self::getCertificateType();
        foreach ($type as $k => $v){
            $type_new['val'] = $k;
            $type_new['text'] = $v;
            if(intval($info['certificate_type'])==$k){
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
     * 保存证书信息更新
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){
	   if(IS_POST){
            $post = I();
            $post['modify_time'] = $this->nowtime;
            $bool = $this->CertificateModel->certificateUpdata($post,$post['cert_id']);
            if($bool){
                $this->redirect("Certificate/index",array('id'=>$post['cert_id']),1,'修改成功');
            }else{
                $this->redirect("Certificate/index",'',1,'修改失败');
            }
        }  
    }

    /**
     * 逻辑删除证书
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
    */
    public function delete(){
        if($this->CertificateModel->certificateDelete(I('get.id'))){

            $this->redirect("Certificate/index",'',1,'删除成功');
        }else{

            $this->redirect("Certificate/index",'',1,'删除失败');
        }
    }

    /*
    * 获取该分类下证书
    * @author george.zou@gaodun.cn
    */ 
    public function getMajorCard(){
        if(IS_POST){
            $major_data = $this->CertificateModel->certificateMajorCard(I('post.id'));
            if($major_data){
                $option = '';
                foreach($major_data as $k=>$v){
                    $option .= '<option value='.$v['pkid'].'>'.$v['full_name'].'</option>';
                }
                $data = array('status'=>true,'data'=>$option,'msg'=>'获取成功');
            }else{
                $option = '<option>暂无数据</option>';
                $data = array('status'=>false,'data'=>$option,'msg'=>'获取失败');
            }
            exit(json_encode($data));
        }
    }
}