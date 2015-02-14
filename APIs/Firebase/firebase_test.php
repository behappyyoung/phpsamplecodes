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
    fb.authWithCustomToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkIjp7InVpZCI6IjEifSwidiI6MCwiaWF0IjoxNDIzNTAzMjA0fQ.TqZez14ZQYTVJiVvsJ0FDFVbhn0d_yWNkWrD-IpZcYM', function(error, authData) {
        if (error) {
            console.log("Login Failed!", error);
        } else {
            console.log("Login Succeeded!", authData);
        }
    });


    //SAVE DATA
    //fb.child('Testing').set({'jstesting': { name: "young park", company:'hsvl' }});
    //LISTEN FOR REALTIME CHANGES
    fb.child('Testing').child('jstesting').on("value", function(data) {
        var name = data.val() ? data.val().name : "";
        console.log("My name is " + name);
        document.getElementById('firebase_value').innerHTML = name;
    });



</script>



</html>