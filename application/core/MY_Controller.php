<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class MY_Controller extends MX_Controller {

    public $model;

    public function __construct() {
        parent::__construct();
        $this->load->model($this->model);
    }

    public function select($select_col) {

        return $this->{$this->model}->select($select_col);
    }

    public function select_where($select_col, $pass_col, $pass_value) {

        return $this->{$this->model}->select_where($select_col, $pass_col, $pass_value);
    }

    /*
     * get all records
     */

    public function get() {
        return $this->{$this->model}->get();
    }

    /*
     * get records with limit and offset, like pagination
     */

    public function get_with_limit($limit, $offset) {
        return $this->{$this->model}->get_with_limit($limit, $offset);
    }

    /*
     * get the specific record according to its id
     */

    public function get_where($id) {
        return $this->{$this->model}->get_where($id);
    }

    /*
     * get the spcific record according to its col and value
     */

    public function get_where_custom($col, $value) {
        return $this->{$this->model}->get_where_custom($col, $value);
    }

    /*
     * inser the record
     */

    public function _insert($data) {
        $this->{$this->model}->_insert($data);
    }

    /*
     * update the record
     */

    public function _update($id, $data) {
        $this->{$this->model}->_update($id, $data);
    }

    /*
     * delete the record
     */

    public function _delete($id) {
        return $this->{$this->model}->_delete($id);
    }

    /*
     * count how many records there according to the column and value
     */

    public function count_where($column, $value) {
        return $this->{$this->model}->count_where($column, $value);
    }

    /*
     * count all records number
     */

    public function count_all() {

        return $this->{$this->model}->count_all();
    }

    /*
     * get the max record according to its id
     */

    public function get_max() {
        return $this->{$this->model}->get_max();
    }

    /*
     * using a cusom query
     */

    public function _custom_query($mysql_query) {
        return $this->{$this->model}->_custom_query($mysql_query);
    }

}

?>
