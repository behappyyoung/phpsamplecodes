<?php

require_once('config.php');

require_once('functions.php');

try {

    $PDO = new PDO('mysql:host=localhost;dbname='.DATABASE, 'h2hra', 'h2hra');
    $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $query = 'Select * from patient ';
    $stmt = $PDO->query($query);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($results as $row){
        showArray($row);
        $patients[] = array( 'first_name' => $row['firstname'],
            'last_name' => $row['lastname'],'gender' => $row['gender'],'email' => $row['email'],
            'username' => $row['username'], 'password' => $row['password'], 'token'=>$row['token']);
    }
    $PDO = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>
<!DOCTYPE html>
<html>
<style>
    table th, td {width: 200px;text-align: center;}
    div.action:hover {color: red; background-color: blue;}
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
    var sessionUrl = '<?=HRA_CREATE_SESSION?>';
    var createUrl = '<?=HRA_CREATE_PATIENT?>';
    var assessmentUrl = '<?=HRA_CREATE_ASSESSMENT?>';
    var questionUrl = '<?=HRA_GET_QUESTIONS?>';



    function saveToken(myjson){
        $.ajax({
            type: "POST",
            url: 'saveToken.php',
            dataType:'html',
            data: { myjson: myjson },
            success: function( data ) {
                console.log(data);
            },
            error: function(msg){
                alert('error' + msg);
            }
        });
    }


    function getSession(sessionPara){

        var mypara =  sessionPara.split('/');
        $.ajax({
            type: "POST",
            url: sessionUrl,
            dataType:'json',
            data: {username: mypara[0], password : mypara[1]},
            success: function( data ) {
                console.log(data);
                saveToken(data);

            },
            error: function(msg){
                alert('error' + msg);
             }
        });

    }

    function pCreate(parray){

        $.ajax({
            type: "POST",
            url: createUrl,
            data: parray,
            success: function( data ) {
                alert( " Created " );
            },
            error: function(msg){
                alert('error' + msg);
            }
        });


    }

    function getAssessment(token){
        $.ajax({
            type: "GET",    // doc says POST.. but,, GET
            url: assessmentUrl,
            data: {token : token },
            success: function( data ) {
                alert( " AssementID : " + data.data.response.hra_id );
                /*
                if($.isNumeric(data.data.response.hra_id )){
                    window.document.href = 'showQuestions.php?hra_id='+data.data.response.hra_id;
                }else{
                    alert('token error ');
                }
                */
            },
            error: function(msg){
                alert('error' + msg);
            }
        });
    }

    function getQuestions(token){
            $.ajax({
                type: "GET",    // doc says POST.. but,, GET
                url: questionUrl,
                data: {token : token },
                success: function( data ) {
                  //  alert( " AssementID : " + data.data );
                    window.location = 'showQuestions.php?questions='+data.data.response;
                },
                error: function(msg){
                    alert('error' + msg);
                }
            });

    }
</script>
    <table><th>id</th> <th> name </th><th> token </th> <th> action </th>

<?php
foreach($patients as $patient){
    $jason = json_encode( array_slice($patient, 0, 6) );
    $sessionPara = $patient['username'].'/'.$patient['password'];
    $action = ($patient['token'] !='')? '<div class="action" onClick=\'getAssessment("'.$patient['token'].'");\' > getAssessment </div><div class="action" onClick=\'window.location="showQuestions.php?token='.$patient['token'].'";\' > getQuestions </div>'
        : '<div class="action" onClick=\'pCreate('.$jason.');\' > pCreate </div><div class="action" onClick=\'getSession("'.$sessionPara.'");\' > getSession </div>';

    echo '<tr><td> '.$patient['email'].'</td><td>'.$patient['first_name'].'  '. $patient['last_name'].' </td><td>'.$patient['token'].'</td><td>'.$action.'</td></tr>';
}
?>

</table>
Result : <br />
<div id="result">

</div>

</html>