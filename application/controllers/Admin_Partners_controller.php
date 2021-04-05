<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin_Partners_controller extends Admin_Core_Controller {


    public function index(){
        $this->load->view('admin/includes/_header');
        $this->load->view('partners/index');
        $this->load->view('admin/includes/_footer');
    }
}

?>