<?php
session_start();
if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
}else{
    include_once "FirebaseToken.php";
    $tokenGen = new Services_FirebaseTokenGenerator("zbOxbPg0yibcUDwXZd8UjtQZXIXflVEychoYmHUL");
    $token = $tokenGen->createToken(array("uid" => "1"));
    $_SESSION['token'] = $token;
}
?>
<!DOCTYPE html>
<html >
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <script src="https://cdn.firebase.com/js/client/2.1.2/firebase.js" ></script>

</head>
<body >
<div >
    <div id="firebase_value"></div>
</div>
</body>
<script>
    //CREATE A FIREBASE
    var fb = new Firebase("https://intense-torch-6195.firebaseio.com");
    //SAVE DATA
    //fb.child('Testing').set({'jstesting': { name: "young park", company:'hsvl' }});
    //LISTEN FOR REALTIME CHANGES
    fb.child('Testing').child('jstesting').on("value", function(data) {
        var name = data.val() ? data.val().name : "";
        console.log("My name is " + name);
        document.getElementById('firebase_value').innerHTML = name;
    });


    fb.authWithCustomToken('<?php echo $token;?>', function(error, authData) {
        if (error) {
            console.log("Login Failed!", error);
        } else {
            console.log("Login Succeeded!", authData);
        }
    });

</script>



</html>