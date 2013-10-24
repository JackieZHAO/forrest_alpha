<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Modal_user_operations_admin extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function update(){
        $this->load->view("operations/modal_user_update_admin.html");
    }
    
    public function active(){
        $this->load->view("operations/modal_user_active_admin.html");
    }
}
?>