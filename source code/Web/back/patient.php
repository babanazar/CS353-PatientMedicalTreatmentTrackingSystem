<?php 
	include("config.php");
	session_start();
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$query = "INSERT INTO address (country, city, street, apartment, apartment_num) VALUES(?,?,?,?,?)";
		if ($stmt = $con->prepare($query)) {
			$country = $_POST["country"];
			$city = $_POST["city"];
			$street = $_POST["street"];
			$apt_name = $_POST["apt_name"];
			$apt_no = $_POST["apt_no"];
			$stmt->bind_param('ssssi', $country, $city, $street, $apt_name, $apt_no);
			$stmt->execute();
			$add_id = mysqli_insert_id($con);
		}
		$query1 = "INSERT INTO user (username, image, phone, name, password, birthday, gender, add_id) VALUES(?,?,?,?,?,?,?,?)";
		if ($stmt1 = $con->prepare($query1)) {
			$username = $_POST["uname"];
			$image = "image.png";
			$phone = $_POST["phone"];
			$name = $_POST["name"];
			$password = $_POST["psw"];
			$rawdate = htmlentities($_POST["date"]);
			$birthday = date('d.m.Y', strtotime($rawdate));
			$gender = $_POST["gender"];
			$stmt1->bind_param("sssssssi", $username, $image, $phone, $name, $password, $birthday, $gender, $add_id);
			$stmt1->execute();
		}
		$query2 = "INSERT INTO patient (username) VALUES (?)";
		if ($stmt2 = $con->prepare($query2)) {
			$stmt2->bind_param("s", $username);
			$stmt1->execute();
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="patient_css.css">
    <meta charset="UTF-8">
    <title>Profile</title>
</head>
<body>
    <div class = "top">
        <img src="logo.png" class = "logo">
        <label id = "title"> Patient Medical Treatment Tracking System</label>
        <a href="login.php"><button type = "submit" class = "top_but" id = "right_1" name = "logout" >Log out</button></a>
        <button type = "submit" class = "top_but" id = "right_2" name = "switch_doc">Doctor</button>
        <button type = "submit" class = "top_but" id = "right_3" name = "switch_pharm">Pharmacist</button>
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
        <img src="img_avatar2.jpg" alt="Avatar" class="avatar"><br>
        <div class="info">
            <label><b>Name:     <?php echo $name;?></b></label><br></div>
        <div class="info"> <label><b>Address:     <?php echo '  '.$country.',  '.$city.',  '.$street.',  '.$apt_name.' / '.$apt_no;?></b></label><br></div>
        <div class="info">
            <label><b>Date of birth:     <?php echo '  '.$birthday;?></b></label><br></div>
        <div class="info">
            <label><b>Gender:     <?php echo '  '.$gender;?></b></label><br></div>
        <div class="info">
            <label><b>Phone:     <?php echo '  '.$phone;?></b></label><br></div>
        <button type = "submit" class = "user_log" name = "edit">Edit profile</button>
    </div>
</body>
</html>