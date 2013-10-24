<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Nav_user_menu extends MX_Controller{
    private $user_slug;
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }

    public function index(){
        $this->user_slug = $this->session->userdata['user_slug'];
        $this->load->view("menus/nav_user_".$this->user_slug.".html");
    }
}
?>
