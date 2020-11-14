<?php

namespace models;

require __DIR__."/../lib/Core.php";
use lib\Core;
use PDO;

class DateiInfos
{

    protected $core;

    function __construct() {
        $this->core = Core::getInstance();
    }

    // Get all stuff
    public function getAllStuff() {
        $r = array();

        $sql = "SELECT * FROM dateiinfo";
        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute()) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }

    // Insert a new file
    public function insertFile($filename, $hash, $password="", $timeTolive="") {
        try {
            $timeStamp = "";
            if ($timeTolive != "") {
                $time = new \DateTime(date("h:i:sa"));
                $time->add(new \DateInterval('PT' . $timeTolive . 'M'));
                $timeStamp = $time->format('H:i:sa');
            }
            $sql = "INSERT INTO dateiinfo (filename, hash, password, timeToLive) 
					VALUES (?, ?, ?, ?);";
            $stmt = $this->core->dbh->prepare($sql);
            if ($stmt->execute([$filename, $hash, $password, $timeStamp])) {
                return $this->core->dbh->lastInsertId();
            } else {
                return '0';
            }
        } catch(PDOException $e) {
            return $e->getMessage();
        }

    }

    // Get an existing file information
    public function getFileInformation($name) {
        $r = array();

        $sql = "SELECT * FROM dateiinfo WHERE ? LIKE hash";
        $stmt = $this->core->dbh->prepare($sql);

        if ($stmt->execute([$name])) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }

    // Get the password
    public function getPassword($name)
    {
       $result = $this->getFileInformation($name);

       return $result[0]['password'];
    }

    // Delete entry
    public function deleteRow($name) {
        $sql = "DELETE FROM dateiinfo WHERE ? LIKE hash";
        $stmt = $this->core->dbh->prepare($sql);
        $r = 0;
        if ($stmt->execute([$name])) {
            $r = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $r = 0;
        }
        return $r;
    }

    // Get timeToLive
    public function getTimeToLive($name)
    {
        $result = $this->getFileInformation($name);

        $timeToLive = $result[0]['timeToLive'];

        if ($timeToLive == strtotime("00:00:00")) {
            return True;
        }

        $time = new \DateTime(date("h:i:sa"));
        $timeStamp = $time->format('H:i:sa');
        if ($timeToLive < $timeStamp) {
            $filepath = "../uploads/".$name;
            $this->deleteRow($name);
            unlink($filepath);
            return False;
        } else {
            return True;
        }
    }
}