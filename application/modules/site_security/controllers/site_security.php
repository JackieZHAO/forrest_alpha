<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed');


class Site_security extends MX_Controller 
{
	// TODO: Right piece of data for the right user
	//	For example, when a user logs in as a buyer
	//	the user should not be able to access the controller
	//	functions related to the sales person.

	public function create_password_hash($plain_password)
	{
		$salt = $this->config->item('encryption_key');
		return hash('SHA512', $plain_password . $salt);
	}

	public function make_sure_is_logged_in()
	{
		// TODO: Load a proper view to display the error message
		if (!isset($this->session->userdata['user_id']))
			redirect("users/login");
	}
        public function make_sure_is_admin(){
            if($this->session->userdata['user_slug']!="admin"){
                redirect("users/login");
            }
        }

	public function setup_logout_headers()
	{
		header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: post-check=0, pre-check=0', false);
		header('Pragma: no-cache');
	}
}