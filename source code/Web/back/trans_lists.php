<?php
/**
 * Created by PhpStorm.
 * User: kasymbek.tashbaev-ug
 * Date: 03.01.2019
 * Time: 09:33
 */

	include("config.php");
	session_start();
	if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }
	if ($_SERVER["REQUEST_METHOD"] == "POST") {

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="table_css.css">
    <meta charset="UTF-8">
    <title>Transactions</title>
</head>
<body>
<div class = "top">
    <img src="logo.png" class = "logo">
    <label id = "title"> Patient Medical Treatment Tracking System</label>
    <button type = "submit" class = "top_but" id = "right_1" name = "logout" >Log out</button>
</div>

<div class = "menu">
    <button type = "submit" class = "menu_but" id = "top_1" name = "profile" >Profile</button>
    <button type = "submit" class = "menu_but" id = "top_2" name = "app" >Appointments</button>
    <button type = "submit" class = "menu_but" id = "top_3" name = "doctors" >Doctors</button>
    <button type = "submit" class = "menu_but" id = "top_4" name = "set" >Settings</button>
    <button type = "submit" class = "menu_but" id = "top_5" name = "developer" >Developers</button>
</div>

<div class="container">
    <h3>Transactions</h3>
    <hr color="#00cc00" size="3px">
    <div class="info">
        <label><b>Total transactions: <?php
                $sql = "SELECT count(trans_id) FROM TRANSACTION GROUP BY patient_username";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "count(trans_id)";
                    }
                } else {
                    echo "0";
                }
                ?>   </b></label><br></div>
    <div class="info">
        <label><b>Total money spent:  <?php
                $sql = "SELECT sum(total_price) FROM TRANSACTION GROUP BY patient_username";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "sum(total_price)";
                    }
                } else {
                    echo "0";
                }
                ?>    </b></label><br></div>
    <hr color="#00cc00" size="3px">
    <label>
        <b>Search according to date:     </b>
        <input class = "search_in" type = "date" name="search">
    </label>
    <label>
        <button type="submit" class = "search_but">Search</button>
    </label>
    <hr color="#00cc00" size="3px">
    <table>
        <tr>
            <th>Date</th>
            <th>Time</th>
            <th>Pharmacist</th>
            <th>Select</th>
        </tr>
        <?php
        $sql = "SELECT date, time, name FROM TRANSACTION join CONTAINS USING (tras_id) join USER (pharmacist_username)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" .$row["date"]. "</td><td>" .$row["time"]. "</td><td>" . $row["Pharmacist.name"]. "</td><button type=\"submit\" class = \"select_but\">View appointment</button></td></tr>";
            }
        } else {
            echo "0 results";
        }
        ?>
    </table>
</div>
</body>
</html>

