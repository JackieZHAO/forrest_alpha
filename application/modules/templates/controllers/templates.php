<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Templates extends MX_Controller {

    private $user_slug;
    private $user_name;

    function __construct() {
        parent::__construct();
    }

    //未修改完，只是暂时测试notification可不可行。
    public function menu() {
        $data="";
        $this->user_slug = $this->session->userdata['user_slug']; 
        if($this->user_slug=="partner"){
          $data['total_sum']=  Modules::run("users/get_assoc_project_value");            
        }
        $this->user_name=$this->session->userdata['user_name']; 
        $data['user_name']=$this->user_name;
        $data['user_slug']=$this->user_slug;
        $this->load->view("menus/" . $this->user_slug . ".html",$data);
    }

    function frontend($data) {
        $this->load->view('frontend.html', $data);
    }

    function backend($data) {

        $data['main_menu'] = $this->menu();
        Modules::run('site_security/setup_logout_headers');
        $this->load->view('backend.html', $data);
    }

}

?>
