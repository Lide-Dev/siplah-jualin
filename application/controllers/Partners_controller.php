<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Partners_controller extends Home_Core_Controller {


    public function index(){
        $data['title'] = trans("login_as_partners");
        $data['description'] = trans("login_as_partners") . " - " . $this->app_name;
        $data['keywords'] = trans("login_as_partners") . "," . $this->app_name;

        $this->load->view('partials/_header',$data);
        $this->load->view('login/partners/partners');
        $this->load->view('partials/_footer');
    }
}

?>