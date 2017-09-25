<html>
<head>
	<title>Sample Test</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <link rel="stylesheet" type="text/css" href="schedule.css"> -->
    <style type="text/css">
        #scrool{
            height: 65%;
            overflow: auto;
        }
        #btn{
            margin-top: 5%;
            text-align: center;
        }
        p{
            color: red;
        }
    </style>
    <script>
        function disable(x) 
        {
            if(x=="II")
                document.getElementById("inter").disabled=true;
            else
                document.getElementById("inter").disabled=false;
        }
        function validateForm() {
            var x = document.forms["myForm"]["year"].value;
            if (x == "0") {
                document.getElementById("year").style.borderColor="red";
                return false;
            }
            else
                document.getElementById("year").style.borderColor="#bdc0c9";
            var x = document.forms["myForm"]["sem"].value;
            if (x == "0") {
                document.getElementById("sem").style.borderColor="red";
                return false;
            }
            else
                document.getElementById("sem").style.borderColor="#bdc0c9";
            var y = document.forms["myForm"]["type"].value;
            if (y == "0") {
                document.getElementById("type").style.borderColor="red";
                return false;
            }
            else
                document.getElementById("type").style.borderColor="#bdc0c9";
        
            if(y=="I"){
                var x = document.forms["myForm"]["mid"].value;
                if (x == "") {
                    document.getElementById("mid").style.borderColor="red";
                    return false;
                }
            }
            else
                document.getElementById("mid").style.borderColor="#bdc0c9";

            var num = document.getElementById("num").value;
            if(isNaN(num)||num<1||num>15){
                document.getElementById("num").style.borderColor="red";
                alert("Please enter valid input");
                return false;
            }  
            else
                document.getElementById("num").style.borderColor="#bdc0c9";  
        }
        function validateCheckbox(){

            var data = document.getElementById('tablecount').innerHTML;
            for(var i=1;i<data;i++){
                if(document.getElementById("check"+i).checked == true){
                    return true;
                }
            }
            alert("Please select any one of Checkbox");
            return false;
        }
    </script>
</head>
<body>

<h2><center>SCHEDULE</center></h2>
    <div class="col-md-1"></div>
    <div class="container col-md-4">
        <form action="dates.php" name="myForm" onsubmit="return validateForm()" method="post">
            <div class="form-group">
                <label for="year">Year:</label>
                <select class="form-control" id="year" name="year">
                    <option value="0">Select</option>
                    <option value="I">1st year</option>
                    <option value="II">2nd year</option>
                    <option value="III">3rd year</option>
                    <option value="IV">4th year</option>
                </select>
                <p id="1"></p>
            </div>  
            <div class="form-group">
                <label for="sem">Semister:</label>
                <select class="form-control" id="sem" name="sem">
                        <option value="0">Select</option>
                        <option value="I">1st sem</option>
                        <option value="II">2nd sem</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type">Exam Type :</label>
                <select class="form-control" id="type" onclick="disable(this.value)" name="type">
                    <option value="0">Select</option>
                    <option value="I">internal</option>
                    <option value="II">semister</option>
                </select>
            </div>
            <div class="form-group">
                <label for="inter">Internal :</label>
                <select class="form-control" id="mid" name="mid">
                    <option value="">Select</option>
                    <option value="I">1st mid</option>
                    <option value="II">2nd mid</option>
                </select>
            </div>
            <div class="form-group">
                <label for="time">Exam Time :</label>
                <input type="time" class="form-control" name="time" required></td>
            </div>
            <div class="form-group">
                <label for="days">Enter number of days :</label>
                <input type="number" class="form-control" id="num" name="days" required>
            </div>
            <div id="btn">
                <input type="submit" class="btn btn-info" name="submit" id="button"/>
            </div>
        </form>
    </div>
    <div class="col-md-2">
        
    </div>
    
        <div class="container col-md-4">
            <div id="scrool">
            <form action="Test.php" name="delete" method="post">
            <table class="table table-hover">
                <?php
                error_reporting(0);
                $tabledbconn = new mysqli('localhost', 'root','','createdtablesdb');
                $detailsdbconn = new mysqli('localhost', 'root','','datesanddetailsdb');
                $qry=$tabledbconn->query("SHOW TABLES FROM createdtablesdb");
                $i=1;
                $createdtablescount=1;
                if($qry->num_rows>0){
                    while($row=mysqli_fetch_row($qry)){
                      echo "<tr><td><input type='checkbox' id=check".$createdtablescount." name=".$createdtablescount." value=".$row[0]."> ".$row[0]." <input type='hidden' name='tableName$createdtablescount' value=".$row[0]."></td>";
                       
                        $dqry=$detailsdbconn->query("SELECT * FROM dates".$row[0]);
                        
                        if($dqry->num_rows>0){
                            echo "<td><select class='form-control' name='dropDownList$createdtablescount'>";
                            echo"<option value='all'>Table</option>";
                            $day=1;
                            while($d=$dqry->fetch_assoc()){
                                echo"<option value='day$day'>".$d['dates']."</option>";
                                $day++;
                            }
                            echo"</select></td>";
                        }
                        echo "<td><input type='submit' class='btn btn-default' name='view' value='view$createdtablescount'></td></tr>";
                        $createdtablescount++;
                    }
                    echo "</table></div><div id='btn'><input type='submit' onclick='return validateCheckbox()' class='btn btn-info' name='datele' value='Delete'></div>";
                }
                else 
                    echo "<tr><td>NO TABLES</td></tr></table>";?>
            
        </form>
        <?php echo "<p id=\"tablecount\"> $createdtablescount  </p>  "; ?>
        </div>
        <div class="col-md-1"></div>
    </body>
</html>  