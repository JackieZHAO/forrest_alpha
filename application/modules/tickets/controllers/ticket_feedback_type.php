<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Ticket_feedback_type extends MY_Controller {

    public $model = "m_ticket_feedback_type";
    private $request_method;

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library("form_validation");
        $this->load->library("pagination");
    }
    
    public function get_all_ticket_feedback_type() {
        $result = $this->get();
        return $result;
    }

    public function show_all_ticket_feedback_type() {
        $show_result = $this->get_all_ticket_feedback_type();
        $data['show_result'] = $show_result;

        $this->load->view('show_ticket_feedback_type.html', $data);
    }

    public function get_ticket_feedback_type($type_id) {
        if ($type_id != 0) {
            $ticket_type = $this->get_where($type_id);
            $type = $ticket_type->name;
            return $type;
        } else {
            $type = "None";
            return $type;
        }
    }

}

?>