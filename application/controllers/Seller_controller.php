<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Seller_controller extends Home_Core_Controller {


    public function index(){
        $data['title'] = trans("login_as_seller");
        $data['description'] = trans("login_as_seller") . " - " . $this->app_name;
        $data['keywords'] = trans("login_as_seller") . "," . $this->app_name;

        $this->load->view('partials/_header',$data);
        $this->load->view('login/seller/index');
        $this->load->view('partials/_footer');
    }
}

?>