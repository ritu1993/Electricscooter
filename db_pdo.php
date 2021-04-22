<?php
/**
 * class for database using PDO to connect to any
 *  type of SQL servers including MYSQL.
 */
class db_pdo
{
    // public $db_host = '127.0.0.1';
    // public $db_user_name = 'root';
    // public $db_user_pw = '';
    // public $db_name = 'electric_scooters'; //name of database
    // public $connection;

/** Infinity connection to database */

     public $db_host = 'sql103.epizy.com';
     public $db_user_name = 'epiz_26726978';
     public $db_user_pw = 'qPAg7jfjfgD7';
     public $db_name = 'epiz_26726978_electric_scooters'; //name of database
     public $connection;


    public function __construct()
    {
        // connection options
        //https://www.php.net/manual/en/pdo.setattribute.php
       // $options = [
            // throw exception on SQL errors
         //   PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // return records with associative keys only, no numeric index
           // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            //Enables or disables emulation of prepared statements. Some drivers do not support native prepared statements or have .
            //(if FALSE) to try to use native prepared statements
            //PDO::ATTR_EMULATE_PREPARES => false,
        //];

        try {
            $this->connection = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name, $this->db_user_name, $this->db_user_pw);
        } catch (PDOException $e) {
            http_response_code(500);
            //throw new \PDOException($e->getMessage(), (int) $e->getcode());
            echo 'Error!: '.$e->getMessage().'<br/>';
            die();
        }
        echo 'Connected to DB!';
    }

    /**
     * query() for INSERT,UPDATE,DELETE that return no records.
     */
    public function query($sql_str)
    {
        try {
            $result = $this->connection->exec($sql_str);
            if (!$result) {
                $validPage = new web_page();
                $validPage->content = '<h2>'.$sql_str.'  <br/>Error: </h2>';
                $validPage->render();
                die('SQL Query Error');
            }
        } catch (\PDOException $e) {
            throw new \PDOException($e->getmessage(), (int) $e->getcode());
            die();
        }

        return $result;
    }

    /**
     * quereySelect() for SELECT queries returning records converted in PHP array.
     */
    public function querySelect($sql_str)
    {
        $stmt = $this->connection->prepare($sql_str);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    /**
     * query() for INSERT, UPDATE, DELETE that return no records.
     */
    public function queryParam($sql_str, $params)
    {
        $stmt = $this->connection->prepare($sql_str);
        $stmt->execute($params);
 
        return true;
    }

    public function querySelectParam($sql_str, $params)
    {
        $stmt = $this->connection->prepare($sql_str);
        $stmt->execute($params);
 
        return $stmt->fetchAll();
    }

    public function disconnect()
    {
        $this->connection = null;
    }
}
