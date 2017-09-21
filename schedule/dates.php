<!DOCTYPE html>
<html>
<head>
    <title>Table</title>
    <link rel="stylesheet" type="text/css" href="dates.css">
</head>
<body>
<?php
    $server="localhost";
    $username="root";
    $password="";
    $dbname = "employeedb";
    $conn = new mysqli($server, $username, $password,$dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['submit']))
    {
        $year = mysqli_real_escape_string($conn,$_POST['year']);
        $sem = mysqli_real_escape_string($conn,$_POST['sem']);
        $type = mysqli_real_escape_string($conn,$_POST['type']);
        $days =mysqli_real_escape_string($conn,$_POST['days']);
        $time = mysqli_real_escape_string($conn,$_POST['time']);
        if($type=="I")
            $mid = $_POST['mid'];
        else
            $mid ="";
        $h = (int)substr($time, 0, 2);
        $m = (int)substr($time, 3, 4);

        $time=date("h:i A",strtotime($time));

        $bm=$m-10;
        if($bm<0){
            $bm+=60;
            if($h==0)
                $bh=23;
            else    
                $bh=$h-1;
            $btime=sprintf('%02d:%02d',$bh,$bm);
        }
        else
            $btime=sprintf('%02d:%02d',$h,$bm);
        $btime=date("h:i A",strtotime($btime));

        if($type=="I"){
            $bh=$h+1;
            $bm=$m+30;
            if($bm>=60){
                $bm-=60;
                $bh+=1;
            }
            if($bh>=24)
                $etime=sprintf('%02d:%02d',$bh-24,$bm);
            else
                $etime=sprintf('%02d:%02d',$bh,$bm);
            $etime=date("h:i A",strtotime($etime));
        }
        else{
            $bh=$h+3;
            if($bh>24)
                $etime=sprintf('%02d:%02d',$bh-24,$m);
            else
                $etime=sprintf('%02d:%02d',$bh,$m);
            $etime=date("h:i A",strtotime($etime));
        }

        $qry=$conn->query("DELETE from details");
        if($conn->query("INSERT INTO `details` VALUES ('$year', '$sem', '$type', '$mid', '$time', '$btime', '$etime', '$days')")===true){}
        else
            echo "Error: <br>" . $conn->error;

        // echo "year :" .$year." sem : ".$sem." exam type : ".$type." days : ".$days." time : ".$time." exam time".$etime." Befoe time:".$btime." mid:".$mid;
        echo "<form action='checkBoxTable.php' method='post'>";
        echo "<table>";
        $j=1;
        for($i=100;$i<$days+100;$i++)
            echo "<tr><td>Select day ".$j++."</td><td><input type='date' name='$i' required/></td></tr>";
        echo "<table>";
        echo "<input type='submit' name='Listofdates' id='button' >";
        echo "</form>";
    }

?>
</body>
</html>