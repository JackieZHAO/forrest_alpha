<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Post_ticket_operations_client extends MX_Controller{
    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
    }
    
    public function post_ticket_form($project_id) {
        $data['project_id_pass'] = $project_id;
        $this->load->view('edit_tickets/post_ticket.html', $data);
    }
    
}
?>