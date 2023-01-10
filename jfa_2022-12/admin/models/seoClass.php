<?php

include_once '../models/credentialCheckClass.php';

class seoClass {

    private $seo_id;
    private $seo_title;
    private $seo_dscp;
    private $seo_keywords;
    private $fb_id;
    private $og_type;
    private $og_img;
    private $og_site_name;
    private $og_tw_dscp;
    private $tw_site;
    private $tw_creator;
    private $tw_img;
    private $google_publisher;
    private $google_analytics;

    function getSeo_id() {
        return $this->seo_id;
    }

    function getSeo_title() {
        return $this->seo_title;
    }

    function getSeo_dscp() {
        return $this->seo_dscp;
    }

    function getSeo_keywords() {
        return $this->seo_keywords;
    }

    function getFb_id() {
        return $this->fb_id;
    }

    function getOg_type() {
        return $this->og_type;
    }

    function getOg_img() {
        return $this->og_img;
    }

    function getOg_site_name() {
        return $this->og_site_name;
    }

    function getOg_tw_dscp() {
        return $this->og_tw_dscp;
    }

    function getTw_site() {
        return $this->tw_site;
    }

    function getTw_creator() {
        return $this->tw_creator;
    }

    function getTw_img() {
        return $this->tw_img;
    }

    function getGoogle_publisher() {
        return $this->google_publisher;
    }

    function getGoogle_analytics() {
        return $this->google_analytics;
    }

    function setSeo_id($seo_id) {
        $this->seo_id = $seo_id;
    }

    function setSeo_title($seo_title) {
        $this->seo_title = $seo_title;
    }

    function setSeo_dscp($seo_dscp) {
        $this->seo_dscp = $seo_dscp;
    }

    function setSeo_keywords($seo_keywords) {
        $this->seo_keywords = $seo_keywords;
    }

    function setFb_id($fb_id) {
        $this->fb_id = $fb_id;
    }

    function setOg_type($og_type) {
        $this->og_type = $og_type;
    }

    function setOg_img($og_img) {
        $this->og_img = $og_img;
    }

    function setOg_site_name($og_site_name) {
        $this->og_site_name = $og_site_name;
    }

    function setOg_tw_dscp($og_tw_dscp) {
        $this->og_tw_dscp = $og_tw_dscp;
    }

    function setTw_site($tw_site) {
        $this->tw_site = $tw_site;
    }

    function setTw_creator($tw_creator) {
        $this->tw_creator = $tw_creator;
    }

    function setTw_img($tw_img) {
        $this->tw_img = $tw_img;
    }

    function setGoogle_publisher($google_publisher) {
        $this->google_publisher = $google_publisher;
    }

    function setGoogle_analytics($google_analytics) {
        $this->google_analytics = $google_analytics;
    }

    public function updateSEO() {
        $myCon = new dbConfig();
        $myCon->connect();
        $query = "UPDATE seo SET seo_title='" . $this->getSeo_title() . "', seo_dscp='" . $this->getSeo_dscp() . "', "
                . "seo_keywords='" . $this->getSeo_keywords() . "', fb_id='" . $this->getFb_id() . "', "
                . "og_type='" . $this->getOg_type() . "', og_img='" . $this->getOg_img() . "', "
                . "og_site_name='" . $this->getOg_site_name() . "', og_tw_dscp='" . $this->getOg_tw_dscp() . "', "
                . "tw_site='" . $this->getTw_site() . "', tw_creator='" . $this->getTw_creator() . "', "
                . "tw_img='" . $this->getTw_img() . "', google_publisher='" . $this->getGoogle_publisher() . "', "
                . "google_analytics='" . $this->getGoogle_analytics() . "' WHERE seo_id = '" . $this->getSeo_id() . "'";
        $result = $myCon->query($query);
        if ($result) {
            return TRUE;
        } else {
            throw new Exception(mysqli_error());
        }
    }

}
