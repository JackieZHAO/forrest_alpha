<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Post_ticket_feedback extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function post_ticket_feedback_form($ticket_id_pass) {
        $data['ticket_id_pass'] = $ticket_id_pass;
        $this->load->view('edit_ticket_feedbacks/post_ticket_feedback.html', $data);
    }
    
}
?>