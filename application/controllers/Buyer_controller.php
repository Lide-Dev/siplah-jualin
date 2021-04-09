<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Buyer_controller extends Home_Core_Controller {


    public function index(){
        $data['title'] = trans("login_as_buyer");
        $data['description'] = trans("login_as_buyer") . " - " . $this->app_name;
        $data['keywords'] = trans("login_as_buyer") . "," . $this->app_name;

        $this->load->view('partials/_header',$data);
        $this->load->view('login/buyer/index');
        $this->load->view('partials/_footer');
    }
}

?>