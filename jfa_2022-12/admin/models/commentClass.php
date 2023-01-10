<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of commentClass
 *
 * @author Kushan
 */
include_once '../models/credentialCheckClass.php';

class commentClass {

    private $comm_code;
    private $comm_title;
    private $comm_url_slug;
    private $comm_name;
    private $comm_email;
    private $comment;
    private $cat_code;
    private $sub_code;
    private $post_code;
    private $approved;
    private $comm_date;
    private $comm_order;

    function getComm_code() {
        return $this->comm_code;
    }

    function getComm_title() {
        return $this->comm_title;
    }

    function getComm_url_slug() {
        return $this->comm_url_slug;
    }

    function getComm_name() {
        return $this->comm_name;
    }

    function getComm_email() {
        return $this->comm_email;
    }

    function getComment() {
        return $this->comment;
    }

    function getCat_code() {
        return $this->cat_code;
    }

    function getSub_code() {
        return $this->sub_code;
    }

    function getPost_code() {
        return $this->post_code;
    }

    function getApproved() {
        return $this->approved;
    }

    function getComm_date() {
        return $this->comm_date;
    }

    function getComm_order() {
        return $this->comm_order;
    }

    function setComm_code($comm_code) {
        $this->comm_code = $comm_code;
    }

    function setComm_title($comm_title) {
        $this->comm_title = $comm_title;
    }

    function setComm_url_slug($comm_url_slug) {
        $this->comm_url_slug = $comm_url_slug;
    }

    function setComm_name($comm_name) {
        $this->comm_name = $comm_name;
    }

    function setComm_email($comm_email) {
        $this->comm_email = $comm_email;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setCat_code($cat_code) {
        $this->cat_code = $cat_code;
    }

    function setSub_code($sub_code) {
        $this->sub_code = $sub_code;
    }

    function setPost_code($post_code) {
        $this->post_code = $post_code;
    }

    function setApproved($approved) {
        $this->approved = $approved;
    }

    function setComm_date($comm_date) {
        $this->comm_date = $comm_date;
    }

    function setComm_order($comm_order) {
        $this->comm_order = $comm_order;
    }

    public function checkUrlSlug() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT comm_url_slug FROM comments WHERE comm_url_slug = '" . $this->getComm_url_slug() . "'";
        $result = $myCon->query($query);
        if (mysqli_num_rows($result) >= 1) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function getCommentOrder() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "SELECT comm_code FROM comments ORDER BY comm_code DESC LIMIT 1";
        $result = $myCon->query($query);
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            while ($row = mysqli_fetch_assoc($result)) {
                $num = $row['comm_code'] + 1;
            }
        } else {
            /* no recordes */
            $num = 1;
        }
        return $num;
    }

    public function addComment() {
        $myCon = new dbConfig();
        $myCon->connect();

        $a = 1;
        $url_slug_up = $this->getComm_url_slug() . '-0';
        while ($this->checkUrlSlug() == FALSE) {
            $url_slug_up = substr($url_slug_up, 0, -1);
            $url_slug_up = $url_slug_up . $a;
            $this->setComm_url_slug($url_slug_up);
            $a = $a + 1;
        }

        $query = "INSERT INTO comments (comm_title, comm_url_slug, comm_name, comm_email, comment, "
                . "cat_code, sub_code, post_code, approved, comm_date, comm_order) VALUES "
                . "('" . $this->getComm_title() . "', '" . $this->getComm_url_slug() . "', "
                . "'" . $this->getComm_name() . "', '" . $this->getComm_email() . "', '" . $this->getComment() . "', "
                . "'" . $this->getCat_code() . "', '" . $this->getSub_code() . "', '" . $this->getPost_code() . "', "
                . "'" . $this->getApproved() . "', '" . $this->getComm_date() . "', '" . $this->getComm_order() . "')";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function deleteComment() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "DELETE FROM comments WHERE comm_code = '" . $this->getComm_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function approveAndEditComment() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE comments SET comm_name ='" . $this->getComm_name() . ", "
                . "comm_email ='" . $this->getComm_email() . ", comment='" . $this->getComment() . ", "
                . "approved='1' WHERE comm_code = '" . $this->getComm_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

    public function approveComment() {
        $myCon = new dbConfig();
        $myCon->connect();

        $query = "UPDATE comments SET approved='1' "
                . "WHERE comm_code = '" . $this->getComm_code() . "'";
        $result = $myCon->query($query);
        if (!$result) {
            throw new Exception(mysqli_error());
        }
    }

}
