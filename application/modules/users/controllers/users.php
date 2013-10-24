<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MY_Controller {

    private $request_method;
    public $model = "m_users";
    private $user_status_dropdown = "";
    private $user_type_dropdown = "";

    public function __construct() {
        parent::__construct();
        Modules::run("site_security/setup_logout_headers");

        $this->load->model('m_users');
        $this->load->model("m_user_type");
        $this->load->model("m_user_status");
        $this->request_method = $this->input->server('REQUEST_METHOD');
    }

    public function index() {
        $this->login();
    }

    // TODO: do form validation, clean up XSS kinda things
    public function login() {
        // Process the login form
        if ($this->request_method == "POST") {
            $this->process_login();
        }
        // If already logged in directly jump to the dashboard
        elseif (isset($this->session->userdata['user_id'])) {
            redirect('dashboard');
            //redirect('projects/show_project_specific');
        }
        // If not show the login form
        else {
            $data['content_path'] = "users/login_form.html";
            echo Modules::run('templates/frontend', $data);
        }
    }

    public function forget_email() {
        // TODO: Process the forget email as well
    }

    public function forget_password() {
        // Process the login form
        if ($this->request_method == "POST") {
            $this->send_reset_token();
        }
        // If not show the login form
        else {
            $data['content_path'] = "users/forget_password.html";
            echo Modules::run('templates/frontend', $data);
        }
    }

    // TODO: use the token_verified() function instead of 
    //	repeating the code.
    public function process_forget_password() {
        $token = $this->input->get('token');
        $email = $this->input->get('email');

        // TODO: do a bit of refactoring, the code is repeating
        //	on send_reset_token function as well.
        $email = $this->input->post('email');
        $arr_user = $this->get_where_custom('email', $email)->result();

        if (count($arr_user) != 1) {
            // TODO: use a proper view
            exit("Email address not found");
        } else {
            $user = $arr_user[0];
            $real_token = $user->token;

            // Check the date as well

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
                    $data['content_path'] = "users/password_reset_form.html";
                    $data['email'] = $this->input->get('email');
                    $data['token'] = $this->input->get('token');
                    echo Modules::run('templates/frontend', $data);
                }
            }
        }
    }

    private function token_verified($email, $token) {
        $arr_user = $this->get_where_custom('email', $email)->result();
        if (count($arr_user) != 1)
            return false;
        $user = $arr_user[0];
        $real_token = $user->token;
        if ($token == $real_token)
            return true;
        else
            return false;
    }

    public function reset_password() {
        $email = $this->input->post("email");
        $token = $this->input->post("token");
        $password1 = $this->input->post("password1");
        $password2 = $this->input->post("password2");

        if ($this->token_verified($email, $token) && ($password1 == $password2)) {
            $new_raw_password = $email . $password1;
            $hashed_password = Modules::run("site_security/create_password_hash", $new_raw_password);

            $arr_user = $this->get_where_custom('email', $email)->result();
            $user = $arr_user[0];
            $this->_update($user->id, array('password' => $hashed_password));

            // TODO: show a proper view to display a password reset conformation
            //	message
            echo "Password has been reset.";
        }
    }

    private function send_reset_token() {
        $email = $this->input->post('email');
        $arr_user = $this->get_where_custom('email', $email)->result();

        if (count($arr_user) != 1) {
            // TODO: use a proper view
            exit("Email address not found");
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

            $view_data["token_url"] = base_url() . "users/process_forget_password?email=" . $email . "&token=" . $update_data["token"];
            $this->_update($user->id, $update_data);

            $message = $this->load->view("forget_password_email.html", $view_data, true);

            $this->email->from('noreply@127.0.0.1');
            $this->email->to($email);
            $this->email->subject('Forget Password');
            $this->email->message($message);
            $this->email->send();
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('users/login');
    }

    //when the user want to register as a new one, use this function to check
    //whether this email has already existed in the user table.
    public function unique_email_check($email) {
        $arr_emails = $this->m_users->get_where_custom('email', $email);
        return ($arr_emails->num_rows == 0) ? true : false;
    }

    //get the user information by email.
    private function get_user($email) {
        $arr_user = $this->m_users->get_where_custom('email', $email)->result();
        $user = $arr_user[0];
        return $user;
    }

    //get the user information by id
    public function get_where($id) {
        $user = parent::get_where($id);
        // Hide the password hash
        unset($user->password);
        unset($user->token);
        unset($user->token_date);
        return $user;
    }

    // TODO: Do a real authintication, for example, 
    //	un activated users should not be able to login
    private function process_login() {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        if ($this->authenticate($email, $password)) {
            $user = $this->get_user($email);
            $user_id_pass = $user->id;
            $user_type_id = $user->type_id;
            //get user name
            $user_first_name=$user->first_name;
            $user_last_name=$user->last_name;
            $user_name=$user_first_name." ".$user_last_name;
            //
            $this->session->set_userdata(array('user_id' => $user->id));
            //warning:must get the user_slug after set the user id into session, because there is a 
            //site security function in the constructor of user_type to avoid get the function's value
            //from type the url directly in the browse.
            //to do: find a better way to handle this.
            $user_slug = $this->get_user_type_slug($user_type_id);
            //$this->session->set_userdata(array('user_id' => $user->id,'user_slug'=>$user_slug)); 
            $this->session->set_userdata(array('user_slug' => $user_slug));
            $this->session->set_userdata(array('user_name'=>$user_name));
            //redirect('projects/show_project_specific');
            redirect('dashboard');
        } else {
            // TODO: use a proper view instead
            //	In fact just throw an error message and redirect back to the login page
            echo "eMail or/and password is not correct.";
        }
    }

    private function authenticate($email, $password) {
        return $this->m_users->authenticate($email, $password);
    }

    /*
     * The following code is jackie's modification
     */

    //show active users
    //to do:just admin could see this.
    //to do: partner only can see its own.
    public function show_active_users() {
        $nav_user_menu = "";
        $temp_show_result = $this->get_all_active_users();
        $show_result = array();
        //to do: the following loop is repeating all the time,extracting it.
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
        //to do:the following code like a configuration and repeating all tht time,extracting it.
        //navigation menu
        $nav_user_menu = Modules::run("users/user_menus/nav_user_menu/index");
        $data['nav_user_menu'] = $nav_user_menu;
        //currently, have not used the $operations_button
        //modal operation button "update"
//        $operations_button = Modules::run("users/user_buttons/modal_user_operations_admin/update");
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

    //it is called by users/show_active_users.
    private function get_all_active_users() {
        $result = $this->get();
        return $result;
    }

    //only admin can use this function
    public function show_inactive_users() {
        echo Modules::run("users/user_temp/show_inactive_users");
    }

    //show users belong to different users
    public function show_users_specific() {
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
        $temp_show_result = $this->get_users_specific($user_id_pass);
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
        //currently, have not used $operations_button
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
        //currently, have not used $user_type_dropdown
        //user type dropdown
//        $data['user_type_dropdown'] = $user_type_dropdown;
        
        $data['show_result'] = $show_result;
        $data['content_path'] = "users/show_active_users_modified.html";
        echo Modules::run('templates/backend', $data);
        //}
    }

    //it is called by users/show_users_specific
    private function get_users_specific($user_id_pass) {
        //how to get the users which belong to this user?
        //just use the parent id, select all users whose parent_id=this user's id
        $col = "parent_id";
        $value = $user_id_pass;
        $query = $this->get_where_custom($col, $value);
        $result = $query->result();
        return $result;
    }
    
    public function get_assoc_project_value(){
        $array=$this->get_user_id_by_parent_id();
        $need_array=array();
        foreach ($array as $result){
            $temp_array=$result->id;
            array_push($need_array, $temp_array);
        }
        $total_sum=Modules::run("projects/get_value_by_user",$need_array);
        return $total_sum;
    }
    
    public function get_user_id_by_parent_id(){
        $select_col="id";
        $pass_col="parent_id";
        $pass_value=$this->session->userdata['user_id'];
        $result=$this->select_where($select_col, $pass_col, $pass_value)->result();
        return $result;
    }

    //this function is like a entry for every user including admin,
    //then, do a condition judgement.
    //every member call this function to show users no matter admin or others
    //then, this function makes the specific judgement.
    public function show_users() {
        //$user_id_pass = $this->session->userdata['user_id'];
        $this->user_slug = $this->session->userdata['user_slug'];
        //using slug to judge whether this user is admin or not.
        if ($this->user_slug == "admin") {
            $this->show_active_users();
        } else {
            $this->show_users_specific();
        }
    }

    //get parent id by user id
    public function get_parentId_by_id($user_id_pass) {
        $select_col = "parent_id";
        $pass_col = "id";
        $pass_value = $user_id_pass;
        $query = $this->select_where($select_col, $pass_col, $pass_value);
        $result = $query->result();
        return $result[0]->parent_id;
    }

    //get user type slug by type_id
    public function get_user_type_slug($user_type_id_pass) {
        $user_slug = Modules::run("users/user_type/get_user_type_slug", $user_type_id_pass);
        return $user_slug;
    }

    //get user id by email
    public function get_user_id_by_email($email_pass) {
        $select_col = "id";
        $pass_col = "email";
        $pass_value = $email_pass;
        $query = $this->select_where($select_col, $pass_col, $pass_value);
        $result = $query->result();
        return $result[0]->id;
    }

    //it is called when admin wants to update the information of users
    public function update_user() {
        if ($this->request_method == "POST") {
            $this->process_update_user();
        } else {
            $update_id = $this->uri->segment(3);
            if (is_numeric($update_id)) {
                $user = $this->get_where($update_id);
                $data = (array) $user;
            }
            //get user details from db according to its id
            //status dropdown
            $this->user_status_dropdown = Modules::run("users/user_status/show_all_user_status");
            $data['user_status_dropdown'] = $this->user_status_dropdown;
            //type dropdown
            $this->user_type_dropdown = Modules::run("users/user_type/show_all_user_type");
            $data['user_type_dropdown'] = $this->user_type_dropdown;
            $data['content_path'] = "users/edit_users/update_user.html";
            echo Modules::run('templates/backend', $data);
        }
    }

    //it is called by users/update_user
    private function process_update_user() {
        $data = $this->get_data_from_post_super();
        $id = $data['id'];
        $type_id = $data['type_id'];
        $status_id = $data['status_id'];
        $status = Modules::run("users/user_status/get_user_status", $status_id);
        $type = Modules::run("users/user_type/get_user_type", $type_id);
        if (($type != "Pending" && $type != "None") && ($status == "Registered" || $status == "Not Approved" || $status == "Active")) {
            //if it is satisfied this condition, update the user's information in user_temp
            $this->_update($id, $data);
        } else {
            //to do: must pass to a error page, and show the message.
            $error_message = "Please chose the right user status and the user type!User's type cannot be pending and none.
                              And user's status cannot be pending, lead,contact and none.";
            echo $error_message;
            exit();
        }

        /*
         * to do: how to refresh the page and stay in the same url?or to the other page?
         */
        redirect("users/show_active_users");
    }

    //暂时没有修改完
    public function add_user() {
        if ($this->request_method == "POST") {
            $this->process_add_project();
            redirect("projects/show_project_specific");
        } else {
            $user_slug = $this->user_slug;
            if ($user_slug == "admin") {
                $data['content_path'] = "projects/add_project_admin.html";
            } else {
                $data['content_path'] = "projects/add_project.html";
            }
            echo Modules::run('templates/backend', $data);
        }
    }

    //暂时没有修改完
    public function process_add_user() {
        
    }

    //this function is called when the user_temp registered, coz
    //the user_temp use ref_num to show the relationship between the 
    //client and it's parent. 
    //when registered as partner, admin arrange the ref_number for it?
    public function get_user_id_by_ref_number() {
        
    }

    //it is called by the partners want to view their users' details
    public function ajax_get_user() {
        //to do: get the id 
        $id = $this->input->post("user_id", TRUE);
        $result = $this->get_where($id);
        foreach ($result as $key => $value) {
            if ($key == "status_id") {
                $status_id = $value;
                $value = Modules::run("users/user_status/get_user_status", $status_id);
                $key = "user_status";
            }
            if ($key == "type_id") {
                $type_id = $value;
                $value = Modules::run("users/user_type/get_user_type", $type_id);
                $key = "user_type";
            }
            $data[$key] = $value;
        }
        $response = json_encode($data);
        echo $response;
    }

    //it is called by the others when get data from post
    //to do: a general form validation for all data.
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

    //currently, have not use this function, when user register, goes to user_temp's register
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

    //currently, have not use this function, when user wants to register as new one,
    //using the user_temp register function.
    private function process_registration() {
        //TODO: Do a bit of form validation, have a look at my sample for an example
        //jackie modify coz the register form is not matching the col in users table.
        $data_post = $this->input->post();
        $data = array();
        foreach ($data_post as $key => $value) {
            if ($key == "confirm_email" || $key == "ABN" || $key == "find_us") {
                continue;
            }
            $data[$key] = $value;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|callback_unique_email_check');

        if ($this->form_validation->run($this)) {
            $raw_password = $data['email'] . $data['password'];
            $hashed_password = Modules::run('site_security/create_password_hash', $raw_password);
            $data['password'] = $hashed_password;

            //  $arr_status = $this->m_user_status->get_where_custom("slug", "not-approved")->result();
            //  $data['member_status'] = $arr_status[0]->id;

            $this->m_users->_insert($data);
        } else {
            // TODO: this part has to be modified to refill the form and to populate the error messages and back to the view
            echo "Forbidden";
        }
    }

    //currently, have not used this function.
    //only admin can use this function
    public function show_all_users() {
        $temp_show_result = $this->get_all_users();
        $show_result = array();
        $nav_user_admin = "";
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
        //navigation menu
        $nav_user_admin = Modules::run("users/user_menus/nav_user_admin/index");
        $data['nav_user_admin'] = $nav_user_admin;
        //modal operation button "update"
        $operations_button = Modules::run("users/user_buttons/modal_user_operations_admin/update");
        $data['operations_button'] = $operations_button;
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
        //user status dropdown
        $user_status_dropdown = Modules::run("users/user_status/show_all_user_status");
        $data['user_status_dropdown'] = $user_status_dropdown;
        //user type dropdown
        $user_type_dropdown = Modules::run("users/user_type/show_all_user_type");
        $data['user_type_dropdown'] = $user_type_dropdown;
        $data['show_result'] = $show_result;
        $data['content_path'] = "users/show_active_users.html";
        echo Modules::run('templates/backend', $data);
    }

    //currently, have not used this function.
    //only admin can use this function,
    //it is called by users/show_all_users
    private function get_all_users() {
        $result = array();
        $result_active = $this->get();
        $result_inactive = Modules::run("users/user_temp/get_all_inactive_users");
        foreach ($result_active as $item_active) {
            array_push($result, $item_active);
        }
        foreach ($result_inactive as $item_inactive) {
            array_push($result, $item_inactive);
        }
        return $result;
    }

    //currently, have not used this function
    public function edit_user() {
        $data_active = $this->input->post("active", TRUE);
        if ($data_active == "active") {
            $this->update_user();
        } else {
            $this->active_user();
        }
    }

    //currently, have not used this function
    public function ajax_delete_user() {
        
    }

    //currently, have not used this function
    public function active_user() {
        $id = $this->input->post("id", TRUE);
        $status_id = $this->input->post("status_id", TRUE);
        $type_id = $this->input->post("type_id", TRUE);
        $data = Modules::run("users/user_temp/active", $id);
        $data->status_id = $status_id;
        $data->type_id = $type_id;
        unset($data->id);
        $this->_insert($data);
        Modules::run("users/user_temp/delete_user_temp", $id);
        redirect("users/show_users");
    }

}

?>