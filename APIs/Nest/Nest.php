<?php
/**
 * Created by Young Park.
 * Date: 2/11/15
 *
 */

define('DS', DIRECTORY_SEPARATOR);
class Nest{

    const CLIENT_ID = 'f497bb7d-8d4d-4760-8d68-58ba9f9ceb1d';   // client id for young test
    const CLIENT_SECRET = 'JZuTwjm8A9Nq3qvTNZFzL96mu';          // client secret for young test
    static  $error_root;

    function __construct(){
        $this::$error_root = dirname(__FILE__).DS.'Logs'.DS.'nest.log'; // for error log
    }

    // curl request... default POST
    private function postJsonData($url, $para, $header, $post=true){
        $ch = curl_init($url);
        if($post){
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS, $para);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER,    $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        $response = curl_exec($ch);
        $this->serviceLog('curl response : ', $response);
        curl_close($ch);
        return $response;
    }

    // get access token using code from login url
    function getAccessToken($code){
        $url = 'https://api.home.nest.com/oauth2/access_token?code='.$code.'&client_id='.$this::CLIENT_ID.'&client_secret='.$this::CLIENT_SECRET.'&grant_type=authorization_code';
        $header = array("Content-type: application/json");
        $result = $this->postJsonData($url, '', $header, true);
        $this->dumpLog('access token result : ', $result);
        $result_array = json_decode($result, true);
        if(isset($result_array['access_token'])){
            return array('result' => true, 'access_token'=> $result_array['access_token']);
        }else{
            return array('result' => false, 'error'=> $result);
        }
    }

    // get device information using access token
    function showDevices($access_token){
        $url = "https://developer-api.nest.com/?auth=".$access_token;
        $header = array("Content-type: application/json", "Content-type: text/event-stream");
        $result = $this->postJsonData($url, '', $header, false);
        $this->dumpLog('get device result : ', $result);
        $result_array = json_decode($result, true);
        if(isset($result_array['devices'])){
            $this->dumpLog('get device result : ', $result_array);
            return array('result' => true, 'devices'=> $result_array['devices']);
        }else{
            return array('result' => false, 'error'=> $result);
        }
    }

    // get structure information using access token
    function showStructures($access_token){
        $url = "https://developer-api.nest.com/?auth=".$access_token;
        $header = array("Content-type: application/json", "Content-type: text/event-stream");
        $result = $this->postJsonData($url, '', $header, false);
        $this->dumpLog('get device result : ', $result);
        $result_array = json_decode($result, true);
        if(isset($result_array['structures'])){
            $this->dumpLog('get Structure result : ', $result_array);
            return array('result' => true, 'structures'=> $result_array['structures']);
        }else{
            return array('result' => false, 'error'=> $result);
        }
    }

    // get meta data using access token
    function showMetadata($access_token){
        $url = "https://developer-api.nest.com/?auth=".$access_token;
        $header = array("Content-type: application/json", "Content-type: text/event-stream");
        $result = $this->postJsonData($url, '', $header, false);
        $this->dumpLog('get device result : ', $result);
        $result_array = json_decode($result, true);
        if(isset($result_array['metadata'])){
            $this->dumpLog('get metadata result : ', $result_array);
            return array('result' => true, 'metadata'=> $result_array['metadata']);
        }else{
            return array('result' => false, 'error'=> $result);
        }
    }


    function serviceLog($title,  $mssage){
        error_log("\n ".$title.' : '. $mssage ."\n", 3, $this::$error_root);
    }

    function dumpLog($title, $dump){
        ob_start();
        var_dump($dump);
        $logs = ob_get_contents();
        ob_end_clean();
        error_log("\n ".$title.' : '. $logs ."\n", 3, $this::$error_root);
    }

}





