<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Guide_user_controller extends Home_Core_Controller {


    public function index(){
        $data['title'] = trans("guide");
        $data['description'] = trans("guide") . " - " . $this->app_name;
        $data['keywords'] = trans("guide") . "," . $this->app_name;

        $this->load->view('partials/_header',$data);
        $this->load->view('guide_user');
        $this->load->view('partials/_footer');
    }
}

?>