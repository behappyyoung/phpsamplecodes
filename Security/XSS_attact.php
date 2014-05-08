<?php
$name = $_GET['name'];
?>


<html>

<body>
<?php echo '<div> hi '. $name . '</div>'; ?>

<div> Intention</div>

<form action="" method="get">
    <input type="text" name="name" value=""/>
    <button type="submit" > lgoin</button>
</form>

<div> Attacking  - The XSS Auditor [ chrome ] can block this one </div>
<form action="" method="get">
    <input type="text" name="name" value="attact user<script>alert('attacked')</script>"/>
    <button type="submit" > lgoin</button>
</form>


<div> Attacking  - The XSS Auditor [ chrome ] can block this one </div>
<a href="?name=%61%74%74%61%63%74%20%75%73%65%72%3c%73%63%72%69%70%74%3e%61%6c%65%72%74%28%27%61%74%74%61%63%6b%65%64%27%29%3c%2f%73%63%72%69%70%74%3e" > attack </a>


</body>
</html>