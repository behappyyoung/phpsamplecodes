<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 3/14/14
 * Time: 5:07 PM
 */


$req_url = 'https://api.twitter.com/oauth/request_token';
$authurl = 'https://api.twitter.com/oauth/authorize';
$acc_url = 'https://api.twitter.com/oauth/access_token';
$api_url = 'https://api.twitter.com/1.1/account';
$conskey = 'YOURAPPconskey';
$conssec = 'YOURAPPconssec';


session_start();

// In state=1 the next request should include an oauth_token.
// If it doesn't go back to 0
if(!isset($_GET['oauth_token']) && $_SESSION['state']==1) $_SESSION['state'] = 0;
try {
    $oauth = new OAuth($conskey,$conssec,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_URI);
    $oauth->enableDebug();
    if(!isset($_GET['oauth_token']) && !$_SESSION['state']) {
        $request_token_info = $oauth->getRequestToken($req_url);
        $_SESSION['secret'] = $request_token_info['oauth_token_secret'];
        $_SESSION['state'] = 1;
        header('Location: '.$authurl.'?oauth_token='.$request_token_info['oauth_token']);
        exit;
    } else if($_SESSION['state']==1) {
        $oauth->setToken($_GET['oauth_token'],$_SESSION['secret']);
        $access_token_info = $oauth->getAccessToken($acc_url);
        $_SESSION['state'] = 2;
        $_SESSION['token'] = $access_token_info['oauth_token'];
        $_SESSION['secret'] = $access_token_info['oauth_token_secret'];
    }
    $oauth->setToken($_SESSION['token'],$_SESSION['secret']);
    $oauth->fetch("$api_url/verify_credentials.json");
    $json = json_decode($oauth->getLastResponse());
    print_r($json);
} catch(OAuthException $E) {
    print_r($E);
}

?>