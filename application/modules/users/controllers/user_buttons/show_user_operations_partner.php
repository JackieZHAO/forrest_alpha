<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Show_user_operations_partner extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function edit_button(){
        $this->load->view("operations/edit_button_partner.html");
    }
    
}
?>
