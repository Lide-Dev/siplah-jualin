<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model
{
    //input values
    public function input_values()
    {
        $data = array(
            'lang_id' => $this->input->post('lang_id', true),
            'title' => $this->input->post('title', true),
            'slug' => $this->input->post('slug', true),
            'summary' => $this->input->post('summary', true),
            'keywords' => $this->input->post('keywords', true),
            'category_id' => $this->input->post('category_id', true),
            'content' => $this->input->post('content', false),
            'user_id' => $this->auth_user->id
        );
        return $data;
    }

    //add post
    public function add_post()
    {
        $data = $this->input_values();

        if (empty($data["slug"])) {
            //slug for title
            $data["slug"] = str_slug($data["title"]);
        }
        $data["created_at"] = date('Y-m-d H:i:s');
        $data["storage"] = "local";

        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $data["image_default"] = $this->upload_model->blog_image_default_upload($temp_path);
            $data["image_small"] = $this->upload_model->blog_image_small_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);
        } else {
            $data["image_default"] = "";
            $data["image_small"] = "";
        }

        //move to s3
        if ($this->storage_settings->storage == "aws_s3") {
            $this->load->model("aws_model");
            $data["storage"] = "aws_s3";
            //move default image
            if ($data["image_default"] != "") {
                $this->aws_model->put_blog_object($data["image_default"], FCPATH . $data["image_default"]);
                delete_file_from_server($data["image_default"]);
            }
            //move small image
            if ($data["image_small"] != "") {
                $this->aws_model->put_blog_object($data["image_small"], FCPATH . $data["image_small"]);
                delete_file_from_server($data["image_small"]);
            }
        }
        return $this->db->insert('blog_posts', $data);
    }

    //update post
    public function update_post($id)
    {
        $data = $this->input_values();
        $post = $this->get_post($id);
        //slug for title
        if (empty($data["slug"])) {
            $data["slug"] = str_slug($data["title"]);
        }
        $this->load->model('upload_model');
        $temp_path = $this->upload_model->upload_temp_image('file');
        if (!empty($temp_path)) {
            $data["image_default"] = $this->upload_model->blog_image_default_upload($temp_path);
            $data["image_small"] = $this->upload_model->blog_image_small_upload($temp_path);
            $this->upload_model->delete_temp_image($temp_path);
            $data["storage"] = "local";
            //move to s3
            if ($this->storage_settings->storage == "aws_s3") {
                $this->load->model("aws_model");
                $data["storage"] = "aws_s3";
                //move default image
                $this->aws_model->put_blog_object($data["image_default"], FCPATH . $data["image_default"]);
                delete_file_from_server($data["image_default"]);
                //move small image
                $this->aws_model->put_blog_object($data["image_small"], FCPATH . $data["image_small"]);
                delete_file_from_server($data["image_small"]);
            }
            //delete old image
            if ($post->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_blog_object($post->image_default);
                $this->aws_model->delete_blog_object($post->image_small);
            } else {
                delete_file_from_server($post->image_default);
                delete_file_from_server($post->image_small);
            }
        }

        $this->db->where('id', clean_number($id));
        return $this->db->update('blog_posts', $data);
    }

    //update slug
    public function update_slug($id)
    {
        $post = $this->get_post($id);
        if (!empty($post)) {
            if (empty($post->slug) || $post->slug == "-") {
                $data = array(
                    'slug' => $post->id
                );
                $this->db->where('id', $post->id);
                $this->db->update('blog_posts', $data);
            } else {
                if ($this->check_is_slug_unique($post->id, $id) == true) {
                    $data = array(
                        'slug' => $post->slug . "-" . $post->id
                    );

                    $this->db->where('id', $post->id);
                    $this->db->update('blog_posts', $data);
                }
            }
        }
    }

    //check slug
    public function check_is_slug_unique($slug, $id)
    {
        $sql = "SELECT COUNT(blog_posts.id) AS count FROM blog_posts WHERE blog_posts.slug = ? AND blog_posts.id != ?";
        $query = $this->db->query($sql, array(clean_str($slug), clean_number($id)));
        if ($query->row()->count) {
            return true;
        } else {
            return false;
        }
    }

    //query string
    public function query_string()
    {
        return "SELECT blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug
                FROM blog_posts
                INNER JOIN blog_categories ON blog_posts.category_id = blog_categories.id" . " ";
    }

    //get post
    public function get_post($id)
    {
        $sql = "SELECT * FROM blog_posts WHERE id =  ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get post joined
    public function get_post_joined($id)
    {
        $sql = $this->query_string() . "WHERE blog_posts.id = ?";
        $query = $this->db->query($sql, array(clean_number($id)));
        return $query->row();
    }

    //get post by slug
    public function get_post_by_slug($slug)
    {
        $sql = $this->query_string() . "WHERE blog_posts.slug = ?";
        $query = $this->db->query($sql, array(clean_str($slug)));
        return $query->row();
    }

    //get posts
    public function get_posts()
    {
        $sql = "SELECT * FROM blog_posts WHERE blog_posts.lang_id = ? ORDER BY blog_posts.created_at DESC";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->result();
    }

    //get posts count
    public function get_posts_count()
    {
        $sql = "SELECT COUNT(blog_posts.id) AS count FROM blog_posts WHERE blog_posts.lang_id = ?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }

    //get all posts
    public function get_posts_all()
    {
        $sql = "SELECT * FROM blog_posts ORDER BY blog_posts.created_at DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all posts joined
    public function get_posts_all_joined()
    {
        $sql = $this->query_string() . "ORDER BY blog_posts.created_at DESC";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //get all posts count
    public function get_all_posts_count()
    {
        $sql = "SELECT COUNT(blog_posts.id) AS count FROM blog_posts";
        $query = $this->db->query($sql);
        return $query->row()->count;
    }

    //get latest posts
    public function get_latest_posts($limit)
    {
        $sql = $this->query_string() . "WHERE blog_posts.lang_id = ? ORDER BY blog_posts.created_at DESC LIMIT ?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($limit)));
        return $query->result();
    }

    //get posts by category
    public function get_posts_by_category($category_id)
    {
        $sql = "SELECT * FROM blog_posts WHERE blog_posts.category_id = ? ORDER BY blog_posts.created_at DESC";
        $query = $this->db->query($sql, array(clean_number($category_id)));
        return $query->result();
    }

    //get post count by category
    public function get_post_count_by_category($category_id)
    {
        $sql = "SELECT COUNT(blog_posts.id) AS count FROM blog_posts WHERE blog_posts.category_id = ? ORDER BY blog_posts.created_at DESC";
        $query = $this->db->query($sql, array(clean_number($category_id)));
        return $query->row()->count;
    }

    //get paginated posts
    public function get_paginated_posts($offset, $per_page)
    {
        $sql = $this->query_string() . "WHERE blog_posts.lang_id = ? ORDER BY blog_posts.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get paginated category posts
    public function get_paginated_category_posts($offset, $per_page, $category_id)
    {
        $sql = $this->query_string() . "WHERE blog_posts.category_id = ? ORDER BY blog_posts.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_number($category_id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get paginated tag posts
    public function get_paginated_tag_posts($offset, $per_page, $tag_slug)
    {
        $sql = "SELECT blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug
                FROM blog_posts
                INNER JOIN blog_categories ON blog_posts.category_id = blog_categories.id
                INNER JOIN blog_tags ON blog_posts.id = blog_tags.post_id 
                WHERE blog_tags.tag_slug = ? AND blog_posts.lang_id = ? ORDER BY blog_posts.created_at DESC LIMIT ?,?";
        $query = $this->db->query($sql, array(clean_str($tag_slug), clean_number($this->selected_lang->id), clean_number($offset), clean_number($per_page)));
        return $query->result();
    }

    //get paginated tag posts count
    public function get_paginated_tag_posts_count($tag_slug)
    {
        $sql = "SELECT COUNT(blog_posts.id) AS count FROM blog_posts
                INNER JOIN blog_categories ON blog_posts.category_id = blog_categories.id
                INNER JOIN blog_tags ON blog_posts.id = blog_tags.post_id 
                WHERE blog_tags.tag_slug = ? AND blog_posts.lang_id = ?";
        $query = $this->db->query($sql, array(clean_str($tag_slug), clean_number($this->selected_lang->id)));
        return $query->row()->count;
    }

    //get related posts
    public function get_related_posts($category_id, $post_id)
    {
        $sql = $this->query_string() . "WHERE blog_posts.category_id = ? AND blog_posts.id != ? ORDER BY RAND() LIMIT 3";
        $query = $this->db->query($sql, array(clean_number($category_id), clean_number($post_id)));
        return $query->result();
    }

    //delete post
    public function delete_post($id)
    {
        $post = $this->get_post($id);
        if (!empty($post)) {
            //delete from s3
            if ($post->storage == "aws_s3") {
                $this->load->model("aws_model");
                $this->aws_model->delete_blog_object($post->image_default);
                $this->aws_model->delete_blog_object($post->image_small);
            } else {
                delete_file_from_server($post->image_default);
                delete_file_from_server($post->image_small);
            }
            //delete post tags
            $this->tag_model->delete_post_tags($post->id);
            $this->db->where('id', $post->id);
            return $this->db->delete('blog_posts');
        } else {
            return false;
        }
    }

}
