<!DOCTYPE html>
<html>
<head>
	<title> Invigilation Schedule</title>
    <link rel="stylesheet" type="text/css" href="invigilation.css">
</head>
<body>
	<?php
		error_reporting(0);
		$server="localhost";
		$username="root";
		$password="";
		$dbname = "employeedb";
		$conn = new mysqli($server, $username, $password,$dbname);

		if ($conn->connect_error) {
	    		die("Connection failed: " . $conn->connect_error);
		}

		$details=$conn->query("SELECT * FROM details");
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
		echo "<h4>VISHNU INSTITUTE OF TECHNOLOGY - BHIMAVARAM</h4>";
		echo"<pre><h4>".$year."-".$sem;
		if($type=='I')
			echo"   ".$mid." MID ";
		else
			echo " SEMISTER ";
		echo"INVIGILATION DUTIES</h4></pre>";

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
        echo "<table border='1'>";
        $datesQuery=$conn->query("SELECT * FROM dates");
        $periodsount=$datesQuery->num_rows;
        $periodsount+=3;
        echo "<tr><td colspan='3' name='firstcol'>";
        echo"Exam Timing : ".$time." to ".$etime."</td><td colspan='$periodsount-3' name='secondcol'>Reporting Time : ".$btime."</td></tr>" ;
        echo "<tr><th>S.NO</th><th>NAME OF THE FACULTY</th><th>DEPT</th>";

        /*if($conn->query("CREATE TABLE test(Sno int(3),Name varchar(30),Branch varchar(10))")===TRUE){}
        else
            echo "Error:  " . $conn->error;*/

        if ($datesQuery->num_rows > 0) {
            while($d = $datesQuery->fetch_assoc()) {
                /*if($conn->query("ALTER TABLE test ADD ".$d['date']." varchar(12)")===false)
                    echo "Error:  " . $conn->error;*/
                $timeStamp=strtotime($d["date"]);
                $weaklydays=date('D',$timeStamp);
                $Array[$i++]=$weaklydays;
                echo "<th>".$d["date"]."<br>(".$Array[$i-1].")</th>";
            }
            echo "</tr>";
        }

        $qry=$conn->query("SELECT * from TimeTable where Year Like '%".$year."%'");

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
                                $Sellected[$sn++]=$sno++;
                                $Sellected[$sn++]=$row["Name"];
                                $Sellected[$sn++]=$row["Branch"];

                        while ($n<$i) {
                            if (strpos($row[$Array[$n++]],$periods)!== false){
                                if($_POST[$row["ID"].$k++]){
                                    $Sellected[$sn++]=$time;
                                    echo "<td>".$time."</td>";
                                }
                                else{
                                    $Sellected[$sn++]="";
                                    echo "<td></td>";
                                }
                            }
                            else
                                echo "<td></td>";
                        }
                        echo "</tr>";
                    }
                }
            }
            echo "</table>";
        }
	?>
</body>
</html>