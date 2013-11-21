<!DOCTYPE html>
<html>
<style>
    table,th,td
    {
        border:1px solid black;
        padding: 5px;
    }
</style>
<?php
error_reporting(E_ALL);


require_once('config.php');


$token  = (isset($_REQUEST['token']))? $_REQUEST['token'] : '';

$questionURL = 'http://services.h2wellness.com/rest/hra/questions/1?token='.$token;
$ch = curl_init($questionURL);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$output = curl_exec($ch);
curl_close($ch);
$output = substr($output, strpos($output, '{'));

if($_SERVER['SERVER_NAME']=='localhost'){var_dump($output);}


if($output){

    $decodedArray = json_decode($output, true);

    $responseArray = ($decodedArray['data']['response']);

    foreach($responseArray as $category){
        echo  'questionnarire <br /> <table width="1200px:"> <th> id </th><th> name</th> ';
        echo   '<tr><td>'.$category['QuestionnaireSection']['id'].'</td><td>'.$category['QuestionnaireSection']['name'].'</td></tr>';

        if($_SERVER['SERVER_NAME']=='localhost'){
            echo '<tr><td colspan="3"><pre>';
            print_r($category['QuestionnaireSection']);
            echo '</pre></td></tr>';
        }

        echo '<tr><td>  </td><td colspan="2">';

        echo  'questions <br /> <table width="1000px:"><th> id </th><th> qid </th> <th>title</th>';
           foreach($category['Questionnaire'] as $question ){
               echo '<tr><td>'.$question['id'].'</td><td>'.$question['questionnair_section_id'].'</td><td>'.htmlspecialchars($question['title']).'</td></tr>';
               if($_SERVER['SERVER_NAME']=='localhost'){
                   echo '<tr><td colspan="3"><pre>';
                   print_r($question);
                   echo '</pre></td></tr>';
               }

           }
        echo  '</table>';


        echo '</td></tr></table>';
     }

}


?>

</html>