<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Thead_ticket_feedback_client extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function show_thead() {
        $this->load->view('edit_ticket_feedbacks/thead_ticket_feedback_client.html');
    }
    
}
?>