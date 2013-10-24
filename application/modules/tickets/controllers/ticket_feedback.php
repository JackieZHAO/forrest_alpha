<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Ticket_feedback extends MY_Controller {

    public $model = "m_ticket_feedback";
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

    //currently, have not used this function
    //showing all the feedback without specific parent ticket id
    public function show_ticket_feedback() {
        $temp_show_result = $this->get_all_ticket_feedback();
        $show_result = array();
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status;
            $type_id = $result->type;
            $status_type = Modules::run("tickets/ticket_feedback_status/get_ticket_feedback_status", $status_id);
            $type_type = Modules::run("tickets/ticket_feedback_type/get_ticket_feedback_type", $type_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $temp_array['type_type'] = $type_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        $value = $this->uri->segment(4);
        $ticket_message = Modules::run("tickets/get_ticket_message", $value);
        $data['ticket_message'] = $ticket_message;
        $data['ticket_id'] = "";
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_ticket_feedback.html";
        echo Modules::run('templates/backend', $data);
    }

    //showing all the feedback without specific parent ticket id
    public function get_all_ticket_feedback() {
        $result = $this->get();
        return $result;
    }

    //currently, have not used this function
    //showing all the feedback with the specific parent ticket id
    public function show_ticket_feedback_specific() {
        $col = "ticket_id";
        $value = $this->uri->segment(4);
        $temp_show_result = $this->get_all_ticket_feedback_specific($col, $value);
        $show_result = array();
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status;
            $type_id = $result->type;
            $status_type = Modules::run("tickets/ticket_feedback_status/get_ticket_feedback_status", $status_id);
            $type_type = Modules::run("tickets/ticket_feedback_type/get_ticket_feedback_type", $type_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $temp_array['type_type'] = $type_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        $ticket_message = Modules::run("tickets/get_ticket_message", $value);
        $data['ticket_message'] = $ticket_message;
        //to do: need to decide which content_path should be gave
        $data['ticket_id'] = $value;
        $data['show_result'] = $show_result;
        $data['content_path'] = "tickets/show_ticket_feedback.html";
        echo Modules::run('templates/backend', $data);
    }

    //showing all the feedback with the specific parent ticket id
    public function get_all_ticket_feedback_specific($col, $value) {
        $temp_result = $this->get_where_custom($col, $value);
        $result = $temp_result->result();
        return $result;
    }

    //new function to show the ticket feedback
    //only user and client can have this function
    public function show_ticket_feedback_by_specific() {
        if ($this->user_slug == "admin") {
            $thead = Modules::run("tickets/edit_ticket_feedbacks/thead_ticket_feedback_admin/show_thead");
        } else {
            $thead = Modules::run("tickets/edit_ticket_feedbacks/thead_ticket_feedback_client/show_thead");
        }
        $col_pass = "ticket_id";
        //get the ticket id value from url
        $value_pass = $this->uri->segment(4);
        //if it is empty, that means admin wants to show alll feedbacks together.
        //not link from the specific ticket number.
        if (empty($value_pass)) {
            $post_ticket_form = "";
            $temp_show_result = $this->get_all_ticket_feedback();
        } else if (is_numeric($value_pass) && $this->user_slug == "admin") {
            $post_ticket_form = "";
            $temp_show_result = $this->get_all_ticket_feedback_specific($col_pass, $value_pass);
        } else {
            $post_ticket_form = Modules::run("tickets/edit_ticket_feedbacks/post_ticket_feedback/post_ticket_feedback_form", $value_pass);
            $temp_show_result = $this->get_all_ticket_feedback_specific($col_pass, $value_pass);
        }
        //define variables
        $edit_button = "";
        $delete_button = "";
        //the array to store all results
        $show_result = array();
        //get the result
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            if (($this->user_slug == "admin" && is_numeric($value_pass)) || ($this->user_id == $result->user_id && $result->status != 2 && $this->user_slug != "admin")) {
                $edit_button = Modules::run("tickets/edit_ticket_feedbacks/edit_button_ticket_feedback/edit_button");
                $delete_button = Modules::run("tickets/edit_ticket_feedbacks/edit_button_ticket_feedback/delete_button");
            } else {
                $edit_button = "";
                $delete_button = "";
            }
            $status_id = $result->status;
            $type_id = $result->type;
            $status_type = Modules::run("tickets/ticket_feedback_status/get_ticket_feedback_status", $status_id);
            $type_type = Modules::run("tickets/ticket_feedback_type/get_ticket_feedback_type", $type_id);
            $temp_array = (array) $temp_result;
            $temp_array['edit_button'] = $edit_button;
            $temp_array['delete_button'] = $delete_button;
            $temp_array['status_type'] = $status_type;
            $temp_array['type_type'] = $type_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        //currently, there are two ways to show feedbacks
        //one is link from ticket, in doing so, need to get the ticket id
        //due to we need to get the ticket id for delete function's back redirect
        //another is show all feedbacks by admin, under this status, do not need 
        //the ticket id for delete function.
        $ticket_message = Modules::run("tickets/get_ticket_message", $value_pass);
        $data['ticket_message'] = $ticket_message;
        $data['ticket_id'] = $value_pass;
        $data['thead'] = $thead;
        $data['show_result'] = $show_result;
        $data['post_ticket_form'] = $post_ticket_form;
        $data['content_path'] = "tickets/show_ticket_feedback.html";
        echo Modules::run('templates/backend', $data);
    }

    //post the ticket feedback(admin, client)
    public function post_ticket_feedback() {
        $ticket_id = $this->uri->segment(4);
        $this->process_post_ticket_feedback($ticket_id);
    }

    //it is called by tickets/ticket_feedback/post_ticket_feedback
    private function process_post_ticket_feedback($ticket_id_pass) {
        //input:message,
        //$this->user_id
        //ip_address
        //get data from post
        $data = $this->get_data_from_post_super();
        $user_id = $this->user_id;
        $ip_address = $this->acquire_ip_address();
        $ticket_id = $ticket_id_pass;
        if ($this->user_slug == "admin") {
            //to do: using slug to get the type id;
            //not in the following way.
            //2:outgoing
            $data['status'] = 2;
            $data['type'] = 2;
        } else {
            //1:incoming
            $data['type'] = 1;
        }
        $data['user_id'] = $user_id;
        $data['ip_address'] = $ip_address;
        $data['ticket_id'] = $ticket_id;
        //insert the data
        $this->_insert($data);
        //redirect to the previous url
        redirect("tickets/ticket_feedback/show_ticket_feedback_by_specific/$ticket_id");
    }

    //get the ip address
    //it is called by tickets/ticket_feedback/process_post_ticket_feedback
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

    //暂时没用
    //currently, have not used this function.
    public function get_ticket_feedback($id) {
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            $data[$key] = $value;
        }
        return $data;
    }

    public function ajax_get_ticket_feedback() {
        //to do: get the id 
        $id = $this->input->post("ticket_feedback_id", TRUE);
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            $data[$key] = $value;
        }
        $response = json_encode($data);
        echo $response;
    }

    //暂时没用
    //currently, have not used this function.
    public function show_update_ticket_feedback() {
        $update_id = $this->uri->segment(4);
        $data = $this->get_ticket($update_id);

        $data['content_path'] = "tickets/update_ticket_feedback.html";
        echo Modules::run('templates/backend', $data);
        //$this->load->view('update_estate_form.html', $data);
    }

    //currently, have not used this function.
    //如果是管理员和不是管理员的时候，回的页面不一样。
    //如果是管理员增加的话，就不应该回到specific的页面
    public function update_ticket_feedback() {
        $ticket_id = $this->uri->segment(4);
        $this->process_update_ticket_feedback($ticket_id);
//        $data['content_path'] = "estates/update_estate_form.html";
//        echo Modules::run('templates/backend', $data);
    }

    //currently, have not used this function
    //it is called by tickets/ticket_feedback/update_ticket_feedback
    private function process_update_ticket_feedback($ticket_id_pass) {
        $ticket_id = $ticket_id_pass;
        $data = $this->get_data_from_post_super();
        $id = $data['id'];
        $this->_update($id, $data);
        /*
         * to do: how to refresh the page and stay in the same url?or to the other page?
         * to do: need to decide which content_path should be gave
         */
        redirect("tickets/ticket_feedback/show_ticket_feedback_specific/$ticket_id");
    }

//如果是管理员和不是管理员的时候，回的页面不一样。
    public function delete_ticket_feedback() {
        $delete_id = $this->uri->segment(4);
        $ticket_id = $this->uri->segment(5);
        $this->_delete($delete_id);
        //to do: need to decide which content_path should be gave
        redirect("tickets/ticket_feedback/show_ticket_feedback_specific/$ticket_id");
    }

    //如果是管理员和不是管理员的时候，回的页面不一样。
    //adding a feedback without specific parent ticket id
    //to do: modify it to get the parent ticket id from post?or from url
    public function add_ticket_feedback() {
        if ($this->request_method == "POST") {
            $this->process_add_ticket_feedback();
            //to do: need to decide which content_path should be gave
            redirect("tickets/ticket_feedback/show_ticket_feedback");
        } else {
            //to do: need to decide which content_path should be gave
            $data['content_path'] = "tickets/add_ticket_feedback.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    //adding a feedback without specific parent ticket id
    private function process_add_ticket_feedback() {

        $data = $this->get_data_from_post_super();
        $this->_insert($data);
    }

    //to do: extract this function to the parent MY_controller class
    //to do: how to give the date time? or just use the current time stamp in mysql?
    public function get_data_from_post_super() {
        $data = "";
        //get all the input post data
        $temp_data = $this->input->post(NULL, TRUE);
        foreach ($temp_data as $key => $value) {
            $this->form_validation->set_rules($key, 'V' . $key, 'xss_clean');
        }
        if ($this->form_validation->run($this) == FALSE) {
            //to do: if it is false, which function or page we need to redirect to?
        } else {
            foreach ($temp_data as $key => $value) {
                if ($key == "feedback_date") {
                    $ticket_date = $temp_data[$key];
                    $data[$key] = date("Y-m-d H:i:s", strtotime($ticket_date));
                    continue;
                }
                $data[$key] = $temp_data[$key];
            }
        }
        return $data;
    }

}

?>