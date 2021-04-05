<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Seller_controller extends Home_Core_Controller {


    public function index(){
        $this->load->view('partials/_header');
        $this->load->view('login/seller/index');
        $this->load->view('partials/_footer');
    }
}

?>