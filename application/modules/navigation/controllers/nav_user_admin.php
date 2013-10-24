<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Nav_user_admin extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function index(){
        $this->load->view("nav_user_admin.html");
    }
}
?>
