<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

    <?php

    //error logs
    error_reporting(E_ALL); 
    ini_set('display_errors','1'); 

    date_default_timezone_set('Asia/Tokyo');

    $fileName = "tempData.txt";
    $line = file($fileName);
    $lines = count($line); //the number of lines in tempData.txt
 
    if (isset($_POST['submit'])) {
        if (isset($_POST["name"]) && isset($_POST["temperature"])) {   
            $_SESSION["lastLine"] = array_pop($line); //might not need this
            $_SESSION["name"] = $_POST["name"];
            $_SESSION["tempTdy"] = $_POST["temperature"]; //temperature today
            $timestamp = date("m-d-Y H:i"); //timestamp
        }
    }

    //temperature yesterday (if exists)
    if ( 0 < $lines ) {
        $tempYdy = substr($_SESSION["lastLine"], strpos($_SESSION["lastLine"], ":") + 4);   
    }

    //output messsages
    echo "Oh, " . $_SESSION["name"]. "-san.<br>";
    echo "Your temperature today is " . $_SESSION["tempTdy"]. ".<br>";
    if ($_SESSION["tempTdy"] < 37.5) {
        echo "That's good.<br>";
    } elseif ( (0 < $lines ) && $_SESSION["tempTdy"] < $tempYdy) {
        echo "That's better.<br>";
    } else {
        echo "That's high.<br>";
    }
    echo "<br>";

    // open the file
    $file = fopen($fileName, "a+");
    ?>

    <form action="" method="post">

    <div>
<?php
        // output old records
    for ($x = 1; $x <= $lines; $x++ ) {
            $oldRecord = fgets($file);
?>
            <label>
            <input type="checkbox" name="checkbox[]" value="<?php echo htmlspecialchars($x); ?>">
<?php
          echo "<span>$oldRecord</span><br></label>";
        }
?>
        
    </div>
    <input type="submit" name="delete" value="delete">
     </form>

<?php
    // input temperature today into the file
    //test
    if (isset($_POST['submit'])) {
        if (isset($_POST["name"]) && isset($_POST["temperature"])) {
            fwrite($file, $timestamp);
            fwrite($file, " ");
            fwrite($file, $_SESSION["tempTdy"]);
            fwrite($file, "\n");
        }
    }
    fclose($file);

    ?>

<?php

    if(isset($_POST['delete'])){
        //var_dump($_POST);
        if(isset($_POST['checkbox'])){
            $checkbox = $_POST['checkbox'];
            $file_out = file($fileName); // Read the whole file into an array
            $count_delete = 0;
        // $values = implode(", ", $checkbox);
            //print_r($checkbox);
            //echo $values."<br>";
            foreach ($checkbox as $cb_number) {
                $row_number = $cb_number - 1 - $count_delete;    // Number of the line we are deleting
                $file_out = file($fileName); // Read the whole file into an array
            
                //Delete the recorded line
                unset($file_out[$row_number]);

                //Recorded in a file
                file_put_contents("tempData.txt", implode("", $file_out));
                $count_delete = $count_delete + 1;
            }
        // reload / format the page
        header("Refresh:0");
        }
    }

?>

</body>
</html>
