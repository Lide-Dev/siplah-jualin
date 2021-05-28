<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Book_model extends CI_Model
{
    protected function default_query($with_parameter = false, $query_parameter = [])
    {
        $this->db->select("text_books.* , classification_books.name as classification_name, school_levels.name as school_level_name, school_classes.name as school_class_name, publishers.name as publisher_name");
        $this->db->join("classification_books", "classification_books.id = text_books.classification_id");
        $this->db->join("school_levels", "school_levels.id = text_books.school_level_id");
        $this->db->join("school_classes", "school_classes.id = text_books.class_id");
        $this->db->join("publishers", "publishers.id = text_books.publisher_id");
        $this->db->order_by("text_books.title", "asc");
        if ($with_parameter) {
            if (!empty($query_parameter["filter"]))
                foreach ($query_parameter["filter"] as $key => $value) {
                    $this->db->where($key, $value);
                }
            if (!empty($query_parameter["search"])) {
                $this->db->like("title", $query_parameter["search"]);
                $this->db->or_like("description", $query_parameter["search"]);
                $this->db->or_like("isbn", $query_parameter["search"]);
                $this->db->or_like("author", $query_parameter["search"]);
            }
        }
    }
    public function get_text_books($id = null)
    {
        $this->default_query();
        if (!empty($id)) {
            $this->db->where("text_books.id", $id);
            $response = $this->db->get("text_books")->first_row("TextBook");
        } else {
            $response = $this->db->get("text_books")->custom_result_object("TextBook");
        }
        return $response;
    }

    public function get_paginated_text_books($query_parameter)
    {
        $per_column = 6;
        if ($query_parameter['page'] == LIMIT_PAGINATION) {
            $query_parameter["page"] = LIMIT_PAGINATION;
        }
        if ($query_parameter['page'] < 0) {
            $query_parameter["page"] = 1;
        }

        $this->default_query(true, $query_parameter);
        $sql = $this->db->limit($per_column, $per_column * ($query_parameter["page"] - 1))->get_compiled_select("text_books");
        // dd($sql);
        $response["result"] = $this->db->query($sql)->custom_result_object("TextBook");
        $this->default_query(true, $query_parameter);
        $response["total_data"] = $this->db->count_all_results("text_books");
        $response["current_page"] = $query_parameter["page"];
        $response["total_page"] = $response["total_data"] % $per_column ? $response["total_data"] / $per_column + 1 : $response["total_data"] / $per_column;
        return $response;
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

    public function get_classification($id = null)
    {
        if (!empty($id)) {
            $this->db->where("id", $id);
            $response = $this->db->get("classification_books")->first_row();
        } else {
            $response = $this->db->get("classification_books")->result();
        }
        return $response;
    }

    public function get_school_level($id = null)
    {
        if (!empty($id)) {
            $this->db->where("id", $id);
            $response = $this->db->get("school_levels")->first_row();
        } else {
            $response = $this->db->get("school_levels")->result();
        }
        return $response;
    }

    public function get_school_class($id = null)
    {
        if (!empty($id)) {
            $this->db->where("id", $id);
            $response = $this->db->get("school_classes")->first_row();
        } else {
            $response = $this->db->get("school_classes")->result();
        }
        return $response;
    }

    public function get_publisher($id = null)
    {
        if (!empty($id)) {
            $this->db->where("id", $id);
            $response = $this->db->get("publishers")->first_row();
        } else {
            $response = $this->db->get("publishers")->result();
        }
        return $response;
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
    public $classification_name;
    public $class_id;
    public $class_name;
    public $school_level_id;
    public $school_level_name;
    public $publisher_id;
    public $publisher_name;
    public $physical_description;
    public $physical_description_json;
    // public $physical_description_json;
    public $prices;
    public $prices_json;
    public $edition;

    public function __construct()
    {

        $ci = get_instance();
        $this->physical_description_json = json_decode($this->physical_description);
        $this->prices_json = json_decode($this->prices);
        // $this->classification_name = $ci->book_model->get_classification($this->classification_id)->name;
        // $this->class_name = $ci->book_model->get_school_class($this->class_id)->name;
        // $this->school_level_name = $ci->book_model->get_school_level($this->school_level_id)->name;
        // $this->publisher_name = $ci->book_model->get_publisher($this->publisher_id)->name;
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

    public function price_collection()
    {
        // dd($this->prices_json);
        return price_formatted($this->prices_json[0]->price, "IDR") . " - " . price_formatted($this->prices_json[4]->price, "IDR");
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
