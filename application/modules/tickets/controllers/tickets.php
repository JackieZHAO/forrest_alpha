<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Tickets extends MY_Controller {

    public $model = "m_tickets";
    private $request_method;
    private $user_id;
    private $user_slug;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');

        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library("form_validation");
        $this->load->library("pagination");
        $this->user_id = $this->session->userdata['user_id'];
        $this->user_slug = $this->session->userdata['user_slug'];
    }

//it is the entry for showing tickets
    public function show_tickets() {
        $user_slug = $this->user_slug;
        if ($user_slug == "admin") {
            $this->show_all_ticket();
        } else {
            $this->show_ticket_user();
        }
    }

    public function show_all_ticket() {
        $edit_button = "";
        $post_ticket_form = "";
        $project_id_pass = "";
        $temp_show_result = $this->get_all_ticket();
        $show_result = array();
//to do: extract the following code. repeated
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->ticket_status;
            $status_type = Modules::run("tickets/ticket_status/get_ticket_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_admin/edit_button");
        $data['edit_button'] = $edit_button;
        $data['post_ticket_form'] = $post_ticket_form;
        $data['project_id_pass'] = $project_id_pass;
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_tickets.html";
        echo Modules::run('templates/backend', $data);
    }

    public function get_all_ticket() {
        $result = $this->get();
        return $result;
    }

//showing all tickets with the specific project id
    public function show_ticket_specific() {
//input:project_id
//output: all tickets related to this project_id  
        $edit_button = "";
        $post_ticket_form = "";
        $project_id_pass = $this->uri->segment(3);
        $temp_show_result = $this->get_all_ticket_specific($project_id_pass);
        $show_result = array();
//to do: extract the following code. repeated
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->ticket_status;
            $status_type = Modules::run("tickets/ticket_status/get_ticket_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
//to do: need to decide which content_path should be gave
        if ($this->user_slug == "client") {
            //$post_ticket_form = $this->post_ticket_form($project_id_pass);
            $post_ticket_form = Modules::run("tickets/edit_tickets/post_ticket_operations_client/post_ticket_form",$project_id_pass);
        }
        if ($this->user_slug == "admin") {
            $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_admin/edit_button");
        } else if ($this->user_slug == "client") {
            $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_client/edit_button");
        }

        $data['edit_button'] = $edit_button;
        $data['post_ticket_form'] = $post_ticket_form;
        $data['project_id_pass'] = $project_id_pass;
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_tickets.html";
        echo Modules::run('templates/backend', $data);
    }

    public function get_all_ticket_specific($project_id_pass) {
        $col = "project_id";
        $value = $project_id_pass;
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }

    public function show_ticket_user() {
        $edit_button = "";
        $project_id_pass = "";
        $post_ticket_form = "";
        $user_id_pass = $this->user_id;
        $temp_show_result = $this->get_all_ticket_user_specific($user_id_pass);
        $show_result = array();
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->ticket_status;
            $status_type = Modules::run("tickets/ticket_status/get_ticket_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
//to do: need to decide which content_path should be gave
        if ($this->user_slug == "admin") {
            $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_admin/edit_button");
        } else {
            $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_client/edit_button");
        }
        $data['edit_button'] = $edit_button;
        $data['post_ticket_form'] = $post_ticket_form;
        $data['project_id_pass'] = $project_id_pass;
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_tickets.html";
        echo Modules::run('templates/backend', $data);
    }

    private function get_all_ticket_user_specific($user_id_pass) {
        $col = "user_id";
        $value = $user_id_pass;
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }

    //this function is called by when admin checking the no replied tickets.
    public function show_ticket_no_replied() {
        $edit_button = "";
        $post_ticket_form = "";
        $project_id_pass = "";
        $temp_show_result = $this->get_ticket_no_replied();
        $show_result = array();
//to do: extract the following code. repeated
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->ticket_status;
            $status_type = Modules::run("tickets/ticket_status/get_ticket_status", $status_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        $edit_button = Modules::run("tickets/ticket_buttons/show_ticket_operations_admin/edit_button");
        $data['edit_button'] = $edit_button;
        $data['post_ticket_form'] = $post_ticket_form;
        $data['project_id_pass'] = $project_id_pass;
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_tickets_no_replied.html";
        echo Modules::run('templates/backend', $data);
    }

    //it is called by tickets/show_ticket_no_replied
    private function get_ticket_no_replied() {
        $col = "replied";
        $value = "0";
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }

    //it is a notification function for admin.
    //when there are new no replied tickets existed.
    public function notification_ticket_admin() {
        //make sure only admin can use this function
        Modules::run('site_security/make_sure_is_admin');

        $count = $this->no_repllied_ticket_existed();
        if ($count > 0) {
            echo $count;
        } else {
            echo "false";
        }
    }

    //it is called by tickets/notification_ticket_admin
    private function no_repllied_ticket_existed() {
        $col = "replied";
        $value = "0";
        $query = $this->count_where($col, $value);
        return $query;
    }

    public function get_ticket_message($id) {
        $ticket_message = "";
        if (empty($id)) {
            $ticket_message = "These are all feedback without the specific ticket.";
        } else {
            $result = $this->process_get_ticket_message($id);
            $ticket_message = $result[0]->message;
        }
        return $ticket_message;
    }

    private function process_get_ticket_message($id) {
        $select_col = "message";
        $pass_col = "id";
        $pass_value = $id;
        $result = $this->select_where($select_col, $pass_col, $pass_value);
        $ticket_message = $result->result();
        return $ticket_message;
    }

    public function ajax_get_ticket() {
//to do: get the id 
        $id = $this->input->post("ticket_id", TRUE);
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            if ($key == "message" || $key == "id") {
                $data[$key] = $value;
            }
        }
        $response = json_encode($data);
        echo $response;
    }

    public function update_ticket() {
        $str_project = $this->uri->segment(3);
        $str_ticket = $this->uri->segment(4);
        $str_no_replied = $this->uri->segment(5);

        $str_project = preg_replace('/[^0-9]/', '', $str_project);
        $str_ticket = preg_replace('/[^0-9]/', '', $str_ticket);
        if (!is_numeric($str_project)) {
            $project_id = "";
        } else {
            $project_id = $str_project;
        }

        if ($this->request_method == "POST") {
            $this->process_update_ticket($project_id);
        } else {
            $ticket_id = $str_ticket;
            if (is_numeric($ticket_id)) {
                $ticket = $this->get_where($ticket_id);
                $data = (array) $ticket;
            }
//get project details from db according to its id
//status dropdown
            $ticket_status_dropdown = Modules::run("tickets/ticket_status/show_all_ticket_status");
            $data['no_replied_pass'] = $str_no_replied;
            $data['project_id_pass'] = $project_id;
            $data['ticket_status_dropdown'] = $ticket_status_dropdown;
            $data['content_path'] = "tickets/edit_tickets/update_ticket_admin.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    private function process_update_ticket($project_id_pass) {
        $project_id = $project_id_pass;
        $data = $this->get_data_from_post_super();
        $id = $data['id'];
        $no_replied_pass = $data['no_replied_pass'];
        unset($data['no_replied_pass']);
        $this->_update($id, $data);
        /*
         * to do: how to refresh the page and stay in the same url?or to the other page?
         */
        if ($project_id != "") {
            redirect("tickets/show_ticket_specific/$project_id");
        } else if ($no_replied_pass != "") {
            redirect("tickets/show_ticket_no_replied");
        } else {
            redirect("tickets/show_tickets");
        }
    }

    public function delete_ticket() {
        $delete_id = $this->uri->segment(3);
        $project_id = $this->uri->segment(4);
        $this->_delete($delete_id);
        if ($project_id != "") {
            redirect("tickets/show_ticket_specific/$project_id");
        } else {
            redirect("tickets/show_tickets");
        }
    }

    public function post_ticket() {
        $project_id = $this->uri->segment(3);
        $this->process_post_ticket($project_id);
    }

    private function process_post_ticket($project_id_pass) {
//input:message,
//$this->user_id
//ip_address
//get data from post
        $data = $this->get_data_from_post_super();
        $user_id = $this->user_id;
        $ip_address = $this->acquire_ip_address();
        $project_id = $project_id_pass;
        $data['user_id'] = $user_id;
        $data['ip_address'] = $ip_address;
        $data['project_id'] = $project_id;

        $this->_insert($data);

        redirect("tickets/show_ticket_specific/$project_id");
    }

    private function acquire_ip_address() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if ($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if ($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if ($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if ($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

//to do: extract this function to the parent MY_controller class
//to do: how to give the date time? or just use the current time stamp in mysql?
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
                if ($key == "ticket_date") {
                    $ticket_date = $temp_data[$key];
                    $data[$key] = date("Y-m-d H:i:s", strtotime($ticket_date));
                    continue;
                }
                $data[$key] = $temp_data[$key];
            }
        }
        return $data;
    }

//    public function post_ticket_form($project_id) {
//        $data['project_id_pass'] = $project_id;
//        $this->load->view('edit_tickets/post_ticket.html', $data);
//    }

//这块不应该这样写，换种方式写，ticket必须是在project 下post出来才行。
//所以必须拿到project id才行
    public function add_ticket() {
        if ($this->request_method == "POST") {
            $this->process_add_ticket();
            redirect("tickets/show_ticket");
        } else {
            $data['content_path'] = "tickets/add_ticket.html";
            echo Modules::run('templates/backend', $data);
        }
    }

//暂时没用,currently, have not used this function.
    private function process_add_ticket() {

        $data = $this->get_data_from_post_super();
        $this->_insert($data);
    }

//currently, have not used this function.
//暂时没用，不知道什么原因，这样引用的时候，就只能被引用一次，
//而用modules:run的方法引入的东西就可以被调用很多次在循环里。
//希望可以找出原因，暂时还是理解不够深刻。
    public function edit_button() {
        if ($this->user_slug == "admin") {
            $this->load->view('operations/edit_button.html');
        } else {
            $this->load->view('operations/edit_button.html');
        }
    }

//暂时没用,currently, have not used this function.
    public function get_ticket($id) {
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }

    //暂时没用,currently, have not used this function.
    public function show_update_ticket() {
        $update_id = $this->uri->segment(3);
        $data = $this->get_ticket($update_id);

        $data['content_path'] = "tickets/update_ticket.html";
        echo Modules::run('templates/backend', $data);
//$this->load->view('update_estate_form.html', $data);
    }

}

?>
