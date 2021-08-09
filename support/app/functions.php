<?php
// ----------- start config methods ------------------------------------------------------------------------------------
require_once __DIR__."/../bootstrap/autoload.php";
include('jdf.php');
// ----------- end config methods --------------------------------------------------------------------------------------

// ----------- start DB class ------------------------------------------------------------------------------------------
class DB
{
    // ----------- properties
    protected $_DB_HOST = 'localhost';
    protected $_DB_NAME = 'fanyab_SS';
    protected $_DB_USER = 'root';
    protected $_DB_PASS = '';
    protected $connection;

    // ----------- constructor
    public function __construct()
    {
        $this->connection = mysqli_connect($this->_DB_HOST, $this->_DB_USER, $this->_DB_PASS, $this->_DB_NAME);
        if ($this->connection) {
            $this->connection->query("SET NAMES 'utf8'");
            $this->connection->query("SET CHARACTER SET 'utf8'");
            $this->connection->query("SET character_setconnectionection = 'utf8'");
        }
    }

    // ----------- for return connection
    public function connect()
    {
        return $this->connection;
    }

}


// ----------- end DB class --------------------------------------------------------------------------------------------

// ----------- start Action class --------------------------------------------------------------------------------------
class Action
{

    // ----------- properties
    public $connection;

    // ----------- constructor
    public function __construct()
    {
        $db = new DB();
        $this->connection = $db->connect();
    }

    // ----------- start main methods ----------------------------------------------------------------------------------

    // ----------- get current page url
    public function url()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }

    // ----------- for check result of query
    public function result($result)
    {
        if (!$result) {
            $errorno = mysqli_errno($this->connection);
            $error = mysqli_error($this->connection);
            echo "Error NO : $errorno";
            echo "<br>";
            echo "Error Message : $error";
            echo "<hr>";
            return false;
        }
        return true;
    }

    // ----------- count of table's field
    public function table_counter($table)
    {
        $result = $this->connection->query("SELECT * FROM `$table` ");
        if (!$this->result($result)) return false;
        return $result->num_rows;
    }

    // ----------- get all fields in table
    public function table_list($table)
    {
        // $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `$table` ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    // ----------- get all fields in table other than one :)
    public function table_option($table, $id)
    {
        $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `$table` WHERE NOT `id`='$id' ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    // ----------- change status of field
    public function change_status($table, $id)
    {
        $status = $this->get_data($table, $id)->status;
        $status = !$status;

        $now = time();
        $result = $this->connection->query("UPDATE `$table` SET 
        `status`='$status',
        `updated_at`='$now'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        return $id;
    }

    // ----------- get data from table
    public function get_data($table, $id)
    {
        $result = $this->connection->query("SELECT * FROM `$table` WHERE id='$id'");
        if (!$this->result($result)) return false;
        $row = $result->fetch_object();
        return $row;
    }

    // ----------- remove data from table
    public function remove_data($table, $id)
    {
        $result = $this->connection->query("DELETE FROM `$table` WHERE id='$id'");
        if (!$this->result($result)) return false;
        return true;
    }

    // ----------- clean strings (to prevent sql injection attacks)

    function get_tag( $attr, $value, $xml ) {

        $attr = preg_quote($attr);
        $value = preg_quote($value);

        $tag_regex = '/<p[^>]*'.$attr.'="'.$value.'">(.*?)<\\/p>/si';

        preg_match($tag_regex,
            $xml,
            $matches);
        return $matches[1];
    }

    public function clean($string, $status = true)
    {
        if ($status) {
            $string = htmlspecialchars($string);
            $string = strip_tags($string);
        }
        $string = stripslashes($string);

        $string = mysqli_real_escape_string($this->connection, $string);
        $string = $this->convertPersianNumbersToEnglish($string);
        return $string;
    }

    public function convertPersianNumbersToEnglish($input)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '٤', '۵', '٥', '٦', '۶', '۷', '۸', '۹'];
        $english = [ 0 ,  1 ,  2 ,  3 ,  4 ,  4 ,  5 ,  5 ,  6 ,  6 ,  7 ,  8 ,  9 ];
        foreach($persian as $p) {
            if (stripos($input,$p) > 0) return str_replace($persian, $english, $input);
        }
        return $input;
    }

    // ----------- for clean and get requests
    public function request($name, $status = true)
    {
        return $this->clean($_REQUEST[$name], $status);
    }

    // ----------- for get and convert date
    public function request_date($name)
    {
        $name = $this->request($name, false);
        if (!$name) return 0;
        $name = $this->shamsi_to_miladi($name);
        return strtotime($name);
    }

    // ----------- convert timestamp to shamsi date
    public function time_to_shamsi($timestamp)
    {
        return $this->miladi_to_shamsi(date('Y-m-d', $timestamp));
    }

    // ----------- convert shamsi date to miladi date
    public function shamsi_to_miladi($date)
    {
        $pieces = explode("/", $date);
        $day = $pieces[2];
        $month = $pieces[1];
        $year = $pieces[0];
        $b = jalali_to_gregorian($year, $month, $day, $mod = '-');
        $f = $b[0] . '-' . $b[1] . '-' . $b[2];
        return $f;
    }

    // ----------- convert miladi date to shamsi date
    public function miladi_to_shamsi($date)
    {
        $pieces = explode("-", $date);
        $year = $pieces[0];
        $month = $pieces[1];
        $day = $pieces[2];
        $b = gregorian_to_jalali($year, $month, $day, $mod = '-');
        $f = $b[0] . '/' . $b[1] . '/' . $b[2];
        return $f;
    }

    // ----------- for send sms to mobile number
    public function send_sms($mobile,$textMessage){

        $webServiceURL  = "http://login.parsgreen.com/Api/SendSMS.asmx?WSDL";
        $webServiceSignature = "86D08235-C008-4C53-8EEA-CE2284FD66F4";

        $textMessage= mb_convert_encoding($textMessage,"UTF-8"); // encoding to utf-8


        $parameters['signature'] = $webServiceSignature;
        $parameters['toMobile' ]  = $mobile;
        $parameters['smsBody' ]=$textMessage;
        $parameters[ 'retStr'] = ""; // return refrence send status and mobile and report code for delivery


        try
        {
            $con = new SoapClient($webServiceURL);

            $responseSTD = (array) $con ->Send($parameters);

            $responseSTD['retStr'] = (array) $responseSTD['retStr'];


        }
        catch (SoapFault $ex)
        {
            echo $ex->faultstring;
        }

    }


// public function broadcast($id){
//     $row = $this->notif_get($id);
//     $date = $row->created_at + 60;
//     $date = date('Y-m-d',$date)."T".date('H:i:s',$date);
//     $notification = new Notification(true);

//     $notification->title = "'.$row->title.'";
//     $notification->body = "'.$row->description.'";
//     $notification->onClickAction = "open-app";
//     $notification->url = "https://abarpayo.com";
//     $notification->content = "nothing special";
//     $notification->json = array();
//     $notification->icon = "https://www.pixsy.com/wp-content/uploads/2021/04/ben-sweet-2LowviVHZ-E-unsplash-1.jpeg";
//     $notification->image = "https://www.pixsy.com/wp-content/uploads/2021/04/ben-sweet-2LowviVHZ-E-unsplash-1.jpeg";
//     $notification->sentTime = "$date";

//     $api_key = "7afd5b75-d0f1-4abd-bd95-1679f7584224";
//     $token = "265fc5c5cc483c01babda87210a0802454423004";

//     $najva = new Najva($api_key,$token);

//     $result = $najva->sendNotification($notification);
// }

    // ----------- create random token
    public function get_token($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet);
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[rand(0, $max - 1)];
        }
        return $token;
    }

    // ----------- end main methods ------------------------------------------------------------------------------------

    // ----------- start ADMINS ----------------------------------------------------------------------------------------
    // ----------- for login admin
    public function admin_login($user, $pass)
    {
        $result = $this->connection->query("SELECT * FROM `tbl_admin` WHERE `username`='$user' AND `password`='$pass' AND status=1");
        if (!$this->result($result)) return false;
        $rowcount = mysqli_num_rows($result);
        $row = $result->fetch_object();
        if ($rowcount) {
            $_SESSION['admin_id'] = $row->id;
            $_SESSION['admin_access'] = $row->access;
            $this->admin_update_last_login();
            $this->log_action(1);
            return true;
        }
        return false;
    }

    // ----------- for check access (admin access)
    public function auth()
    {
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_access']))
            return true;
        return false;
    }

    // ----------- for check access (guest access)
    public function guest()
    {
        if (isset($_SESSION['admin_id']) && isset($_SESSION['admin_access']))
            return false;
        return true;
    }

    // ----------- update last login of admin (logged)
    public function admin_update_last_login()
    {
        $id = $this->admin()->id;
        $now = strtotime(date('Y-m-d H:i:s'));
        $result = $this->connection->query("UPDATE `tbl_admin` SET `last_login`='$now' WHERE `id`='$id'");
        if (!$this->result($result)) return false;
        return true;
    }

    public function admin_check_per($admin,$per)
    {
        $result = $this->connection->query("SELECT * FROM `tbl_admin_permission` WHERE `admin_id`='$admin' AND `permission_id`='$per'");
        $rowcount = $result->num_rows;
        if ($rowcount < 1) return false;
        return true;
    }



    public function admin_per_add($admin, $per)
    {
        $now = time();
        $result = $this->connection->query("INSERT INTO `tbl_admin_permission`
        (`admin_id`,`permission_id`,`created_at`) 
        VALUES
        ('$admin','$per','$now')");
        if (!$this->result($result)) return false;
        return $this->connection->insert_id;

    }

    public function admin_per_list($id)
    {
        return $result = $this->connection->query("SELECT * FROM `tbl_admin_permission` WHERE `admin_id`='$id'");
    }

    public function admin_per_remove($admin, $per)
    {
        $result = $this->connection->query("DELETE FROM `tbl_admin_permission` WHERE `admin_id`='$admin' AND `permission_id`='$per'");
        if (!$this->result($result)) return false;
        return true;
    }

    // ----------- update profile (logged admin)
    public function profile_edit($first_name, $last_name, $phone, $password)
    {
        $id = $this->admin()->id;
        $now = time();
        $result = $this->connection->query("UPDATE `tbl_admin` SET 
        `first_name`='$first_name',
        `last_name`='$last_name',
        `phone`='$phone',
        `password`='$password',
        `updated_at`='$now'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        $this->log_action(2);
        return $id;
    }

    // ----------- for show all admins
    public function admin_list()
    {
        $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `tbl_admin` WHERE NOT `id`='$id' ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    // ----------- add an admin
    public function admin_add($first_name, $last_name, $phone, $username, $password, $status, $access)
    {
        $now = time();
        $result = $this->connection->query("INSERT INTO `tbl_admin`
        (`first_name`,`last_name`,`phone`,`username`,`password`,`access`,`status`,`created_at`) 
        VALUES
        ('$first_name','$last_name','$phone','$username','$password','$access','$status','$now')");
        if (!$this->result($result)) return false;
        return $this->connection->insert_id;
    }

    // ----------- update admin's detail
    public function admin_edit($id, $first_name, $last_name, $phone, $username, $password, $status, $access)
    {
        $now = time();
        $result = $this->connection->query("UPDATE `tbl_admin` SET 
        `first_name`='$first_name',
        `last_name`='$last_name',
        `phone`='$phone',
        `username` = '$username',
        `password`='$password',
        `access` = '$access',
        `status`='$status',
        `updated_at`='$now'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        return $id;
    }

    // ----------- remove admin
    public function admin_remove($id)
    {
        if ($this->admin_get($id)->access) return false;
        return $this->remove_data("tbl_admin", $id);
    }

    // ----------- change admin's status
    public function admin_status($id)
    {
        return $this->change_status('tbl_admin', $id);
    }

    // ----------- get admin's data
    public function admin_get($id)
    {
        return $this->get_data("tbl_admin", $id);
    }

    public function notif_get($id)
    {
        return $this->get_data("tbl_notif", $id);
    }


    // ----------- get admin's data (logged)
    public function admin()
    {
        $id = $_SESSION['admin_id'];
        return $this->admin_get($id);
    }

    // ----------- count of admin
    public function admin_counter()
    {
        return $this->table_counter("tbl_admin");
    }

    // ----------- end ADMINS ------------------------------------------------------------------------------------------

}

// ----------- end Action class ----------------------------------------------------------------------------------------



