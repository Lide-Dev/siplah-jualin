<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_Supervisor_controller extends Admin_Core_Controller {


    public function index(){
        $this->load->view('admin/includes/_header');
        $this->load->view('admin/supervisor/index');
        $this->load->view('admin/includes/_footer');
    }
}

?>