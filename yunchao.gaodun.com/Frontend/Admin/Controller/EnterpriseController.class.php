<?php
// +----------------------------------------------------------------------
// | 企业账户控制器 
// +----------------------------------------------------------------------
// | 创建于2015-04-03 14:44:55 ，最后更新 2015-04-07 17:20
// +----------------------------------------------------------------------
// | 作者 : Martin Cheng <martin.cheng@gaodun.cn> 
// +----------------------------------------------------------------------
// | 更新 : 致远<george.zou@gaodun.cn>
// +----------------------------------------------------------------------

namespace Admin\Controller;
use Think\Upload as Upload;
use Vendor\PageAdmin as PageAdmin;
use Think\Crypt as Crypt;
class EnterpriseController extends AdminController{

    protected $page_number = 10 ;// 列表数量
    protected $EnterpriseModel ;
    protected $nowtime;
    protected $scale_list;
    protected $hotcity_list;
    protected $industry_list;

    public function _initialize(){
	    parent::_initialize();
      $this->EnterpriseModel = D('Enterprise'); // 初始化管理员账户模型
      $this->nowtime = date('Y-m-d H:i:s',NOW_TIME);
      $this->scale_list = $this->getBusSacle(); //企业规模
      $this->hotcity_list = $this->getHotCity(); //热门城市
      $this->industry_list = $this->getBusIndustry(); //行业类型
    }

    /**
     * 管理员列表
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function index(){
        $where = array();
        $field = 'enterprise.pkid as pkid,enterprise.login_email,enterprise.email_verify,full_name,scale_id,
        industry_id,telephone,order_num,is_hot,enterprise.create_time';
        $order = 'enterprise.create_time desc,order_num desc';
        $word_form = I('post.w') ? I('post.w') : I('get.w');//获取查询字
        //生成数据库查询条件
        if($word_form){
            $condition['enterprise.full_name'] = array('like',"%".$word_form."%");
            $condition['enterprise.login_email']  = array('like',"%".$word_form."%");
            $condition['_logic'] = 'or';
            $where['_complex'] = $condition;
        }

        $where = !empty($where) ? $where : true;
        $enterprise_total = $this->EnterpriseModel->getEnterpriseTotalOutDelete($where);//列表总数量
        $page = isset($_GET['p']) ? intval(htmlspecialchars(strip_tags($_GET['p']))) : '';//获取url的分页参数
        $page = max(1,$page);
        $page = min($page,ceil($enterprise_total/$this->page_number)); // 获取合法的分页数
        $enterprise_data = $this->EnterpriseModel->getEnterpriseListOutDelete($where,$field,$order,$page,$this->page_number); // 分页数据
        $map = $word_form ? array('w'=>$word_form) : array();//生成分页连接参数
        $Page       = new PageAdmin($enterprise_total,$this->page_number,$map); //分页类实例化
        $this->assign('page',$Page->show());// 分页显示输出
        $this->assign('word',$word_form);// 查询关键字
        $scaleModel = D('Scale');
        $industryModel = D('Industry');
        foreach($enterprise_data as $k=>$v){
            $scale =  $scaleModel->getScaleinfo($v['scale_id']);
            $industry = $industryModel->getIndustryinfo($v['industry_id']);
            $enterprise_data[$k]['scale'] = $scale['title'];
            $enterprise_data[$k]['industry'] = $industry['title'];
        }
        $this->assign('enterpriselist',$enterprise_data);
        $this->display();
    }

    /**
     * 加载新增企业模板文件
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function add(){
      $this->assign('fare',$this->welfare());
      $this->assign('scale',$this->scale_list);
      $this->assign('hotcity',D('Regions')->parentCity());
      $this->assign('industry',$this->industry_list);
	    $this->display();
    }

    /**
     * 执行添加新增企业数据
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function save(){
      if(IS_POST){
          $data = I();
          if(empty($data['full_name']) || empty($data['login_email'])){
              $this->redirect("Enterprise/add",'',1,'企业名称或登陆邮箱为空');
          }
          $account_data['login_email'] = $data['login_email'];
          $account_data['login_pass'] = MD5(C('DATA_AUTH_KEY').Crypt::encrypt('chinagdsx2015')); //默认密码
          $account_data['account_type'] = 1;//默认账户类型
          $account_data['create_time'] = date("Y-m-d H:i:s",NOW_TIME);
          $account_data['modify_time'] = date("Y-m-d H:i:s",NOW_TIME);
          $account_id = 1;//D('Account')->accountAdd($account_data); //新增account账户
          if($account_id){
              $data['create_time'] = $this->nowtime;
              $data['modify_time'] = $this->nowtime;
              unset($data['_wysihtml5_mode']); //删除不需要的字段数据
              $data['account_id'] = $account_id; //设置默认的账户ID
              $data['website'] = !empty($data['website']) ? pregHttp($data['website']) : '';
              $bool = $this->EnterpriseModel->enterpriseAdd($data);
              if($bool){
                  /*$EnterpriseWelfare  = D('EnterpriseWelfare');
                  if(!empty($data['welfare'])){
                    $welfare['enterprise_id'] = $bool;
                    foreach($data['welfare'] as $k=>$v){
                        $welfare['welfare_id'] = $v;
                        $EnterpriseWelfare->EnterfareAdd($welfare);
                    }
                  }*/
                  $this->redirect("Enterprise/index",'',1,'添加成功');
              }else{
                  $this->redirect("Enterprise/index",'',1,'添加失败');
              }
          }else{
                  $this->redirect("Enterprise/index",'',1,'添加ACCOUNT失败');
          }
      }
    }

    /*
     * 加载重设帐号密码页面
     * @author allen
     */
    public function reset(){
        $enId = I('get.id');
        $field = 'account.login_pass,enterprise.login_email,enterprise.pkid as enterprise_id,enterprise.account_id';
        $data = $this->EnterpriseModel->field($field)->join('account ON account.pkid = enterprise.account_id')->where
        (array('enterprise.pkid' => $enId))->find();
        $this->assign('accountInfo',$data);
        $this->display();
    }

    /**
     * 加载企业信息编辑页面
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function edit(){
        $field = array();
        $data = $this->EnterpriseModel->getEnterpriseinfo(I('get.id'),$field); //获取企业信息数据
        $data['description'] = strip_tags($data['description'],'');
        //处理企业福利
        /*$well = $this->welfare();
        $well_data = D('EnterpriseWelfare')->EnterfareList(I('get.id'));
        foreach($well_data as $k=>$v){
          $fare[] = $v['welfare_id'];
        }
        foreach($well as $k=>$v){
		$well_none['val'] = $k;
		$well_none['text'] = $v;
		if(in_array($k,$fare)){
			$well_none['checked'] = 'checked';
		}
            $well_list[] = $well_none;
            unset($well_none);
        }      
        $this->assign('well',$well_list);*/
        $this->assign('info',$data);
        $this->assign('scale',$this->scale_list);
        $this->assign('hotcity',D('Regions')->parentCity());
        $this->assign('childcity',D('Regions')->childCity($data['province_id']));
        $this->assign('industry',$this->industry_list);
	      $this->display();
    }

    /**
     * 企业信息更新
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function update(){

	   if(IS_POST){
            $data = I();
            unset($data['_wysihtml5_mode']); //删除不需要的字段数据
            if(empty($data['full_name'])){
              $this->redirect("Enterprise/edit",array('id'=>$data['enterprise_id']),1,'企业名称或登陆邮箱为空');
            }
            $data['modify_time'] = $this->nowtime;
           $data['website'] = !empty($data['website']) ? pregHttp($data['website']) : '';
           //$EnterpriseWelfare = D('EnterpriseWelfare');
            $bool = $this->EnterpriseModel->enterpriseUpdata($data,$data['enterprise_id']);

            if($bool){
                /*if(!empty($data['welfare'])){
                    $EnterpriseWelfare->EnterfareDel($data['enterprise_id']);
                    $welfare['enterprise_id'] = $data['enterprise_id'];
                    foreach($data['welfare'] as $k=>$v){
                        $welfare['welfare_id'] = $v;
                        //print_r($welfare);die;
                        $EnterpriseWelfare->EnterfareAdd($welfare);
                    }
                }else{
                    $EnterpriseWelfare->EnterfareDel($data['enterprise_id']);
                }*/

                //更新账户邮箱
                $this->redirect("Enterprise/index",'',1,'修改成功');
            }else{
                $this->redirect("Enterprise/edit",array('id'=>$data['enterprise_id']),1,'修改失败');
            }
        }
    }

    /**
     * 上传头像、图片
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function uphead(){
        $upload = new Upload();// 实例化上传类
        $upload->rootPath  =  'Public/Upload/headpic/enterprise/'; // 设置附件上传根目录
        dir_create($upload->rootPath);//创建文件夹
        $head = $upload ->uploadOne($_FILES['headpic']);
        $path = C('APP_URL').'/'.$upload->rootPath.$head['savepath'].$head['savename'];
        $data = array('msg'=>$path,'error'=>'');
        exit(json_encode($data));
    }

    /**
     * 福利数组
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function welfare(){
      $data_new = D('Welfare')->welfareList();
      foreach($data_new as $k=>$v){
        $data[$v['pkid']] = $v['title'];
      }
      return $data;
    }

    /**
     * 验证企业邮箱唯一
     * @author Martin.Cheng <martin.cheng@gaodun.cn>
     */
    public function checkMail(){
        if(IS_POST){
            $email = isset($_POST['mail']) ? I('post.mail') : '';
            $uid = isset($_POST['uid']) ? I('post.uid') : '';
            $bool = D('Account')->checkMailUnique($email,$uid);
            exit(json_encode($bool));
        }
    }

    /*
     * 修改企业帐号密码
     * @author allen
     */
    public function resetPwd(){
        $data = I('post.');
        $acData = array(
            'pkid' => $data['id'],
            'login_pass' => MD5(C('DATA_AUTH_KEY').Crypt::encrypt('chinagdsx2015'))
        );
        $result = D('Account')->data($acData)->save();
        if($result != false){
            exit(json_encode(array('status' => 'success','msg' => '修改成功')));
        }else{
            exit(json_encode(array('status' => 'fail','msg' => '修改失败')));
        }
    }

    /*
     * 修改企业帐号信息
     * @author allen
     */
    public function resetAccount(){
        $data = I('post.');
        $acParam = array(
            'pkid' => $data['acId'],
            'login_email' => $data['email'],
            'login_pass' => MD5(C('DATA_AUTH_KEY').Crypt::encrypt(strtolower($data['password'])))
        );
        $bool = D('Account')->checkMailUnique($acParam['login_email'],$acParam['pkid']);
        if(!$bool){
            exit(json_encode(array('status' => 'fail','msg' => '修改失败','email' => 'notunique')));
        }

        $pwdRule = checkpass($data['password']);
        if(!$pwdRule){
            exit(json_encode(array('status' => 'fail','msg' => '修改失败','pass_word' => 'notunique')));
        }
        $enParam = array(
            'pkid' => $data['enId'],
            'login_email' => $data['email']
        );
        $result = D('Account')->data($acParam)->save();
        $this->EnterpriseModel->data($enParam)->save();
        if($result !== false){
            exit(json_encode(array('status' => 'success','msg' => '修改成功')));
        }else{
            exit(json_encode(array('status' => 'fail','msg' => '修改失败')));
        }
    }

    public function checkPwdRule(){
        $pass = I('post.pass');
        $pwdRule = pwdCharInt($pass);
        if(!$pwdRule){
            exit(json_encode(array('status' => 'fail','msg' => '修改失败','pass_word' => 'notunique')));
        }else{
            exit(json_encode(array('status' => 'success','msg' => '修改成功')));
        }
    }

    /*
    * @function 加载设置联系人view
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function contact(){
      $pkid = I('get.id') ? intval(I('get.id')) : '';
      $contact_id = I('get.cid') ? intval(I('get.cid')) : '';
      $tag_from = isset($_GET['tag']) ? I('get.tag') : '';
      (!$pkid && !$contact_id) ? exit('参数错误') : '';
      $email = $this->EnterpriseModel->getEnterpriseinfo($pkid,'login_email');
      empty($email) ? exit('企业账号不存在') :'';

      $contact = M('enterprise_contact')->where(array('enterprise_id'=>$pkid))->order('create_time desc')->select();
      // $contact_first = count($contact) < 1 ? TRUE : FALSE;
      $contact ? $this->assign('list',$contact) : '';
      if($contact_id){
        $contact_info = M('enterprise_contact')->where(array('pkid'=>$contact_id))->find();
        empty($contact_info) ? exit('联系人不存在') : $this->assign('info',$contact_info);
      }
      $list_text = array(
          'status'  =>  array(0=>'未推送',1=>'推送成功',2=>'推送失败'),
          'type'    =>  array(1=>'用户',2=>'后台')
        );
      $this->assign('text',$list_text);
      $data = array(
        'enterprise_id'=>$pkid,
        'email'=>$email['login_email'],
        'first_bool'=>$contact_first,
        'tag'=>$tag_from,
        'type' => $contact_id ? 1 : 0,
        );
      $this->assign('data',$data);   
      $this->display('contact');
    }

    /*
    * @function 保存联系人
    * @param 
    * @return 
    * 致远<george.zou@gaodun.cn> ('<^>') 
    */
    public function dataContact(){
      $post = I('post.') ? I('post.') : '';
      $post['modify_time'] = date('Y-m-d H:i:s',NOW_TIME);
      if(isset($_POST['enterprise_id']) && I('post.enterprise_id')){
        $post['create_time'] = date('Y-m-d H:i:s',NOW_TIME);
        $post['type'] = 2 ;
        $last_id = M('enterprise_contact')->data($post)->add();       
      }
      if(isset($_POST['contact_id']) && I('post.contact_id')){
        $post['enterprise_id'] = $post['contact_id_enter'];
        $where['pkid'] = $post['contact_id'];
        unset($post['contact_id']);
        unset($post['contact_id_enter']);
        $last_id = M('enterprise_contact')->where($where)->data($post)->save();  
      }
      if($last_id){
        $this->redirect("/Admin/Enterprise/contact/id/".$post['enterprise_id'],'',1,'设置成功');
      }else{
        $this->redirect("/Admin/Enterprise/contact/id/".$post['enterprise_id'],'',1,'设置失败');
      }
    }

    //检查邮箱类型
    public function chkMailType(){
      $mail = isset($_POST['data']) ? I('post.data') : '';
      $enterprise_id = isset($_POST['eid']) ? I('post.eid') : '';
      $type = isset($_POST['type']) ? intval(I('post.type')) : 0;
      if($mail && $enterprise_id){
          if(!parent::checkMail($mail)){
            exit(json_encode(array('status'=>FALSE,'msg'=>'邮箱格式错误')));
          }
          if($this->checkMailType($mail)){
            exit(json_encode(array('status'=>FALSE,'msg'=>'不属于企业邮箱')));
          }else{
            $where['e.contact_mail'] = $mail;
            if($type===1){
              $where['e.enterprise_id'] = array('neq',$enterprise_id);
            }
            $count = M('enterprise_contact as e')->where($where)->getField('e.pkid');
            if($count){
              exit(json_encode(array('status'=>FALSE,'msg'=>'该邮箱已经被使用')));
            }else{
              exit(json_encode(array('status'=>TRUE,'msg'=>'')));
            }
          }
      }else{
        exit(json_encode(array('status'=>FALSE,'msg'=>'不能为空')));
      }
    }
}