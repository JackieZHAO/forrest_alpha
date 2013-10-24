<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Project_user extends MY_Controller {

    public $model = "m_project_user";
    private $request_method;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library("form_validation");
        $this->load->library("pagination");
    }

    //return value is an array.
    public function get_project_id($user_id_pass) {
        $user_id=$user_id_pass;
        $project_id = $this->process_get_project_id($user_id);    
        return $project_id;
    }

    private function process_get_project_id($user_id) {
        $pass_col = "user_id";
        $select_col="project_id";
        $project_result=$this->select_where($select_col, $pass_col, $user_id);
        $project_id = $project_result->result();
        return $project_id;
    }
    
    public function get_ticket_id($user_id_pass) {
        $user_id=$user_id_pass;
        $ticket_id = $this->process_get_ticket_id($user_id);
//        foreach ($ticket_id as $item){
//            echo $item->ticket_id;
//        }
        return $ticket_id;
    }

    private function process_get_ticket_id($user_id) {
        $pass_col = "user_id";
        $select_col="ticket_id";
        $ticket_result=$this->select_where($select_col, $pass_col, $user_id);
        $ticket_id = $ticket_result->result();
        return $ticket_id;
    }
    
    public function get_ticket_id_from_project_id($project_id_pass) {
        $project_id=$project_id_pass;
        $ticket_id = $this->process_get_ticket_id_from_project_id($project_id);
//        foreach ($ticket_id as $item){
//            echo $item->ticket_id;
//        }
        return $ticket_id;
    }

    private function process_get_ticket_id_from_project_id($project_id) {
        $pass_col = "project_id";
        $select_col="ticket_id";
        $ticket_result=$this->select_where($select_col, $pass_col, $user_id);
        $ticket_id = $ticket_result->result();
        return $ticket_id;
    }
    
    public function add_project($project_id,$user_id){
        $data=array();
        $data=array(
            "project_id"=>$project_id,
            "user_id"=>$user_id
        );
        $this->_insert($data);
    }
    

}

?>
