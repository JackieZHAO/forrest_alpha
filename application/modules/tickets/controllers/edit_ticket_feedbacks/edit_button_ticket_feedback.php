<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Edit_button_ticket_feedback extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function edit_button(){
        $this->load->view("edit_ticket_feedbacks/edit_button_ticket_feedback.html");
    }
    
    public function delete_button(){
        $this->load->view("edit_ticket_feedbacks/delete_button_ticket_feedback.html");
    }
}
?>
