<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Principles extends MY_Controller {

    /**
     * Class constructor
     *
     * Loads required models, loads the hook class and add a hook point
     *
     * @return  void
     */
    public function __construct()
    {
               
        parent::__construct();

        $model_list = [
            'user/Users_model' => 'MUsers',
            'package/Packages_model' => 'MPackages',
            'sites/Sites_model' => 'MSites',
            'settings/Payment_settings_model' => 'MPayments',
            'settings/Core_settings_model' => 'MCores',
            'settings/Whitelabel_model' => 'MWhitelabel',
        ];
        $this->load->model($model_list);

        $this->hooks = load_class('Hooks', 'core');
        $this->data = [];
        $this->data['whitelabel_general'] = $this->MWhitelabel->load();

        /** Hook point */
        $this->hooks->call_hook('auth_construct');

        //$this->output->enable_profiler(TRUE);
    }

    /**
     * User login from other side and do session login
     * Getting E-mail
     *
     * @return user 
     */
    public function login(){
        //print_r($_REQUEST);
        $email = $_REQUEST['email'];
        $user = $this->MUsers->get_by_email($email);
        if($user){
            $data['user_id'] = $user['id'];
            $data['package_id'] = $user['package_id'];
            $data['user_fname'] = $user['first_name'];
            $data['user_lname'] = $user['last_name'];
            $data['user_email'] = $user['email'];
            $data['user_type'] = $user['type'];
            $this->session->set_userdata($data);
            redirect('sites', 'refresh');
        }else{
            $this->session->set_flashdata('error', $this->lang->line('auth_index_validation_error'));
                redirect('auth', 'refresh');
        }
    }
    
    public function user_register(){
        print_r($_REQUEST);
        $data = array(
            'package_id'                    => 0,
            'username'                      => $_REQUEST['email'],
            'email'                         => $_REQUEST['email'],
            'password'                      => substr(do_hash($_REQUEST['password']), 0, 32),
            'first_name'                    => $_REQUEST['first_name'],
            'last_name'                     => $_REQUEST['last_name'],
            'stripe_cus_id'                 => NULL,
            'current_subscription_gateway'  => '',
            'type'                          => 'User',
            'status'                        => 'Active',
            'activation_code'               => substr(do_hash($_REQUEST['email']), 0, 32),
            'created_at'                    => date('Y-m-d H:i:s', time())
        );
        $this->db->insert('users', $data);

        return $this->db->insert_id();
    }

    /**
     * Controller desctruct method for custom hook point
     *
     * @return void
     */
    public function __destruct()
    {
        /** Hook point */
        $this->hooks->call_hook('auth_destruct');
    }

}