<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_type extends MY_Controller {

    private $request_method;
    public $model = "m_user_type";

    public function __construct() {
        parent::__construct();
        //to do: here is a problem, if we do not add the following code, it will cause some security
        //problem,however, if we add it, it will not allowed to get this class's function before the login point,
        //in users/process login, we use this class's function.
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
    }

//	public function get_user_type($user_id)
//	{
//		$user = Modules::run('users/get_where', $user_id);
//		$user_type = $this->m_user_type->get_where($user->type_id);
//
//		return $user_type;
//	}
    //the following is jackie's modification
    public function show_all_user_type() {
        $show_result = $this->get_all_user_type();
        $data['show_result'] = $show_result;

        $this->load->view('show_user_type.html', $data);
    }

    public function get_all_user_type() {
        $result = $this->get();
        return $result;
    }

    public function get_user_type($type_id) {
        if ($type_id != 0) {
            $user_type = $this->get_where($type_id);
            $type_type = $user_type->name;
            return $type_type;
        } else {
            $type_type = "None";
            return $type_type;
        }
    }
    
    public function get_user_type_slug($type_id){
        if ($type_id != 0) {
            $user_type = $this->get_where($type_id);
            $type_type = $user_type->slug;
            return $type_type;
        } else {
            $type_type = "None";
            return $type_type;
        }
    }

}