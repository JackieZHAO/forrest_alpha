<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Ticket_feedback_status extends MY_Controller {

    public $model = "m_ticket_feedback_status";
    private $request_method;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library("form_validation");
        $this->load->library("pagination");
    }
    
    public function get_all_ticket_feedback_status() {
        $result = $this->get();
        return $result;
    }

    public function show_all_ticket_feedback_status() {
        $show_result = $this->get_all_ticket_feedback_status();
        $data['show_result'] = $show_result;

        $this->load->view('show_ticket_feedback_status.html', $data);
    }

    public function get_ticket_feedback_status($status_id) {
        if ($status_id != 0) {
            $ticket_status = $this->get_where($status_id);
            $status_type = $ticket_status->name;
            return $status_type;
        } else {
            $status_type = "None";
            return $status_type;
        }
    }
}

?>