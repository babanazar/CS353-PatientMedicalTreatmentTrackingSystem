<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="table_css.css">
    <meta charset="UTF-8">
    <title>Doctors</title>
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
    <button type = "submit" class = "menu_but" id = "top_4" name = "doctors" >Transactions</button>
    <button type = "submit" class = "menu_but" id = "top_5" name = "set" >Settings</button>
    <button type = "submit" class = "menu_but" id = "top_6" name = "developer" >Developers</button>
</div>

<div class="container">
    <h3>Doctors</h3>
    <hr color="#00cc00" size="3px">
    <label>
        <b>Search:     </b>
        <input class = "search_in" type = "text" name="search">
    </label>
    <label>
        <b>According to:     </b>
        <select class="small_select" name="select">
            <option value="hospital">hospital</option>
            <option value="name">name</option>
            <option value="experience">experience</option>
            <option value="specialization">specialization</option>
        </select>
        <button type="submit" class = "search_but">Choose</button>
    </label>
    <hr color="#00cc00" size="3px">
    <table>
        <tr>
            <th>Hospital</th>
            <th>Name</th>
            <th>Experience</th>
            <th>Specialization</th>
            <th>Select</th>
        </tr>

        <?php
        $servername = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "myDB";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT Hospital.name, Doctor.name, experience, specialization FROM Doctor join Hospital using (hos_id)";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["Hospital.name"]. "</td><td>" . $row["Doctor.name"]. "</td><td>" . $row["experience"]. "</td><td>" . $row["specialization"]. "<td><button type=\"submit\" class = \"select_but\">View profile</button></td></tr>" ;
            }
        } else {
            echo "0 results";
        }

        mysqli_close($conn);
        ?>

    </table>
</div>
</body>
</html>
