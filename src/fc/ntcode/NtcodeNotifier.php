<?php
namespace fc\ntcode;

use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;



class NtcodeNotifier
{
    protected $session;
    protected $config;
    
    
    function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
        
        
    }
    
    
    // public function rander(){
    //     $i = rand(10,100);
    //     echo $i;
    //     $this->session->put('i' , $i);
    //     print_r( $this->session->all() );
    //     return " NtcodeNotifier ok" ;
    // }
    
    
    public function make(){
        $tn  = new TnCode( $this->session);
        // $tn->setPath( dirname(__FILE__) . "/../../../public/tncode_img" );
        $tn->setPath( public_path('vendor/ntcode')."/tncode_img" );
        $tn->make();
    }
    
    public function check(){
        $tn  = new TnCode( $this->session);
        if($tn->check()){
            return "ok";
        }else{
            return "error";   
        }

    }
    
}


