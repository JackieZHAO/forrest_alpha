<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Project_status extends MY_Controller {

    public $model = "m_project_status";
    private $request_method;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
    }

    public function get_all_project_status() {
        $result = $this->get();
        return $result;
    }

    //It is the orginal one
    public function show_all_project_status() {
        $show_result = $this->get_all_project_status();
        $data['show_result'] = $show_result;

        $this->load->view('show_project_status.html', $data);
    }

//    public function show_all_project_status($status_id_pra) {
//        if (empty($status_id_pra)) {
//            $status_id_pra = "";
//        }
//        $status_id = $status_id_pra;
//        $status_name = $this->get_project_status($status_id);
//        $real_result = $this->get_all_project_status();
//        $select_type = "";
//        $show_result = array();
//
//        foreach ($real_result as $show) {
//            $temp_result = $show;
//            $temp_array = (array) $temp_result;
//            if ($show->name == $status_name) {
//                $temp_array['select_type'] = "selected";
//            } else {
//                $temp_array['select_type'] = "";
//            }
//            $need_object = (object) $temp_array;
//            array_push($show_result, $need_object);
//        }
//        $data['show_result'] = $show_result;
//        $this->load->view('show_project_status.html', $data);
//    }
    
    //暂时不用
    public function ajax_show_all_project_status(){
        $status_id = $this->input->post("project_id", TRUE);
        
       
        $status_name = $this->get_project_status($status_id);
        $real_result = $this->get_all_project_status();
        $select_type = "";
        $show_result = array();

        foreach ($real_result as $show) {
            $temp_result = $show;
            $temp_array = (array) $temp_result;
            if ($show->name == $status_name) {
                $temp_array['select_type'] = "selected";
            } else {
                $temp_array['select_type'] = "";
            }
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        $data['show_result'] = $show_result;
        
        //$response = json_encode($data);
        //echo $response;
        
        $this->load->view('show_project_status.html', $data);
    }

    public function get_project_status($status_id) {
        if ($status_id != 0) {
            $project_status = $this->get_where($status_id);
            $status_type = $project_status->name;
            return $status_type;
        } else {
            $status_type = "None";
            return $status_type;
        }

//        if ($status_id == 0 || !is_object($status_id)) {
//            $status_type = "None";
//            return $status_type;
//        } else {
//            $project_status = $this->get_where($status_id);
//            $status_type = $project_status->name;
//            return $status_type;
//        }
    }

}

?>