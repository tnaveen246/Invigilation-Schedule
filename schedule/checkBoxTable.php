<!DOCTYPE html>
<html>
<head>
    <title>Table</title>
    <!-- <link rel="stylesheet" type="text/css" href="stylesheet.css"> -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        table{
            font-size: 15px;
        }
        body{
            text-align: center;
            margin: 5%;
        }
    </style>
</head>
<body>
<?php

    $server="localhost";
    $username="root";
    $password="";
    $dbname = "employeedb";
    $conn = new mysqli($server, $username, $password,$dbname);
    $detailsdbconn = new mysqli('localhost', 'root','','datesanddetailsdb');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $qry=$conn->query("SELECT number from CreatedTables");
    while($n=$qry->fetch_assoc())
        $num=$n['number'];

        $qry=$detailsdbconn->query("SELECT days from detailstable".$num);
        if($qry->num_rows > 0) {
            while($row = $qry->fetch_assoc()) 
            {
                $days=$row['days'];
            }
        }

        $qry=$detailsdbconn->query("CREATE TABLE datestable".$num."(dates varchar(15))");

        $detailsdbconn->query("DELETE FROM datestable".$num);

        for($i=100;$i<$days+100;$i++){
            $date=$_POST[$i];
            $qry=$detailsdbconn->query("INSERT into datesTable".$num." VALUES('$date')");
        }

        $sno=1;
    	$Array=array();
        $num_days=0;
        echo "<table class='table' border='1'><tr><th>S.NO</th><th>NAME OF THE FACULTY</th><th>DEPT</th>";

        $datesQuery=$detailsdbconn->query("SELECT * FROM datestable".$num);

        if ($datesQuery->num_rows > 0) {
            while($d = $datesQuery->fetch_assoc()) {
                $timeStamp=strtotime($d["dates"]);
                $weaklydays=date('D',$timeStamp);
                $Array[$num_days++]=$weaklydays;
                echo "<th>".$d["dates"]."<br>(".$Array[$num_days-1].")</th>";
            }
            echo "</tr>";
        }

        echo "<form action='Invigilation.php' method='post'>";

        $details=$detailsdbconn->query("SELECT * FROM detailsTable".$num);
        if($details->num_rows>0){
            while ($row = $details->fetch_assoc()) {
                $time=$row['time'];
                $etime=$row['etime'];
                $year=$row['year'];
            }
        }

        if($year=='I')
            $year='1';
        elseif ($year=="II")
            $year='2';
        elseif ($year=="III")
            $year='3';
        else
            $year='4';
        $time24=date("H:i",strtotime($time));
        $etime24=date("H:i",strtotime($etime));
        $h = (int)substr($time24, 0, 2);
        $m = (int)substr($time24, 3, 4);
        $eh = (int)substr($etime24, 0, 2);
        $em = (int)substr($etime24, 3, 4);
        $hours=Array();
        $min=Array();
        $hours[1]=8;
        $min[1]=30;
        $hours[2]=9;
        $min[2]=20;
        $hours[3]=10;
        $min[3]=30;
        $hours[4]=11;
        $min[4]=20;
        $hours[5]=12;
        $min[5]=10;
        $hours[6]=14;
        $min[6]=0;
        $hours[7]=15;
        $min[7]=0;
        $hours[8]=16;
        $min[8]=0;
        $hours[9]=17;
        $min[9]=0;
        $c="";
        for ($i=1; $i <9 ; $i++) {
            if((($h==$hours[$i]&&$m>=$min[$i])||$h<$hours[$i])||(($h==$hours[$i+1]&&$m<$min[$i+1])||$h<$hours[$i+1])){
                break;
            }
        }
        $f=0;
        for ($j=$i; $j <9&&$f!=1 ; $j++) {
            if(($eh==$hours[$j+1]&&$em>=$min[$j+1])||$eh>$hours[$j+1]){
                if($eh==$hours[$j+1]&&$em==$min[$j+1])
                    $f=1;
                $c=$c.$j;
            }
            else{
                $c=$c.$j;
                break;
            }
        }
        $qry=$conn->query("SELECT * from timetable where Year Like '%".$year."%'");

        if ($qry->num_rows > 0) {
            
        	while($row = $qry->fetch_assoc()) {
                $n=0;
                $f=0;
                while ($n<$num_days) {  
                    if (strpos($row[$Array[$n++]],$c)!== false){
                        $f=1;
                        break;
                    }
                }
                if($f==1){
                    $k=1;
                    $n=0;
                    echo "<tr><td>".$sno++."</td><td>".$row["Name"]."</td><td>".$row["Branch"]."</td>";
                    while ($n<$num_days) {  
                        if (strpos($row[$Array[$n++]],$c)!== false)
                            echo "<td><center><input type='checkBox' name=".$row['ID'].$k++." value='checked'></center></td>";
                        else
                            echo "<td></td>";
                    }
                echo "</tr>";
                }
        	}
            echo "</table>";
            echo "<div id='btn'><input type='submit' name='Print' value='$c'></div></form>";

        }
?>
<ul class="pager">
    <li><a href="index.php">Home</a></li>
</ul>
</body>
</html>