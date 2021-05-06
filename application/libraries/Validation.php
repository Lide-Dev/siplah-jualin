<?php

/**
 * Validation class that handle validation with language that was being setting on web.
 * It run with form validation on CI3. Prefer to see documentation on <a href="https://codeigniter.com/userguide3/libraries/form_validation.html">CI3 Form Validation</a>.
 *
 * @property array $arr_validate
 * @author Herlandro T <herlandrotri@gmail.com>
 * @link https://codeigniter.com/userguide3/libraries/form_validation.html
 */
class Validation
{
    // private array $arr_validate;

    private CI_Controller $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->library("form_validation");
    }

    /**
     * Setting validation that will be running.
     *
     * @param mixed $value
     * @return void
     */
    public function set_rules($key, $label = null, $rules = null)
    {
        $lang = [];
        if (is_string($rules)) {
            $rules_modified = explode("|", $rules);
        } else {
            $rules_modified = $rules;
        }
        foreach ($rules_modified as $key => $value) {
            $lang += [$value => trans_valid($value, "Terjadi kesalahan pada form yang di input!")];
        }
        if (is_array($rules)) {
            $rules = implode("|", $rules);
        }
        try {
            $this->ci->form_validation->set_rules($key, $label, $rules, $lang);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
