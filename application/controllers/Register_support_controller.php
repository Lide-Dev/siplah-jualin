<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * This controller handle AJAX Function of registering user page.
 *
 * @author Herlandro T. <herlandrotri@gmail.com>
 */
class Register_support_controller extends Home_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    }

    public function get_province_option()
    {
        $result = $this->location_model->get_province();
        $response = "<option value='0'> Pilih salah satu provinsi</option>";
        foreach ($result as $key => $value) {
            $response .= "<option value='{$value->id}'> {$value->province_name} </option>";
        };
        return $response;
    }

    public function get_city_option()
    {
        $province_id = $this->input->get("province_id", true);
        $result = $this->location_model->get_city($province_id);
        // dd($result,$province_id);
        $response = "<option value='0'> Pilih salah satu kota</option>";
        foreach ($result as $key => $value) {
            $response .= "<option value='{$value->id}'> {$value->city_name} </option>";
        };
        echo $response;
    }
}
