<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Show_user_operations_admin extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function edit_button(){
        $this->load->view("operations/edit_button.html");
    }
    
    public function delete_button(){
        $this->load->view("operations/delete_button.html");
    }
}
?>
