<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Shop
{

    public $id;
    public $supplier_id;
    public $supplier_name;
    public $npwp;
    public $npwp_path;
    public $nib;
    public $nib_path;
    public $phone_number;
    public $is_umkm;
    public $nik;
    public $siup_path;
    public $responsible_person_name;
    public $responsible_person_position;
    public $bank_account;
    public $bank_id;
    public $bank_account_owner_name;
    public $full_address;
    public $district;
    public $village;
    public $city_id;
    public $zip_code;
    public $legal_status_id;
    public $user_id;
    public $email;
    public $username;
    //Custom
    public $legal_status;
    public $bank_name;


    public function __set($name, $value)
    {
        if ($name === 'legal_status') {
            $arr = ["1" => "Individu", "2" => "PKP", "3" => "Non PKP"];
            $this->legal_status = $arr[(string)$this->legal_status_id];
        }
        if ($name === "bank_name") {
            $ci = get_instance();
            $ci->load->model("bank_model");
            $ci->bank_model->get_bank($this->bank_id);
        }
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }
}
