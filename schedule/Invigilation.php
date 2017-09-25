<!DOCTYPE html>
<html>
<head>
	<title> Invigilation Schedule</title>
    <!-- <link rel="stylesheet" type="text/css" href="invigilation.css"> -->
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
            margin-left: 5%;
            margin-right: 5%;
        }
    </style>
</head>
<body>
	<?php
		error_reporting(0);

		$conn = new mysqli("localhost","root","","employeedb");
        $detailsdbconn = new mysqli('localhost', 'root','','datesanddetailsdb');
        $tablesdbconn = new mysqli('localhost', 'root','','createdtablesdb');

		if ($conn->connect_error) {
	    		die("Connection failed: " . $conn->connect_error);
		}
        $qry=$conn->query("SELECT number from createdtables");      //Query
        while($n=$qry->fetch_assoc())
            $num=$n['number'];

		$details=$detailsdbconn->query("SELECT * FROM detailstable".$num);       //Query
		if($details->num_rows>0){
			while ($row = $details->fetch_assoc()) {
				$year=$row['year'];
				$sem=$row['sem'];
				$type=$row['type'];
				$mid=$row['mid'];
				$time=$row['time'];
				$btime=$row['btime'];
				$etime=$row['etime'];
			}
		}
		echo "<center><h3>VISHNU INSTITUTE OF TECHNOLOGY - BHIMAVARAM</h3</center>";
		echo "<center><h4>".$year."-".$sem;
		if($type=='I')
			echo"   ".$mid." MID ";
		else
			echo " SEMISTER ";
		echo"INVIGILATION DUTIES</h4></center>";

        if($year=='I')
            $year='1';
        elseif ($year=="II")
            $year='2';
        elseif ($year=="III")
            $year='3';
        else
            $year='4';
        $periods=$_POST['Print'];

        $sno=1;
    	$Array=array();
        $i=0;
        echo "<div class='container-fluid'><table border='1' class='table'>";
        
        $datesQuery=$detailsdbconn->query("SELECT * FROM datestable".$num);        //Query

        $periodsount=$datesQuery->num_rows;
        echo "<tr><td colspan='3' name='firstcol'>";
        echo"Exam Timing : ".$time." to ".$etime."</td><td colspan='$periodsount' name='secondcol'>Reporting Time : ".$btime."</td></tr>" ;
        echo "<tr><th>S.NO</th><th>NAME OF THE FACULTY</th><th>DEPT</th>";

        $tablesdbconn->query("CREATE TABLE Table".$num."(sno int(3),name varchar(30),branch varchar(5))");       //Query
        $tablesdbconn->query("DELETE FROM Table".$num);

        if ($datesQuery->num_rows > 0) {
            $datenum=1;
            while($d = $datesQuery->fetch_assoc()) {
                if($tablesdbconn->query("ALTER TABLE table".$num." ADD day".$datenum++." varchar(12)")===false)      //Query
                    echo "Error:  " . $conn->error;
                $timeStamp=strtotime($d["dates"]);
                $weaklydays=date('D',$timeStamp);
                $Array[$i++]=$weaklydays;
                echo "<th>".$d["dates"]."<br>(".$Array[$i-1].")</th>";
            }
            echo "</tr>";
        }

        $qry=$conn->query("SELECT * from timetable where Year Like '%".$year."%'");              //Query

        if ($qry->num_rows > 0) {     
            while($row = $qry->fetch_assoc()) {
                $n=0;
                $f=0;
                while ($n<$i) {
                    if (strpos($row[$Array[$n++]],$periods)!== false){
                        $f=1;
                        break;
                    }
                }
                if($f==1){
                    $k=1;
                    $n=0;
                    $f=0;
                    while ($n<$i) {  
                        if (strpos($row[$Array[$n++]],$periods)!== false){
                            if($_POST[$row['ID'].$k++]){
                                $f=1;
                                break;
                            }
                        }
                    }
                    $Sellected=Array();
                    if($f==1){
                        $sn=1;
                        $k=1;
                        $n=0;
                        
                        echo "<tr>
                                <td>".$sno."</td>
                                <td>".$row["Name"]."</td>
                                <td>".$row["Branch"]."</td>";
                                $name=$row["Name"];
                                $dept=$row["Branch"];

                        $tablesdbconn->query("INSERT INTO table".$num." (`sno`,`name`,`branch`) values('$sno','$name','$dept')");
                        while ($n<$i) {
                            if (strpos($row[$Array[$n++]],$periods)!== false){
                                if($_POST[$row["ID"].$k++]){
                                    $datesCount=$n;
                                    $tablesdbconn->query("UPDATE table".$num." SET day".$datesCount." = '$time' WHERE `sno` = ".$sno);
                                    echo "<td>".$time."</td>";
                                }
                                else{
                                    echo "<td></td>";
                                    $datesCount++;
                                }
                            }
                            else{
                                echo "<td></td>";
                                $datesCount++;
                            }
                        }
                        echo "</tr>";
                        $sno++;
                    }
                }
            }
            echo "</table></div>";
            $num1=$num+1;
            $conn->query("UPDATE createdtables set number='$num1' where sno=1");
        }
	?>
<ul class="pager">
    <li><a href="index.php">Home</a></li>
</ul>
</body>
</html>