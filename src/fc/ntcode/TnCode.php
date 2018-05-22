<?php 
namespace fc\ntcode;
use Illuminate\Session\SessionManager;


class TnCode
{
    var $im = null;
    var $im_fullbg = null;
    var $im_bg = null;
    var $im_slide = null;
    var $bg_width = 240;
    var $bg_height = 150;
    var $mark_width = 50;
    var $mark_height = 50;
    var $bg_num = 6;
    var $_x = 0;
    var $_y = 0;
    
    
    

    var $sPath = "";
    protected $session;
    
    //容错象素 越大体验越好，越小破解难道越高
    var $_fault = 3;
    function __construct( SessionManager $session = null ){
        //ini_set('display_errors','On');
        
        // error_reporting(0);
        
        
        if( $session == null ){
            session_start();
        }
        
        $this->session = $session;
        
        
        $this->im = config('ntcode.im', null);
        $this->im_fullbg = config('ntcode.im_fullbg', null);
        $this->im_bg = config('ntcode.im_bg', null);
        $this->im_slide = config('ntcode.im_slid', null);
        $this->bg_width = config('ntcode.bg_width', 240);
        $this->bg_height = config('ntcode.bg_height', 150);
        $this->mark_width = config('ntcode.mark_width', 50);
        $this->mark_height = config('ntcode.mark_height', 50);
        $this->bg_num = config('ntcode.bg_num', 6);
        $this->_x = config('ntcode.x', 0);
        $this->_y = config('ntcode.y', 0);
    
    
    
    }
    
    function setSession($_key , $_val ){
        if( $this->session == null ){
            
            $_SESSION[ $_key ] = $_val;
            return true;
        }
        
        $this->session->put($_key, $_val);
        return true;
    }
    function getSession( $_key ){
        
        if( $this->session == null ){
            if ( !array_key_exists($_key,$_SESSION)){
                return false;
            }
            return $_SESSION[ $_key ];
        }
        
        return $this->session->get( $_key , false);
        
    }
    
    function unsetSession($_key){
        if( $this->session == null ){
            if ( array_key_exists($_key,$_SESSION)){
                 unset( $_SESSION[$_key] );
            }
            return;
        }
        
        $this->setSession($_key , false );
        
        return;
        
    }
    
    
    function make($_nowebp = false){
        $this->_init();
        $this->_createSlide();
        $this->_createBg();
        $this->_merge();
        $this->_imgout($_nowebp);
        $this->_destroy();
    }

    function check($offset=''){
        
        if(! $this->getSession( 'tncode_r' ) ){
            return false;
        }
        if(!$offset){
            $offset = $_REQUEST['tn_r'];
        }
        $ret = abs( $this->getSession('tncode_r')-$offset)<=$this->_fault;
        if($ret){
            // unset( $this->getSession('tncode_r') );
            $this->unsetSession('tncode_r');
        }else{
            $tncode_err = $this->getSession('tncode_err') + 1;
            $this->setSession('tncode_err',$tncode_err);
            if( $this->getSession('tncode_err') >10){//错误10次必须刷新
                // unset( $this->getSession('tncode_r') );
                $this->unsetSession('tncode_r');
            }
        }
        return $ret;
    }
    
    public function setPath( $_path = '' ){
        if( $_path == '' ){
            $_path = dirname(__FILE__);
        }
        $this->sPath = $_path;
    }
    
    private function getPath(){
        
        return $this->sPath;
    }

    private function _init(){
        $bg = mt_rand(1,$this->bg_num);
        $file_bg = $this->getPath().'/bg/'.$bg.'.png';
        $this->im_fullbg = imagecreatefrompng($file_bg);
        $this->im_bg = imagecreatetruecolor($this->bg_width, $this->bg_height);
        imagecopy($this->im_bg,$this->im_fullbg,0,0,0,0,$this->bg_width, $this->bg_height);
        $this->im_slide = imagecreatetruecolor($this->mark_width, $this->bg_height);
        $tncode_r = $this->_x = mt_rand(50,$this->bg_width-$this->mark_width-1);
        $this->setSession( 'tncode_r' , $tncode_r );//$this->getSession(
        $this->setSession( 'tncode_err' , 0 );//$this->getSession(
        
        $this->_y = mt_rand(0,$this->bg_height-$this->mark_height-1);
    }

    private function _destroy(){
        imagedestroy($this->im);
        imagedestroy($this->im_fullbg);
        imagedestroy($this->im_bg);
        imagedestroy($this->im_slide);
    }
    private function _imgout( $_nowebp ){
        // if(!$_GET['nowebp']&&function_exists('imagewebp')){//优先webp格式，超高压缩率
        if(!$_nowebp&&function_exists('imagewebp')){//优先webp格式，超高压缩率
            $type = 'webp';
            $quality = 40;//图片质量 0-100
        }else{
            $type = 'png';
            $quality = 7;//图片质量 0-9
        }
        header('Content-Type: image/'.$type);
        $func = "image".$type;
        $func($this->im,null,$quality);
    }
    private function _merge(){
        $this->im = imagecreatetruecolor($this->bg_width, $this->bg_height*3);
        imagecopy($this->im, $this->im_bg,0, 0 , 0, 0, $this->bg_width, $this->bg_height);
        imagecopy($this->im, $this->im_slide,0, $this->bg_height , 0, 0, $this->mark_width, $this->bg_height);
        imagecopy($this->im, $this->im_fullbg,0, $this->bg_height*2 , 0, 0, $this->bg_width, $this->bg_height);
        imagecolortransparent($this->im,0);//16777215
    }

    private function _createBg(){
        $file_mark = $this->getPath().'/img/mark.png';
        $im = imagecreatefrompng($file_mark);
        header('Content-Type: image/png');
        //imagealphablending( $im, true);
        imagecolortransparent($im,0);//16777215
        //imagepng($im);exit;
        imagecopy($this->im_bg, $im, $this->_x, $this->_y  , 0  , 0 , $this->mark_width, $this->mark_height);
        imagedestroy($im);
    }

    private function _createSlide(){
        $file_mark = $this->getPath().'/img/mark2.png';
        $img_mark = imagecreatefrompng($file_mark);
        imagecopy($this->im_slide, $this->im_fullbg,0, $this->_y , $this->_x, $this->_y, $this->mark_width, $this->mark_height);
        imagecopy($this->im_slide, $img_mark,0, $this->_y , 0, 0, $this->mark_width, $this->mark_height);
        imagecolortransparent($this->im_slide,0);//16777215
        //header('Content-Type: image/png');
        //imagepng($this->im_slide);exit;
        imagedestroy($img_mark);
    }

}

