<!DOCTYPE html>
<html>
<head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        
        table{
            font-size: 13px;
        }
        td,th{
            width :30px;height:15px;

        }
        body{
            margin-left: 5%;
            margin-right: 5%;
        }
    </style>
</head>
<body>

<?php
    $detailsdbconn = new mysqli('localhost', 'root','','datesanddetailsdb');
    $tablesdbconn = new mysqli('localhost', 'root','','createdtablesdb');

    if(isset($_POST["view"])){
        $view = $_POST["view"];
        $viewNumber=49;
        while($viewNumber){
            if(strpos($view,$viewNumber)){
                $num=$viewNumber-48;
                break;
            }
            else{
                $viewNumber++;
            }
        }

        $day=$_POST["dropDownList".$num];
        $tableName=$_POST["tableName".$num];

        $tableNumber=filter_var($tableName, FILTER_SANITIZE_NUMBER_INT);

        if($day!="all"){
            $timeqry=$detailsdbconn->query("SELECT * FROM detailstable".$tableNumber);
            $row=$timeqry->fetch_assoc();
            $time=$row["time"];

            $qry=$tablesdbconn->query("SELECT name from table".$tableNumber." where $day='$time'");
            if($qry->num_rows>0){
                while($row=$qry->fetch_assoc()){
                    echo"<br>".$row["name"];
                }
            }
        }
        else{
            $details=$detailsdbconn->query("SELECT * FROM detailstable".$tableNumber);       //Query
            while ($row = $details->fetch_assoc()) {
                $year=$row['year'];
                $sem=$row['sem'];
                $type=$row['type'];
                $mid=$row['mid'];
                $time=$row['time'];
                $btime=$row['btime'];
                $etime=$row['etime'];
            }
            echo "<center><h3>VISHNU INSTITUTE OF TECHNOLOGY - BHIMAVARAM</h3</center>";
            echo "<center><h4>".$year."-".$sem;
            if($type=='I')
                echo"   ".$mid." MID ";
            else
                echo " SEMISTER ";
            echo"INVIGILATION DUTIES</h4></center>";
            $datesQuery=$detailsdbconn->query("SELECT * FROM datestable".$tableNumber);        //Query
            $periodsount=$datesQuery->num_rows;
            echo "<div class='container-fluid'><table border='1' class='table'>";
            echo "<tr><td colspan='3' name='firstcol'>";
            echo"Exam Timing : ".$time." to ".$etime."</td><td colspan='$periodsount' name='secondcol'>Reporting Time : ".$btime."</td></tr>" ;
            echo "<tr><th>S.NO</th><th>NAME OF THE FACULTY</th><th>DEPT</th>";
            if ($datesQuery->num_rows > 0) {
                $i=1;
                while($d = $datesQuery->fetch_assoc()) {
                    $timeStamp=strtotime($d["dates"]);
                    $weaklydays=date('D',$timeStamp);
                    $Array[$i++]=$weaklydays;
                    echo "<th>".$d["dates"]."<br>(".$Array[$i-1].")</th>";
                }
                echo "</tr>";
            }
            $qry=$tablesdbconn->query("SELECT * from table".$tableNumber);
            if($qry->num_rows>0){
                while($row=$qry->fetch_assoc()){
                    echo"<tr><td>".$row["sno"]."</td>
                        <td>".$row["name"]."</td>
                        <td>".$row["branch"]."</td>";
                        $dup=1;
                        while($dup<=$periodsount){
                            echo "<td>".$row["day".$dup]."</td>";
                            $dup++;
                        }
                        echo "</tr>";
                }
                echo "</table></div>";
            }

        }
    }

    else{
        error_reporting(0);
        $qry=$tablesdbconn->query("SHOW TABLES FROM createdtablesdb");
        $numberOfTables=$qry->num_rows;
        while ($numberOfTables!=0) {
            if($_POST["".$numberOfTables]){

                $tableName=$_POST["tableName".$numberOfTables];
                $tableNumber=filter_var($tableName, FILTER_SANITIZE_NUMBER_INT);
                $detailsdbconn->query("DROP TABLE detailstable".$tableNumber);
                $detailsdbconn->query("DROP TABLE datestable".$tableNumber);
                $tablesdbconn->query("DROP TABLE ".$tableName);

                echo "<br>".$tableName." is deleted.";
                $numberOfTables--;
            }
            else
                $numberOfTables--;
        }
    }
?>
<ul class="pager">
    <li><a href="index.php">Home</a></li>
</ul>
</body>
</html>