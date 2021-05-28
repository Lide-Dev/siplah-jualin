<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron_controller extends Home_Core_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Update Sitemap
     */
    public function update_sitemap()
    {
        $this->load->model('sitemap_model');
        $this->sitemap_model->update_sitemap();
    }

    public function dapodik_checksums()
    {
        
        $test = file_get_contents(FCPATH . "kemdikbud/checksums.txt");
        $test = preg_split('/\r\n|\r|\n/', $test);
        foreach ($test as $key => &$one) {
            list($file, $checksum) = explode(" ", $one, 2);
        }
        dd($test);
    }
}
