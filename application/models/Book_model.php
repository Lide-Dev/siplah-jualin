<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Book_model extends CI_Model
{
    public function get_text_books($id = null)
    {
        $response = $this->db->limit(10)->get("text_books")->custom_result_object("TextBook");
        return json_encode($response);
    }

    public function get_text_books_by_categories($value)
    {
    }

    public function get_text_books_by_name($value)
    {
    }

    public function get_text_books_by_isbn($value)
    {
    }
}

class TextBook
{
    public $id;
    public $isbn;
    public $title;
    public $author;
    public $publication_year;
    public $description;
    public $subject_id;
    public $classification_id;
    public $class_id;
    public $school_level_id;
    public $publisher_id;
    public $physical_description;
    public $physical_description_json;
    // public $physical_description_json;
    public $prices;
    public $price_json;
    public $edition;

    public function __construct()
    {
        $this->physical_description_json = json_decode($this->physical_description);
        $this->price_json = json_decode($this->prices);
    }

    public function __set($name, $value)
    {
        // echo $name;
        // echo $name.":".$value."<br>";
        // dd($name);
        // if ($name == 'physical_description_json') {
        //     dd($name);
        //     $this->physical_description_json = json_decode($this->physical_description);
        // }
    }

    public function __get($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        } else if (method_exists($this, $name)) {
            return $this->$name();
        }
    }
}
