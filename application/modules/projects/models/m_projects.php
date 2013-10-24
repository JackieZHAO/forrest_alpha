<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class M_projects extends My_model {

    public $table = 'projects';

    public function __construct() {
        parent::__construct();
    }

    public function select_where_in($select_col_pra, $pass_col_pra, $pass_value_pra) {
        $select_col = $select_col_pra;
        $pass_col = $pass_col_pra;
        $pass_value_array = $pass_value_pra;
        $this->db->select($select_col);
        $this->db->where_in($pass_col, $pass_value_array);
        $query = $this->db->get($this->table);
        $result = $query->result();
        return $result;
    }

//    public function join_project_user($user_id_pass) {      
//        $user_id=$user_id_pass;
//        $this->db->select('*');
//        $this->db->from('projects');
//        $this->db->join('project_user', 'project_user.project_id = projects.id');
//        $this->db->where('project_user.user_id', $user_id);
//        $query = $this->db->get();
//        
//        return $query;
//    }
}

?>
