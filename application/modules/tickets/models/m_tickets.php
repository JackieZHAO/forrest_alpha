<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class M_tickets extends My_model {

    public $table = 'tickets';

    public function __construct() {
        parent::__construct();
    }

//    public function join_project_ticket($project_id_pass) {      
//        $project_id=$project_id_pass;
//        $this->db->select('*');
//        $this->db->from('tickets');
//        $this->db->join('project_user', 'project_user.ticket_id = tickets.id');
//        $this->db->where('project_user.project_id', $project_id);
//        $query = $this->db->get();
//        
//        return $query;
//    }

}

?>
