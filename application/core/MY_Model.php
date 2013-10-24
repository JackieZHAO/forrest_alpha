<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * A base model providing CRUD, pagination and validation.
 * 
 * Install this file as application/core/MY_Model.php
 * @link		http://developer13.com
 */
class My_model extends CI_Model {
    /*
     * -------------------------------------------------------------------------------------------------------------------------------------------
     * -------------------------------------------------------------------------------------------------------------------------------------------
     */

    public $table;

    public function __construct() {
        parent::__construct();
    }

    /*
     * get all records
     */

    public function select($select_col) {
        $this->db->select($select_col);
        return $this->db->get($this->table);
    }

    public function select_where($select_col, $pass_col, $pass_value) {
        $this->db->select($select_col);
        $this->db->where($pass_col, $pass_value);
        return $this->db->get($this->table);
    }

    public function get($order_by = "id") {
//                $this->db->where("id >",0);
        $this->db->order_by($order_by, "desc");
        return $this->db->get($this->table)->result();
    }

    /*
     * get records with limit and offset, like pagination
     */

    public function get_with_limit($limit, $offset, $order_by = "id") {
        $this->db->limit($limit, $offset);
        $this->db->order_by($order_by);
        return $this->db->get($this->table)->result();
    }

    /*
     * get the specific record according to its id
     */

    public function get_where($id) {
        
        $this->db->where('id', $id);
        $this->db->order_by('id', "desc");
        $arr_result = $this->db->get($this->table)->result();

        return (count($arr_result) == 1) ? $arr_result[0] : null;
    }

    /*
     * get the spcific record according to its col and value
     */

    public function get_where_custom($col, $value) {
        $this->db->where($col, $value);
        $this->db->order_by('id', "desc");
        return $this->db->get($this->table);
    }

    /*
     * inser the record
     */

    public function _insert($data) {
        $this->db->insert($this->table, $data);
    }

    /*
     * update the record
     */

    public function _update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    /*
     * delete the record
     */

    public function _delete($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete($this->table);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * count how many records there according to the column and value
     */

    public function count_where($column, $value) {
        $this->db->where($column, $value);
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    /*
     * count all records number
     */

    public function count_all() {
        $query = $this->db->get($this->table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    /*
     * get the max record according to its id
     */

    public function get_max() {
        $this->db->select_max('id');
        $query = $this->db->get($this->table);
        $row = $query->row();
        $id = $row->id;
        return $id;
    }

    /*
     * using a cusom query
     */

    public function _custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
    }

    /*
     * -------------------------------------------------------------------------------------------------------------------------------------------
     * -------------------------------------------------------------------------------------------------------------------------------------------
     */
}