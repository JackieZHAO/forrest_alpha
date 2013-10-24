<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_temp extends MY_Controller {

    private $request_method;
    public $model = "m_user_temp";

    public function __construct() {
        parent::__construct();
        //Modules::run('site_security/make_sure_is_logged_in');
        Modules::run('site_security/setup_logout_headers');
        $this->request_method = $this->input->server('REQUEST_METHOD');
        $this->load->library('form_validation');
    }

    //show inactive users
    //to do: just admin could see this
    public function show_inactive_users() {
        $nav_user_menu = "";
        $temp_show_result = $this->get_all_inactive_users();
        $show_result = array();
        //to do: extract the following loop as a function coz this kind of thing was 
        //repeating all the time.
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status_id;
            $status_type = Modules::run("users/user_status/get_user_status", $status_id);
            $type_id = $result->type_id;
            $type_type = Modules::run("users/user_type/get_user_type", $type_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $temp_array['type_type'] = $type_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        //to do: extract the following code as a function coz this kind of thing like a
        //configruation function.
        //navigation menu
        $nav_user_menu = Modules::run("users/user_menus/nav_user_menu/index");
        $data['nav_user_menu'] = $nav_user_menu;
        //currently, have not used the following $operations_button
        //modal operation button "active"
//        $operations_button = Modules::run("users/user_buttons/modal_user_operations_admin/active");
//        $data['operations_button'] = $operations_button;
        //edit button
        $edit_button = Modules::run("users/user_buttons/show_user_operations_admin/edit_button");
        $data['edit_button'] = $edit_button;
        //delete button
        $delete_button = Modules::run("users/user_buttons/show_user_operations_admin/delete_button");
        $data['delete_button'] = $delete_button;
        //thead edit
        $thead_edit = "Edit";
        $data['thead_edit'] = $thead_edit;
        //thead delete
        $thead_delete = "Delete";
        $data['thead_delete'] = $thead_delete;
        //currently, have not used the following $user_status_dropdown.
        //user status dropdown
//        $user_status_dropdown = Modules::run("users/user_status/show_all_user_status");
//        $data['user_status_dropdown'] = $user_status_dropdown;
//        //currently, have not used the following $user_type_dropdown.
        //user type dropdown
//        $user_type_dropdown = Modules::run("users/user_type/show_all_user_type");
//        $data['user_type_dropdown'] = $user_type_dropdown;
        $data['show_result'] = $show_result;
        $data['content_path'] = "users/show_active_users_modified.html";
        echo Modules::run('templates/backend', $data);
    }

    //it is called by user_temp/show_inactive_users
    public function get_all_inactive_users() {
        $result = $this->get();
        return $result;
    }

    //show potential users in user_temp whom belongs to different users
    public function show_users_temp_specific() {
        //because this function was not called by admin, so, normal people do
        //not have the following value, so, define them as null;
        //because the view of show users is the same view html file, so, if 
        //do not define and pass the following variables, the view page will
        //show some variables were not defined error.
        $nav_user_menu = "";
        //currently, have not used $operations_button
//        $operations_button = "";
        $edit_button = "";
        $delete_button = "";
        $thead_edit = "View";
        $thead_delete = "";
        //currently, have not used $user_status_dropdown, $user_type_dropdown
//        $user_status_dropdown = "";
//        $user_type_dropdown = "";
        $user_id_pass = $this->session->userdata['user_id'];

        //how to get the users which belong to this user?
        //just use the parent id, select all users whose parent_id=this user's id
        $temp_show_result = $this->get_users_temp_specific($user_id_pass);
        $show_result = array();
        //to do: extract the following loop coz it is repeated all the time.
        foreach ($temp_show_result as $result) {
            $temp_result = $result;
            $status_id = $result->status_id;
            $status_type = Modules::run("users/user_status/get_user_status", $status_id);
            $type_id = $result->type_id;
            $type_type = Modules::run("users/user_type/get_user_type", $type_id);
            $temp_array = (array) $temp_result;
            $temp_array['status_type'] = $status_type;
            $temp_array['type_type'] = $type_type;
            $need_object = (object) $temp_array;
            array_push($show_result, $need_object);
        }
        //to do: extract the following codes, it is like a configuration.
        //navigation menu
        $nav_user_menu = Modules::run("users/user_menus/nav_user_menu/index");
        $data['nav_user_menu'] = $nav_user_menu;
        //currently,have not used $operations_button
        //modal operation button 
//        $data['operations_button'] = $operations_button;
        //edit button
        $edit_button = Modules::run("users/user_buttons/show_user_operations_partner/edit_button");
        $data['edit_button'] = $edit_button;
        //delete button
        $data['delete_button'] = $delete_button;
        //thead edit
        $data['thead_edit'] = $thead_edit;
        //thead delete
        $data['thead_delete'] = $thead_delete;
        //currently, have not used $user_status_dropdown
        //user status dropdown
//        $data['user_status_dropdown'] = $user_status_dropdown;
//        //currently, have not used $user_type_dropdown
        //user type dropdown
//        $data['user_type_dropdown'] = $user_type_dropdown;
        $data['show_result'] = $show_result;
        $data['content_path'] = "users/show_active_users_modified.html";
        echo Modules::run('templates/backend', $data);
        //}
    }

    //it is called by user_temp/show_users_specific
    private function get_users_temp_specific($user_id_pass) {
        //how to get the users which belong to this user?
        //just use the parent id, select all users whose parent_id=this user's id
        $col = "parent_id";
        $value = $user_id_pass;
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }

    //it is called when the user want to register
    //register should go to the uset temp table at first
    public function register() {
        // Process the registration form
        if ($this->request_method == "POST") {
            $this->process_registration();
        } elseif (isset($this->session->userdata['user_id'])) {
            // TODO: I don't know the best place to redirect, I will think when Im free
            redirect('dashboard');
        } else {
            $data['content_path'] = "users/registration_form.html";
            echo Modules::run('templates/frontend', $data);
        }
    }

    //when register, check whether the email is unique or not
    //should check two tables, users and user_temp
    public function unique_email_check($email) {
//        $arr_emails = $this->m_users->get_where_custom('email', $email);
//        return ($arr_emails->num_rows == 0) ? true : false;
        $check_user_email = Modules::run("users/unique_email_check", $email);
        $check_user_temp_email = $this->unique_email_check_user_temp($email);

        if ($check_user_email && $check_user_temp_email) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    //it is called by user_temp/unique_email_check
    public function unique_email_check_user_temp($email) {
        $arr_emails = $this->get_where_custom('email', $email);
        return ($arr_emails->num_rows == 0) ? true : false;
    }

    //when doing registration, it is called by user_temp/register
    private function process_registration() {
        //TODO: Do a bit of form validation, have a look at my sample for an example
        //jackie modify coz the register form is not matching the col in users table.
        $data_post = $this->input->post(NULL, TRUE);
        $data = array();
        foreach ($data_post as $key => $value) {
            $this->form_validation->set_rules($key, 'V' . $key, 'xss_clean');
            if ($key == "confirm_email" || $key == "ABN" || $key == "find_us") {
                continue;
            }
            $data[$key] = $value;
        }

        $email = $data['email'];
        if (!$this->unique_email_check($email)) {
            echo "The email has already existed.Please use another one";
        } else {
            if ($this->form_validation->run($this)) {
                $raw_password = $data['email'] . $data['password'];
                $hashed_password = Modules::run('site_security/create_password_hash', $raw_password);
                $data['password'] = $hashed_password;

                //  $arr_status = $this->m_user_status->get_where_custom("slug", "not-approved")->result();
                //  $data['member_status'] = $arr_status[0]->id;

                $this->_insert($data);
                //to do: must find a better url to redirect, not the following one.
                redirect("users/index");
            } else {
                // TODO: this part has to be modified to refill the form and to populate the error messages and back to the view
                echo "Forbidden";
            }
        }
        //do not want to use the following callback function.
        // $this->form_validation->set_rules('email', 'Email', 'required|callback_unique_email_check');
    }

    //get the user_temp detail by id
    public function get_where($id) {
        $user = parent::get_where($id);
        // Hide the password hash
        unset($user->password);
        return $user;
    }

    //when the admin active this user's information,
    //then send the email to the user, and ask the user to click
    //a link to set the password.After user set the password, the user's detail
    //will be deleted from the user_temp table and insert into the user table.
    public function active_user($data) {
        $id = $data['id'];
        $email = $data['email'];
        //status_id, type_id keep the original status and type of the user
        //status_tag, type_tag, keep the admin's modification for this user.
        //after the user set the password and all detail will be tranfered from user_temp to user table,
        //at that time, status_tag will replace status_id, type_tag will replace type_id
        $status_tag = $data['status_id'];
        $type_tag = $data['type_id'];
        $tmep_store_status_type = array(
            "status_tag" => $status_tag,
            "type_tag" => $type_tag
        );
        //keep status_tag and type_tag for temporary using.
        $this->_update($id, $tmep_store_status_type);
        //send the email to the client to set the password
        $this->send_password($email);
        //
        redirect("users/show_inactive_users");
    }

    //get the email by id
    public function get_user_temp_email_by_id($id) {
        $select_col = "email";
        $pass_col = "id";
        $pass_value = $id;
        $query = $this->select_where($select_col, $pass_col, $pass_value);
        $result = $query->result()[0]->email;
        return $result;
    }

    //get the id by email
    public function get_user_temp_id_by_email($email) {
        $select_col = "id";
        $pass_col = "email";
        $pass_value = $email;
        $query = $this->select_where($select_col, $pass_col, $pass_value);
        $result = $query->result()[0]->id;
        return $result;
    }

    //it is called by user_temp/active_user
    public function send_password($email) {
        $this->send_reset_token($email);
    }

    //it is called by user_temp/send_password.
    public function send_reset_token($email) {
        $arr_user = $this->get_where_custom("email", $email)->result();
        if (count($arr_user) != 1) {
            // TODO: use a proper view
            $error_message = "Email address not found";
            exit($error_message);
        } else {
            $user = $arr_user[0];

            // Load library and stuffs
            $this->load->library('email');
            $this->load->helper('string');
            $this->load->helper('date');

            // Set the token and the token date
            $token = hash('SHA512', random_string('alnum', 16));
            $token_date = mdate("%Y-%m-%d %h:%i", time());

            $update_data = array(
                "token" => $token,
                "token_date" => $token_date
            );

            $view_data["token_url"] = base_url() . "users/user_temp/process_set_password?email=" . $email . "&token=" . $update_data["token"];
            $this->_update($user->id, $update_data);

            $message = $this->load->view("set_password_email.html", $view_data, true);

            $this->email->from('noreply@127.0.0.1');
            $this->email->to($email);
            $this->email->subject('Set your Password');
            $this->email->message($message);
            $this->email->send();
        }
    }

    //when the user click the link in his email, call this function
    public function process_set_password() {
        $token = $this->input->get('token', TRUE);
        $email = $this->input->get('email', TRUE);

        // TODO: do a bit of refactoring, the code is repeating
        //	on send_reset_token function as well.
        $arr_user = $this->get_where_custom("email", $email)->result();

        if (count($arr_user) != 1) {
            // TODO: use a proper view
            exit("Email address not found");
        } else {
            $user = $arr_user[0];
            $real_token = $user->token;

            // to do:Check the date as well

            if ($token == $real_token) {
                $to_time = strtotime("2008-12-13 10:42:00");
                $from_time = strtotime("2008-12-13 10:21:00");
                // TODO critical: there is a bug here, it always returns 21
                $minutes_past = round(abs($to_time - $from_time) / 60, 2);

                // TODO critical: fix the bug first. Then
                //	 get the validation period from some sort of config
                //	instead of directly hard coding as 6 hours
                $validation_period = 360; // time in minutes, eg. 360 minutes = 6 hrs
                if ($minutes_past > $validation_period) {
                    // TODO: load a proper view to show the token expired message
                    //	perhaps, show a link to reset the token as well.
                } else {
                    $data['content_path'] = "users/set_password_form.html";
                    $data['email'] = $this->input->get('email', TRUE);
                    $data['token'] = $this->input->get('token', TRUE);
                    echo Modules::run('templates/frontend', $data);
                }
            }
        }
    }

    //when the client set the password, click the button, calling this function
    public function reset_password() {
        $email = $this->input->post("email");
        $token = $this->input->post("token");
        $password1 = $this->input->post("password1");
        $password2 = $this->input->post("password2");

        if ($this->token_verified($email, $token) && ($password1 == $password2)) {
            $new_raw_password = $email . $password1;
            $hashed_password = Modules::run("site_security/create_password_hash", $new_raw_password);

            $data = $this->get_where_custom("email", $email)->result()[0];
            $arr_data = (array) $data;
            //set the password.
            $arr_data['password'] = $hashed_password;
            //get the status_tag
            $status_tag = $arr_data['status_tag'];
            //get the type_tag
            $type_tag = $arr_data['type_tag'];
            //set the new status_id
            $arr_data['status_id'] = $status_tag;
            //set the new type_id
            $arr_data['type_id'] = $type_tag;
            //unset the following attributes,coz the user table
            //does not need these attributes.
            unset($arr_data['id']);
            unset($arr_data['comment']);
            unset($arr_data['token']);
            unset($arr_data['token_date']);
            unset($arr_data['status_tag']);
            unset($arr_data['type_tag']);
            $data = (object) $arr_data;
            //call user's insert function to insert all these information.
            Modules::run("users/_insert", $data);

            //get the user_temp id in order to delete the user's information in user_temp table.
            $user_temp_delete_id = $this->get_user_temp_id_by_email($email);
            //delete the user's information from user_temp table, after the user was actived as a client or partner.
            $this->delete_user_temp($user_temp_delete_id);
            // TODO: show a proper view to display a password reset conformation
            //	message
            $success_message = "Password has been reset.";
            echo $success_message;
        }
    }

    //it is called by the user_temp/reset_password
    private function token_verified($email, $token) {
        $arr_user = $this->get_where_custom('email', $email)->result();
        if (count($arr_user) != 1) {
            return false;
        }
        $user = $arr_user[0];
        $real_token = $user->token;
        if ($token == $real_token) {
            return true;
        } else {
            return false;
        }
    }

    //it is called by user_temp/reset_password
    //after active the user, delete the user information in the user_temp table.
    public function delete_user_temp($id) {
        parent::_delete($id);
    }

    //it is called when the admin update the modification of user_temp's related information
    //and also active the user_temp to user.
    public function update_user_temp() {
        if ($this->request_method == "POST") {
            $this->process_update_user_temp();
        } else {
            $update_id = $this->uri->segment(4);
            //check whether there is a number: passing update_id
            if (is_numeric($update_id)) {
                //get user details from db according to its id
                $user = $this->get_where($update_id);
                $data = (array) $user;
            }
            //status dropdown
            $this->user_status_dropdown = Modules::run("users/user_status/show_all_user_status");
            $data['user_status_dropdown'] = $this->user_status_dropdown;
            //type dropdown
            $this->user_type_dropdown = Modules::run("users/user_type/show_all_user_type");
            $data['user_type_dropdown'] = $this->user_type_dropdown;
            $data['content_path'] = "users/edit_users/update_user_temp.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    //it is called by update_user_temp
    private function process_update_user_temp() {
        $data = $this->get_data_from_post_super();
        $id = $data['id'];
        //use status id and type id to judge what function should be called.
        //only status is "active", get the user details from user_temp to user.
        $type_id = $data['type_id'];
        $status_id = $data['status_id'];
        $status = Modules::run("users/user_status/get_user_status", $status_id);
        $type = Modules::run("users/user_type/get_user_type", $type_id);
        if ($type == "Pending" && ($status == "Pending" || $status == "Lead" || $status == "Contact" || $status == "None")) {
            //if it is satisfied this condition, update the user's information in user_temp
            $this->_update($id, $data);
        } else if ($type != "Pending" && ($status == "Registered" || $status == "Not Approved" || $status == "Active")) {
            //it it is satisfied this condition, call the active function.
            $this->active_user($data);
            //get the user detail from user_temp, then insert it into user
        } else {
            //to do: must pass to a error page, and show the message.
            $error_message = "Please chose the right user status and the user type.If you want to active ths user, you cannot chose the type as Pending!.";
            echo $error_message;
            exit();
        }

        /*
         * to do: how to refresh the page and stay in the same url?or to the other page?
         */
        redirect("users/show_inactive_users");
    }

    //it is called by the partners want to view their users' details
    public function ajax_get_user_temp() {
        //to do: get the id 
        $id = $this->input->post("user_id", TRUE);
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            if($key=="status_id"){
                $status_id = $value;
                $value = Modules::run("users/user_status/get_user_status", $status_id);
                $key="user_status";
            }  
            if($key=="type_id"){
                $type_id = $value;
                $value = Modules::run("users/user_type/get_user_type", $type_id);
                $key="user_type";
            } 
            $data[$key] = $value;
        }
        unset($data['token']);
        unset($data['token_date']);
        unset($data['status_tag']);
        unset($data['type_tag']);
        $response = json_encode($data);
        echo $response;
    }

    //it is called when the other functions want to get the post data.
    //to do: form validation. can we create a general form validation?
    public function get_data_from_post_super() {
        $data = "";
        $temp_data = $this->input->post(NULL, TRUE);
        foreach ($temp_data as $key => $value) {
            $this->form_validation->set_rules($key, 'V' . $key, 'xss_clean');
        }

        if ($this->form_validation->run($this) == FALSE) {
            //to do: if it is false, which function or page we need to redirect to?
        } else {
            foreach ($temp_data as $key => $value) {
                if ($key == "registration_date") {
                    $start_date = $temp_data[$key];
                    $data[$key] = date("Y-m-d H:i:s", strtotime($start_date));
                    continue;
                }
                $data[$key] = $temp_data[$key];
            }
        }
        return $data;
    }

    //currently, have not used this function.
    //it was called by users/active_user
    public function active($id) {
        $data = parent::get_where($id);
        return $data;
    }

    //currently, have not used this function
    public function ajax_delete_user_temp() {
        
    }

}