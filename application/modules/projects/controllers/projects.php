<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Projects extends MY_Controller {

    public $model = "m_projects";
    private $request_method;
    private $project_user_id;
    private $user_id;
    private $user_slug;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');

        // TODO: one of the following lines is creating problem while logging out.
        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library("form_validation");
        //when constrct this object, arrange the following two variables.
        $this->user_id = $this->session->userdata['user_id'];
        $this->user_slug = $this->session->userdata['user_slug'];
    }

    //it is the entry for showing projects no matter admin or normal users
    //then, using a condition to judge which function should be called.
    public function show_projects() {
        //using slug to judge whether this user is admin or not.
        if ($this->user_slug == "admin") {
            $this->show_all_project();
        } else {
            $this->show_project_specific();
        }
    }

    //showing all projects(admin)
    public function show_all_project() {
        $nav_project_menu = "";
//        $status_dropdown = "";
        $temp_show_result = $this->get_all_project();
        $show_result = array();
        //to do: extract the following code. repeated.
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status_id;
            $status_type = Modules::run("projects/project_status/get_project_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        //navigation menu
        $nav_project_menu = Modules::run("projects/project_menus/nav_project_menu/index");
        $data['nav_project_menu'] = $nav_project_menu;
        //edit button
        $edit_button = Modules::run("projects/project_buttons/show_project_operations_admin/edit_button");
        $data['edit_button'] = $edit_button;
        //delete button
        $delete_button = Modules::run("projects/project_buttons/show_project_operations_admin/delete_button");
        $data['delete_button'] = $delete_button;
        //thead edit
        $thead_edit = "Edit";
        $data['thead_edit'] = $thead_edit;
        //thead delete
        $thead_delete = "Delete";
        $data['thead_delete'] = $thead_delete;
        //currently, have not used $status_dropdown
//        $status_dropdown = Modules::run("projects/project_status/show_all_project_status");
//        $data['project_status_dropdown'] = $status_dropdown;
        $data['show_result'] = $show_result;
        $data['content_path'] = "projects/show_projects.html";
        echo Modules::run('templates/backend', $data);
    }

    //it is called by projects/show_all_project
    public function get_all_project() {
        $result = $this->get();
        return $result;
    }

    //show project according to specific user
    public function show_project_specific() {
        $nav_project_menu = "";
        $edit_button = "";
        $delete_button = "";
        $thead_edit = "View";
        if($this->user_slug=="admin"){
            $thead_delete = "Delete";
        }else{
           $thead_delete = ""; 
        }
        
        $user_pass_by_partner=$this->uri->segment(3);
        if(is_numeric($user_pass_by_partner)){
            $user_id_pass=$user_pass_by_partner;
        }else{
           $user_id_pass = $this->user_id; 
        } 
        $temp_show_result = $this->get_all_project_specific($user_id_pass);
        $show_result = array();

        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status_id;
            $status_type = Modules::run("projects/project_status/get_project_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            //$temp_array['id'] = $id;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        //navigation menu
        $nav_project_menu = Modules::run("projects/project_menus/nav_project_menu/index");
        $data['nav_project_menu'] = $nav_project_menu;
        //edit button
        if($this->user_slug=="client"){
             $edit_button = Modules::run("projects/project_buttons/show_project_operations_client/edit_button");    
        }
        if($this->user_slug=="partner"){
            $edit_button = Modules::run("projects/project_buttons/show_project_operations_partner/edit_button");    
        }
        
        if($this->user_slug=="admin"){
            $edit_button = Modules::run("projects/project_buttons/show_project_operations_admin/edit_button");
        }
       
        $data['edit_button'] = $edit_button;
        //delete button
        if($this->user_slug=="admin"){
             $delete_button = Modules::run("projects/project_buttons/show_project_operations_admin/delete_button");
        }
        $data['delete_button'] = $delete_button;
        //thead edit
        $data['thead_edit'] = $thead_edit;
        //thead delete
        $data['thead_delete'] = $thead_delete;
        //currently, have not used $status_dropdown
//        $status_dropdown = Modules::run("projects/project_status/show_all_project_status");
//        $data['project_status_dropdown'] = $status_dropdown;
        //to do: need to decide which content_path should be gave

        $data['show_result'] = $show_result;
        $data['content_path'] = "projects/show_projects.html";
        echo Modules::run('templates/backend', $data);
    }

    public function get_all_project_specific($user_id_pass) {
        $col = "user_id";
        $value = $user_id_pass;
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }

    //update project detail
    public function update_project() {
        if ($this->request_method == "POST") {
            $this->process_update_project();
        } else {
            $update_id = $this->uri->segment(3);
            if (is_numeric($update_id)) {
                $project = $this->get_where($update_id);
                $data = (array) $project;
            }
            //get project details from db according to its id
            //status dropdown
            $project_status_dropdown = Modules::run("projects/project_status/show_all_project_status");
            $data['project_status_dropdown'] = $project_status_dropdown;
            $data['content_path'] = "projects/edit_projects/update_project.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    //it is called by projects/update_project
    private function process_update_project() {
        $data = $this->get_data_from_post_super();
        $id = $data['id'];
        $this->_update($id, $data);

        /*
         * to do: how to refresh the page and stay in the same url?or to the other page?
         */
        redirect("projects/show_all_project");
    }

    //it is called by admin add create the new project
    public function add_project() {
        if ($this->request_method == "POST") {
            $this->process_add_project();
        } else {
            //status dropdown
            $project_status_dropdown = Modules::run("projects/project_status/show_all_project_status");
            $data['project_status_dropdown'] = $project_status_dropdown;
            $data['content_path'] = "projects/edit_projects/add_project.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    private function process_add_project() {
        $data = $this->get_data_from_post_super();
        $this->_insert($data);
        redirect("projects/show_all_project");
    }

    //it is called by the clicking the delete button on view page.
    public function delete_project() {
        $delete_id = $this->uri->segment(3);
        $result = $this->_delete($delete_id);
        if ($result) {
            redirect("projects/show_all_project");
        } else {
            $error_message = "There is a error of deleting action.";
            echo $error_message;
        }
    }

    //it is called when clients want to view their projects' details
    //just for viewing.
    public function ajax_get_project() {
        $id = $this->input->post("project_id", TRUE);
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            if($key=="status_id"){
                $status_id = $value;
                $value = Modules::run("projects/project_status/get_project_status", $status_id);
                $key="status_type";
            }        
            $data[$key] = $value;
        }
        $response = json_encode($data);
        echo $response;
    }

    public function get_project($id) {
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }
    
    //get the value of this project
    public function get_value_by_user($user_array){
        $select_col="value";
        $pass_col="user_id";
        //$pass_value=array('5','6');
        $pass_value=$user_array;
        //array_push($pass_value,'5');
        //array_push($pass_value,'6');
        $result=$this->select_where_in($select_col, $pass_col, $pass_value);
        $sum=0;
        $temp_value=0;
        foreach($result as $item){
            $temp_value=$item->value;
            $sum+=$temp_value;
        }
       return $sum;
    }
    
    private function select_where_in($select_col,$pass_col,$pass_value){
        return $this->{$this->model}->select_where_in($select_col, $pass_col, $pass_value);
    }

    //to do: extract this function to the parent MY_controller class
    public function get_data_from_post_super() {
        $data = "";
        $temp_data = $this->input->post(NULL, TRUE);
        foreach ($temp_data as $key => $value) {
            $this->form_validation->set_rules($key, 'V' . $key, 'xss_clean');
        }

        if ($this->form_validation->run($this) == FALSE) {
            //to do: if it is false, which function or page we need to redirect to?
        } else {
            foreach ($temp_data as $key => $value) {
                if ($key == "start_date") {
                    $start_date = $temp_data[$key];
                    $data[$key] = date("Y-m-d H:i:s", strtotime($start_date));
                    continue;
                }
                if ($key == "end_date") {
                    $end_date = $temp_data[$key];
                    $data[$key] = date("Y-m-d H:i:s", strtotime($end_date));
                    continue;
                }
                $data[$key] = $temp_data[$key];
            }
        }
        return $data;
    }

    //currently, have not used this function.
    public function ajax_delete_project() {
        $delete_id = $this->input->post("project_id", TRUE);
        $result = $this->_delete($delete_id);
        echo $result;
        //redirect("projects/show_all_project");
    }

}

?>