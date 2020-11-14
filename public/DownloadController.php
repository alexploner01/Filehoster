<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . "/../classes/DateiInfos.php";

class DownloadController
{

    static function prepareDownload($name, $app, $response)
    {

        $filepath = "../uploads/" . $name;

        if (is_file($filepath)) {
            echo "file found";

            $array = self::getDatafromDB($name);
            $real_name = $array[0]['filename'];
            //echo $real_name;
            $app->view->render($response, "download.phtml", array('name' => $real_name, 'wrongPassword' => ""));
        } else {
            echo "file does not exist";
        }

    }

    static private function getDatafromDB($name)
    {

        $dateiinfos = new \models\DateiInfos();

        return $dateiinfos->getFileInformation($name);
    }

    public static function checkCredentialsForDownload($password, $name, $response, $app)
    {
        $filepath = "../uploads/" . $name;
        if (is_file($filepath)) {

            if (self::checkTimeStamp($name)) {

                $hash = self::getPassword($name);


                if (password_verify($password, $hash)) {


                    $file_hash = self::getDatafromDB($name)[0]["hash"];
                    $file_name = self::getDatafromDB($name)[0]["filename"];
                    $file = '../uploads/' . $file_hash;

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename=' . str_replace(' ', '_', $file_name));
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                    exit;

                } else {

                    $wrongPassword = "<p style=\"color: red; font-size: 14px; opacity: <?=$opacity?>\">wrong password</p>";
                    $app->view->render($response, "download.phtml", array('name' => self::getDatafromDB($name)[0]['filename'], 'wrongPassword' => $wrongPassword));
                }
            } else {
                $wrongPassword = "<p style=\"color: red; font-size: 14px; opacity: <?=$opacity?>\">file time expired</p>";
                $app->view->render($response, "download.phtml", array('name' => "Error", 'wrongPassword' => $wrongPassword));
            }
        } else {
            echo "file does not exist";
        }

    }

    private static function getPassword($name)
    {
        $dateiinfos = new \models\DateiInfos();

        return $dateiinfos->getPassword($name);
    }

    private static function checkTimeStamp($name)
    {
        $dateiinfos = new \models\DateiInfos();

        return $dateiinfos->getTimeToLive($name);
    }
}