<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_users extends My_model{
    public $table = 'users';

    public function __construct() {
        parent::__construct();
    }
    
    public function authenticate($email, $password)
    {
        $raw_password = $email . $password;

        $hashed_password = Modules::run('site_security/create_password_hash', $raw_password);

        $this->db->where('password', $hashed_password);
        $this->db->where('email', $email);
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();

        return ($num_rows > 0) ? true : false;
    }
    
}