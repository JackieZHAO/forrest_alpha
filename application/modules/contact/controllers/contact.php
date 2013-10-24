<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class Contact extends MX_Controller {

    public function __construct() {
        parent::__construct();
        Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->load->library("form_validation");
    }

    public function index() {
        //use the following code replace the current code when integration.
        //$this->load->view("contact.html");
        $data['content_path'] = "contact/contact.html";
        echo Modules::run('templates/backend', $data);
    }

    public function process_enquiry() {
        //to do: modify the following code to the site security function.
        $data = $this->get_data_from_post();

        if (empty($data)) {
            //to do
            redirect("contact/index");
        } else {
            
            $customer_id=$data['customer_id'];
            $customer_email=$data['email'];
            $customer_message=$data['message'];
            
            $message="";
            $message.="There is a new enquiry from customer:\n";
            $message.="Customer id is : ".$customer_id."\n";
            $message.="Customer email is : ".$customer_email."\n";
            $message.="Customer enquiry is :\n";
            $message.=$customer_message;
            
            $this->load->library('email');

            $this->email->from('noreply@127.0.0.1', 'Customer Enquiry');
            $this->email->to('info@127.0.0.1');

            $this->email->subject('Customer Enquiry');
            $this->email->message($message);

            $this->email->send();
        }
    }

    //notice: this function for testing, the real process_enquiry will get the data from security function.
    public function get_data_from_post() {
        $data = "";

        //to do: get the id from the session
        $this->form_validation->set_rules('customer_id', 'Customer_id', 'xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean');
        $this->form_validation->set_rules('message', 'Message', 'required|xss_clean');
        if ($this->form_validation->run($this) == FALSE) {

            redirect("contact/index");
        } else {
            $data['customer_id'] = $this->input->post('customer_id', TRUE);
            $data['email'] = $this->input->post('email', TRUE);
            $data['message'] = $this->input->post('message', TRUE);
            return $data;
        }
    }

}

?>
