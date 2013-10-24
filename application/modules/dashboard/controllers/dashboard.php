<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    private $user_slug;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
//        $user_id = $this->session->userdata['user_id'];
        $this->user_slug = $this->session->userdata['user_slug'];
    }

    public function menu() { 
        $data['user_slug']=$this->user_slug;
        $this->load->view("menus/" . $this->user_slug . ".html",$data);
    }

    public function index() {
        $data['content_path'] = "dashboard/home.html";
        echo Modules::run('templates/backend', $data);
    }

    public function clients() {
        // TODO: make sure is logged in as a master or as a sales person
        // TODO: try not to use if conditions here, try to use some sort of inheritance because we don't know how many types of user we are going to have and the goal is to keep this function untouched nomatter how many types of users we have
        $data['content_path'] = "dashboard/clients.html";
        if ($this->user_slug == 'admin') {
            echo Modules::run('templates/backend', $data);
        } else if ($this->user_slug == 'sales') {
            echo Modules::run('templates/backend', $data);
        } else {
            echo "Forbidden";
        }
    }

}