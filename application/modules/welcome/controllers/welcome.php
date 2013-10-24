<?php 
	if (!defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MX_Controller 
{
	public function index()
	{
		$data['content_path'] = "welcome/home.html";
		echo Modules::run('templates/frontend', $data);
	}
}