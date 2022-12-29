<!DOCTYPE html>
<html>
<body>

<?php

    //error logs
    error_reporting(E_ALL); 
    ini_set('display_errors','1'); 

    date_default_timezone_set('Asia/Tokyo');

    if( isset($_POST['submit']) ) {
        echo "submit.<br>";
    } else if ( isset($_POST['delete']) ) {
    echo "delete.<br>";
    } else {
        echo "Nah..<br>";
    }

    $fileName = "tempData.txt";
    $line = file($fileName);
    $lines = count($line); //the number of lines in tempData.txt
    $lastLine = array_pop($line); //might not need this
    if (isset($_POST['submit'])) {
        $name = $_POST["name"];
        $tempTdy = $_POST["temperature"]; //temperature today
    } else {
        $name = "test";
        $tempTdy = "test2"; //temperature today
    }
    
    $timestamp = date("m-d-Y H:i");

    //temperature yesterday (if exists)
    if ( 0 < $lines ) {
        $tempYdy = substr($lastLine, strpos($lastLine, ":") + 4);   
    }

    //output messsages
    echo "Oh, $name-san.<br>";
    echo "Your temperature today is $tempTdy.<br>";
    if ($tempTdy < 37.5) {
        echo "That's good.<br>";
    } elseif ( (0 < $lines ) && $tempTdy < $tempYdy) {
        echo "That's better.<br>";
    } else {
        echo "That's high.<br>";
    }
    echo "<br>";

    // open the file
    $file = fopen($fileName, "a+");

    // output old records
    for ($x = 0; $x < $lines; $x++ ) {
            $oldRecord = fgets($file);
            ?>
            <input type="checkbox" name="check_delete[]"> 
    <?php
            echo $oldRecord."<br>";
        }
        ?>

     <form action="" method="post">
     <input type="submit" name="delete" value="Delete">
     </form>

<?php
    // input temperature today into the file
    //test
    if (isset($_POST['submit'])) {
        fwrite($file, $timestamp);
        fwrite($file, " ");
        fwrite($file, $tempTdy);
        fwrite($file, "\n");
    }
    fclose($file);

?>

<?php
if(isset($_POST['delete'])){//to run PHP script on submit
    echo "clicked!";
if(!empty($_POST['check_delete'])){
// Loop to store and display values of individual checked checkbox.
foreach($_POST['check_delete'] as $selected){
echo $selected."</br>";
}
}
}
?>

<script>
    let a = document.getElementsByName('check_delete')
    <!-- alert(a); -->
</script>

</body>
</html>
