<?php
    class PostEntity {
        private $id;
        private $title;
        private $post_date;
        private $post;
        private $cover;
        private $category_id;
        private $category_name;
        private $username;

//        // Constructor
//        public function __construct($id, $title, $post_date, $post, $cover, $category_id, $category_name, $username)
//        {
//            $this->id = $id;
//            $this->title = $title;
//            $this->post_date = $post_date;
//            $this->post = $post;
//            $this->cover = $cover;
//            $this->category_id = $category_id;
//            $this->category_name = $category_name;
//            $this->username = $username;
//        }

        // Getters
        public function getId()
        {
            return $this->id;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getPostDate() {
            return $this->post_date;
        }

        public function getPost() {
            return $this->post;
        }

        public function getCover() {
            return $this->cover;
        }

        public function getCategoryId()
        {
            return $this->category_id;
        }

        public function getCategoryName()
        {
            return $this->category_name;
        }

        public function getUsername()
        {
            return $this->username;
        }


        // Setters
        public function setId($id): void
        {
            $this->id = $id;
        }

        public function setTitle($title): void {
            $this->title = $title;
        }

        public function setPostDate($post_date): void {
            $this->post_date = $post_date;
        }

        public function setPost($post): void {
            $this->post = $post;
        }

        public function setCover($cover): void {
            $this->cover = $cover;
        }

        public function setCategoryId($category_id): void
        {
            $this->category_id = $category_id;
        }

        public function setCategoryName($category_name): void
        {
            $this->category_name = $category_name;
        }

        public function setUsername($username): void
        {
            $this->username = $username;
        }

    }



?>