<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partners_controller extends Home_Core_Controller {


    public function index(){
        $this->load->view('partials/_header');
        $this->load->view('login/partners/index');
        $this->load->view('partials/_footer');
    }
}

?>