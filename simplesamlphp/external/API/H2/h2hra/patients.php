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
        $patients[$row['id']] = $row['firstname'].'   '.$row['lastname'];
    }
    $PDO = null;
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}


//showArray($patients);
?>

<form action="createPatient.php">
    <select name="pid">
        <?php
        foreach($patients as $id => $name){
            echo '<option value="'.$id.'" > '.$name.' </option>';
        }
        ?>

    </select>

    <input type="submit" value="createPatient" />
</form>


<form action="getAccessment.php">
    <select name="pid">
        <?php
        foreach($patients as $id => $name){
            echo '<option value="'.$id.'" > '.$name.' </option>';
        }
        ?>

    </select>

       <input type="submit" value="getassessment" />
</form>