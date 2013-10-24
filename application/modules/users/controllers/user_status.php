<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_status extends MY_Controller {

    private $request_method;
    public $model = "m_user_status";
    private $user_slug;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
    }

    // TODO: Do a bit of security thing and disable this function
    //	if the user is logged in as a buyer
    public function get_not_approved() {
        $users = Modules::run('users/get');
        // It doesn't make any sense to return the hashed password
        //	so that I unset the password
        $users_without_password = array();
        foreach ($users as $user) {
            unset($user->password);
            array_push($users_without_password, $user);
        }
        $data['users'] = $users_without_password;
        return $users_without_password;
    }

    //the following is jackie's modification
    public function show_all_user_status() {
        $show_result = $this->get_all_user_status();
        $data['show_result'] = $show_result;

        $this->load->view('show_user_status.html', $data);
    }

    public function get_all_user_status() {
        $result = $this->get();
        return $result;
    }

    public function get_user_status($status_id) {
        if ($status_id != 0) {
            $user_status = $this->get_where($status_id);
            $status_type = $user_status->name;
            return $status_type;
        } else {
            $status_type = "None";
            return $status_type;
        }
    }

}