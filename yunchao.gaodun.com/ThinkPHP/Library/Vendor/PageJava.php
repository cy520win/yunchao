<?php
/**
 * Created by PhpStorm.
 * User: gaodun
 * Date: 2015/10/27
 * Time: 17:43
 */

class PageJava {

    public $ip;
    public $PageOfficeCtrl;
    public $PDFCtrl;
    public $OpenMode;
    public $PageOfficeLink;
    public $resume_type = 4;

    public function __construct(){
        $this->ip = C('APP_IP');
        require_once("http://".$this->ip.":8080/JavaBridge/java/Java.inc");//此行必须
        java_set_file_encoding("UTF-8");//设置中文编码，若涉及到中文必须设置中文编码
        $this->PageOfficeCtrl = new Java("com.zhuozhengsoft.pageoffice.PageOfficeCtrlPHP");//此行必须
        $this->PDFCtrl = new Java("com.zhuozhengsoft.pageoffice.PDFCtrlPHP");//此行必须
        $this->OpenMode = new Java("com.zhuozhengsoft.pageoffice.OpenModeType");
        $this->PageOfficeLink = new JavaClass("com.zhuozhengsoft.pageoffice.PageOfficeLink");
    }

    //打开word文件
    public function openWord($path){
        if(empty($path)){
            return false;
        }
        $this->PageOfficeCtrl->setServerPage("http://".$this->ip.":8080/JavaBridge/poserver.zz");//此行必须，设置服务器页面
//        $this->PageOfficeCtrl->setJsFunction_AfterDocumentOpened("AfterDocumentOpened()");
        $this->PageOfficeCtrl->UserAgent = $_SERVER['HTTP_USER_AGENT'];//若要支持谷歌浏览器此行代码必须有，其他浏览器此行代码可以不加
        //隐藏Office工具条
        $this->PageOfficeCtrl->setOfficeToolbars(false);
        //隐藏自定义工具栏
        $this->PageOfficeCtrl->setCustomToolbar(false);
        $this->PageOfficeCtrl->setMenubar(false);
        $this->PageOfficeCtrl->setTitlebar(false);
        $this->PageOfficeCtrl->webOpen($path,$this->OpenMode->docReadOnly,'简历预览');   //打开相关文件(只读模式)
        return $this->PageOfficeCtrl->getDocumentView("PageOfficeCtrl1");
    }

    //打开pdf文件
    public function openPdf($path){
        $this->PDFCtrl->setServerPage("http://".$this->ip.":8080/JavaBridge/poserver.zz");//此行必须，设置服务器页面
//     $this->PDFCtrl->setJsFunction_AfterDocumentOpened("AfterDocumentOpened()");
        $this->PDFCtrl->UserAgent = $_SERVER['HTTP_USER_AGENT'];//若要支持谷歌浏览器此行代码必须有，其他浏览器此行代码可以不加
        $this->PDFCtrl->setMenubar(false);
        $this->PDFCtrl->setTitlebar(false);
        $this->PDFCtrl->setCustomToolbar(false);
        $this->PDFCtrl->webOpen($path);
        return $this->PDFCtrl->getDocumentView("PDFCtrl1");
    }

    public function openWordHtml($resume_id  = ''){
        return $this->PageOfficeLink
            ->openWindow(null, C('APP_URL').'/PageOffice/box/id/'.$resume_id,"width=1200px;height=800px;");
    }

    public function openPdfHtml($resume_id  = ''){
        return $this->PageOfficeLink
            ->openWindow(null, C('APP_URL').'/PageOffice/pdf/id/'.$resume_id,"width=1200px;height=800px;");
    }

}
