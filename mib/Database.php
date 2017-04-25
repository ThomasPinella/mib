<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author thomaspinella
 */
class Database 
{
    var $host = null;
    var $user = 'root';
    var $password = 'root';
    var $database = 'mib';
    var $port = 3306;
    var $socket = '/Applications/MAMP/tmp/mysql/mysql.sock';
    var $link;
    
    function db_connect()
    {
        $a_link = mysqli_connect($this->host, $this->user, $this->password, $this->database, $this->port, $this->socket);
        if (!$a_link)
        {
            die("Cannot connect to database.");
        }
        $this->link = $a_link;
        return $this->link;
        
    }
    
    function db_close()
    {
        mysqli_close($this->link);
    }
    
    function do_query($query)
    {
        $result = mysqli_query($this->link, $query);
        return $result;

    }
}
