<?php
include_once '../' . $website['decryptor_php'];

class dbConfig1
{
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;

    /* connection variable */
    private $dbq;

    public function __construct()
    {
        try {
            if (
                !defined("DB_HOST") ||
                !defined("DB_NAME") ||
                !defined("DB_USER") ||
                !defined("DB_PASS")
            ) throw new Exception("Error: Database not defined.", 1);
        } catch (Exception $e) {;
            cLog($e);
            echo $e->getMessage();
            exit();
        };
        $this->db_host = DB_HOST;
        $this->db_name = DB_NAME;
        $this->db_user = decryptor(DB_USER);
        $this->db_pass = decryptor(DB_PASS);
    }

    public function getDbq()
    {
        return $this->dbq;
    }

    public function connect()
    {
        $this->dbq = mysqli_connect($this->db_host, $this->db_user, $this->db_pass); //Store data connection specifier in object
        /* Check connection */
        // if (mysqli_connect_errno($this->dbq)) {
        if (mysqli_connect_errno()) {
            die("Failed to connect to MySQL: " . mysqli_connect_error());
        }
        mysqli_select_db($this->dbq, $this->db_name) or die("cannot select DB");
    }

    public function query($sql)
    {
        if ($this->dbq) {
            return mysqli_query($this->getDbq(), $sql);  //Specify connection handler when doing query
        } else {
            die('database closed');
        }
    }

    public function fetch($sql)
    {
        return mysqli_fetch_array($this->query($sql));
    }

    public function escapeString($value)
    {
        return mysqli_real_escape_string($this->getDbq(), $value);
    }

    public function mysqliInsertId()
    {
        return mysqli_insert_id($this->getDbq());
    }

    public function closeCon()
    {
        // if (mysqli_connect_errno($this->dbq)) {
        if (mysqli_connect_errno()) {
            echo "Connection Error: " . mysqli_connect_error();
        }
        mysqli_close($this->dbq);
    }
}

/* set default timezone */
date_default_timezone_set('Asia/Colombo');

cLog(pathinfo(__FILE__, PATHINFO_BASENAME) . " loaded.");