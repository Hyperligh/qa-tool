<?php
session_start();
class request{

    function allgroups(){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "SELECT Group_Name, Code FROM groups order by Time desc";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
    }
    
    function ClearGroup($group_code){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "DELETE FROM  groups WHERE Code = '$group_code';";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           
        $sqllink = "DELETE FROM urls WHERE Code = '$group_code';";
           if(!$result = $db->query($sqllink)){
           die('There was an error running the query [' . $db->error . ']');
           }
    }
        
       function rightlinks(){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "SELECT Code, URL_Code, URL FROM urls ORDER BY URL ASC";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
           
    }
    
    function ClearLinks($codeclear){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "DELETE FROM  urls WHERE Code = '$codeclear';";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
    }

    function ClearOneLink($groupcode, $urlcode){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "DELETE FROM  urls WHERE Code = '$groupcode' AND URL_Code = '$urlcode'";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
    }
    
    
    function openlinks($opencode){
        if(isset($db) == false){
            require_once("../PHPfunctions/DatabaseConnection.php");
            $db_class = new db_class();
            $db = $db_class->db_call();
        }

        $sql = "SELECT Code, URL_Code, URL FROM urls WHERE Code = '$opencode';";
           if(!$result = $db->query($sql)){
           die('There was an error running the query [' . $db->error . ']');
           }
           return $result;
           
    } 
        
        
        
        
}//end request class
           
?>