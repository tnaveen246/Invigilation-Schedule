<html>
<head>
	<title>Sample Test</title>
    <link rel="stylesheet" type="text/css" href="schedule.css">
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
                alert("please select year!");
                return false;
            } 
            var x = document.forms["myForm"]["sem"].value;
            if (x == "0") {
                alert("please select Semister!");
                return false;
            }
            var y = document.forms["myForm"]["type"].value;
            if (y == "0") {
                alert("please select type of exam!");
                return false;
            }
            if(y=="I"){
                var x = document.forms["myForm"]["mid"].value;
                if (x == "") {
                    alert("please select mid!");
                    return false;
                }
            }     
        }
    </script>
</head>
<body>
<h2><center>SHEDULE</center></h2>
        <form action="dates.php" name="myForm" onsubmit="return validateForm()" method="post">
            <table>
                <tr>
                    <td>year:</td>
                    <td><select name="year">
                            <option value="0">Select</option>
                            <option value="I">1st year</option>
                            <option value="II">2nd year</option>
                            <option value="III">3rd year</option>
                            <option value="IV">4th year</option>
                        </select></td>
                </tr>
                <tr>   
                    <td>sem:</td>
                    <td><select name="sem">
                            <option value="0">Select</option>
                            <option value="I">1st sem</option>
                            <option value="II">2nd sem</option>
                        </select></td>
                </tr>
                <tr>
                    <td>exam type:</td>
                    <td><select onclick="disable(this.value)" name="type">
                            <option value="0">Select</option>
                            <option value="I">internal</option>
                            <option value="II">semister</option>
                        </select></td>
                </tr>
                <tr>
                    <td>internal:</td>
                    <td><select id="inter" name="mid">
                            <option value="">Select</option>
                            <option value="I">1st mid</option>
                            <option value="II">2nd mid</option>
                        </select></td>
                </tr>
                <tr>
                    <td>exam time:</td>
                    <td><input type="time" name="time" required></td>
                </tr>
                <tr>
                    <td>number of days:</td>
                    <td><input type="number" name="days" required></td>
                </tr>
                <tr> <td colspan="2" id="submit"><input type="submit" name="submit" id="button"/></td></tr>
            </table>
        </form>
    </body>
</html>  