<?php
// ----------- start config methods ------------------------------------------------------------------------------------
require_once __DIR__ . "/../bootstrap/autoload.php";
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
    public function table_list($table, $desc = true)
    {
        // $id = $this->admin()->id;
        if ($desc)
        $result = $this->connection->query("SELECT * FROM `$table` ORDER BY `id` DESC");
        else
        $result = $this->connection->query("SELECT * FROM `$table` ORDER BY `id`");
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

    public function change_view($table, $id)
    {
        $view = $this->get_data($table, $id)->view;
        $view = !$view;

        $result = $this->connection->query("UPDATE `$table` SET 
        `view`='$view'
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

    public function clean($string, $status = true)
    {
        if ($status) {
            $string = htmlspecialchars($string);
            $string = strip_tags($string);
        }
        $string = stripslashes($string);

        $string = mysqli_real_escape_string($this->connection, $string);
        return $this->convertNumbers($string);
    }

    public function convertNumbers($input)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '٤', '۵', '٥', '٦', '۶', '۷', '۸', '۹'];
        $english = [0, 1, 2, 3, 4, 4, 5, 5, 6, 6, 7, 8, 9];
        foreach ($persian as $p) {
            if (stripos($input, $p) > 0) return str_replace($persian, $english, $input);
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
        return $b[0] . '/' . $b[1] . '/' . $b[2];
    }

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
            $this->admin_log(1);
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


    // ----------- for show all admins
    public function admin_list()
    {
        $id = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `tbl_admin` WHERE NOT `id`='$id' ORDER BY `id` DESC");
//        $result = $this->connection->query("SELECT * FROM `tbl_admin` ORDER BY `id` DESC");
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

        $this->admin_log(3, $username);

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

        $this->admin_log(4, $username);

        return $id;
    }

    // ----------- remove admin
    public function admin_remove($id)
    {
        if ($this->admin_get($id)->access) return false;

        $this->admin_log(5, $this->admin_get($id)->username);

        return $this->remove_data("tbl_admin", $id);
    }

    // ----------- change admin's status
    public function admin_status($id)
    {
        $this->admin_log(15, $this->admin_get($id)->username);
        return $this->change_status('tbl_admin', $id);
    }

    // ----------- get admin's data
    public function admin_get($id)
    {
        return $this->get_data("tbl_admin", $id);
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

    // ----------- start log ----------------------------------------------------------------------------

    public function action_get($id)
    {
        return $this->get_data("tbl_action", $id);
    }

    public function admin_log($action, $variable = null)
    {
        $now = time();
        $admin_id = $this->admin()->id;
        $ip = $_SERVER['REMOTE_ADDER'];
        $result = $this->connection->query("INSERT INTO tbl_admin_log (`admin_id`,`action_id`,`variable`,`ip`,`created_at`)
                                                VALUES('$admin_id','$action','$variable','$ip','$now')");
        if (!$this->result($result)) return false;
        return $this->connection->insert_id;
    }

    // ----------- for show all admins log
    public function admin_log_list()
    {
        $result = $this->connection->query("SELECT * FROM `tbl_admin_log` WHERE `view`=0 ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    public function my_log_list($limit = null)
    {
        if (is_null($limit)) $limit = $this->my_log_counter();
        $admin = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `tbl_admin_log` WHERE `admin_id`='$admin' AND `admin_view`=0 ORDER BY `id` DESC LIMIT 0,$limit");
        if (!$this->result($result)) return false;
        return $result;
    }

    public function my_log_counter()
    {
        $admin = $this->admin()->id;
        $result = $this->connection->query("SELECT * FROM `tbl_admin_log` WHERE `admin_id`='$admin' AND `admin_view`=0 ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result->num_rows;
    }

    public function admin_log_view($id)
    {
        $result = $this->connection->query("UPDATE `tbl_admin_log` SET 
        `view`='1'
        WHERE `id`='$id'");
        if (!$this->result($result)) return false;
        return true;

    }


    public function admin_log_view_all()
    {
        $result = $this->connection->query("UPDATE `tbl_admin_log` SET 
        `view`='1'");
        if (!$this->result($result)) return false;
        return true;

    }

    public function admin_my_log_view_all()
    {
        $admin = $this->admin()->id;
        $result = $this->connection->query("UPDATE `tbl_admin_log` SET 
        `admin_view`='1'
        WHERE `admin_id`= '$admin'");
        if (!$this->result($result)) return false;
        return true;

    }

    // ----------- end log ----------------------------------------------------------------------------


    // ----------- start user ----------------------------------------------------------------------------
    // ----------- for show all admins
    public function user_list()
    {
        return $this->table_list("tbl_user");
    }

    // ----------- add an admin
    public function user_add($first_name, $last_name, $phone, $password, $province, $payment, $warranty, $support, $status)
    {
        $now = time();
        $result = $this->connection->query("INSERT INTO `tbl_user`
        (`first_name`,`last_name`,`phone`,`password`,`province_id`,`payment`,`warranty`,`support`,`status`,`created_at`) 
        VALUES
        ('$first_name','$last_name','$phone','$password','$province','$payment','$warranty','$support','$status','$now')");
        if (!$this->result($result)) return false;

        $this->admin_log(6, $phone);

        return $this->connection->insert_id;
    }

    // ----------- update admin's detail
    public function user_edit($id, $first_name, $last_name, $phone, $password, $province, $payment, $warranty, $support, $status)
    {
        $now = time();
        $result = $this->connection->query("UPDATE `tbl_user` SET 
        `first_name`='$first_name',
        `last_name`='$last_name',
        `phone`='$phone',
        `province_id` = '$province',
        `password`='$password',
        `warranty`='$warranty',
        `payment` = '$payment',
        `support` = '$support',
        `status`='$status',
        `updated_at`='$now'
        WHERE `id` ='$id'");
        if (!$this->result($result)) return false;
        $this->admin_log(7, $phone);
        return $id;
    }

    // ----------- remove admin
    public function user_remove($id)
    {
        $this->admin_log(8, $this->user_get($id)->phone);
        return $this->remove_data("tbl_user", $id);
    }

    // ----------- change admin's status
    public function user_status($id)
    {
        $this->admin_log(16, $this->user_get($id)->phone);
        return $this->change_status('tbl_user', $id);
    }

    // ----------- get admin's data
    public function user_get($id)
    {
        return $this->get_data("tbl_user", $id);
    }

    // ----------- count of admin
    public function user_counter()
    {
        return $this->table_counter("tbl_user");
    }

    public function user_log_list()
    {
        $result = $this->connection->query("SELECT * FROM `tbl_user_log` WHERE `view`=0 ORDER BY `id` DESC");
        if (!$this->result($result)) return false;
        return $result;
    }

    public function user_log_view($id)
    {
        $result = $this->connection->query("UPDATE `tbl_user_log` SET 
        `view`='1'
        WHERE `id`='$id'");
        if (!$this->result($result)) return false;
        return true;

    }

    public function user_log_view_all()
    {
        $result = $this->connection->query("UPDATE `tbl_user_log` SET 
        `view`='1'");
        if (!$this->result($result)) return false;
        return true;

    }

    // ----------- end user ------------------------------------------------------------------------------

    // ----------- start sms ------------------------------------------------------------------------------
    public function sms_list()
    {
        return $this->table_list("tbl_sms", false);
    }

    public function sms_log_list()
    {
        return $this->table_list("tbl_sms_log");
    }

    public function sms_edit($slug, $text)
    {
        $result = $this->connection->query("UPDATE `tbl_sms` SET 
        `text`='$text'
        WHERE `slug` ='$slug'");
        if (!$this->result($result)) return false;
        $this->admin_log(9);
        return true;
    }

    public function sms_get($id)
    {
        return $this->get_data("tbl_sms", $id);
    }

    public function sms_add($user, $sms, $var = "")
    {

        $text = $this->sms_get($sms)->text;
        $text = str_replace("#var", $var, $text);

        $now = time();
        $admin = (int) $this->admin()->id;
        $user = (int) $user;

        $result = $this->connection->query("INSERT INTO `tbl_sms_log`
        (`admin_id`,`user_id`,`text`,`send_at`)
        VALUES
        ('$admin','$user','$text','$now')");

        if (!$this->result($result)) return false;

        $phone = $this->user_get($user)->phone;
        return $this->send_sms($phone, $text);
    }

    public function send_sms($phone, $text)
    {
        return true;
    }

    // ----------- end sms ------------------------------------------------------------------------------

    // ----------- start request ------------------------------------------------------------------------------

    public function request_counter()
    {
        return $this->table_counter("tbl_request");
    }

    public function request_list()
    {
        return $this->table_list("tbl_request");
    }

    public function request_type_get($type) {
        if ($type == 0) echo "تعمیرات";
        elseif ($type == 1) echo "";
        elseif ($type == 2) echo "";
        elseif ($type == 3) echo "";
        elseif ($type == 4) echo "";
        elseif ($type == 5) echo "";
        elseif ($type == 6) echo "";
        else echo "تعریف نشده";
    }

    public function request_remove($id)
    {
        $this->admin_log(17, $this->request_get($id)->code);
        return $this->remove_data("tbl_request", $id);
    }

    public function request_status_counter($status)
    {
        $result = $this->connection->query("SELECT * FROM `tbl_request` WHERE `status_id`='$status' ");
        if (!$this->result($result)) return false;
        return $result->num_rows;
    }

    public function request_get($id)
    {
        return $this->get_data("tbl_request", $id);
    }

    // ----------- end request ------------------------------------------------------------------------------

    // ----------- start status ------------------------------------------------------------------------------
    public function status_list()
    {
        return $this->table_list("tbl_status", false);
    }

    public function status_get($id)
    {
        return $this->get_data("tbl_status", $id);
    }

    // ----------- end status ------------------------------------------------------------------------------

    // ----------- start movie ------------------------------------------------------------------------------
    public function movie_list()
    {
        return $this->table_list("tbl_movie");
    }

    // ----------- add an admin
    public function movie_add($title, $cover, $desc, $movie)
    {
        $now = time();
        $result = $this->connection->query("INSERT INTO `tbl_movie`
        (`title`,`cover`,`description`,`movie`,`created_at`) 
        VALUES
        ('$title','$cover','$desc','$movie','$now')");
        if (!$this->result($result)) return false;
        $this->admin_log(10, $title);
        return $this->connection->insert_id;
    }

    // ----------- update admin's detail
    public function movie_edit($id, $title, $cover, $desc, $movie)
    {
        $result = $this->connection->query("UPDATE `tbl_movie` SET 
        `title`='$title',
        `cover`='$cover',
        `description`='$desc',
        `movie`='$movie' 
        WHERE `id` = '$id' ");

        if (!$this->result($result)) return false;
        $this->admin_log(11, $title);
        return $id;
    }

    // ----------- remove admin
    public function movie_remove($id)
    {
        $this->admin_log(12, $this->movie_get($id)->title);
        return $this->remove_data("tbl_movie", $id);
    }
    // ----------- end movie ------------------------------------------------------------------------------

    public function movie_get($id)
    {
        return $this->get_data("tbl_movie", $id);
    }


}

// ----------- end Action class ----------------------------------------------------------------------------------------



