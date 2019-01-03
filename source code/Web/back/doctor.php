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
		$query2 = "SELECT hos_id FROM hospital where name = ?";
		$hos_id = 0;
		if ($stmt2 = $con->prepare($query2)) {
			if ($stmt2 && $stmt2->bind_param('s', $_POST["hospital"]) && $stmt2 -> execute() && $stmt2 -> store_result() && $stmt2 -> bind_result($cur_id)) {
				while ($stmt2 -> fetch()) {
					if ($hos_id == 0) {
						$hos_id = $cur_id;
						break;
					}
				}

			}
		}
		$query3 = "INSERT INTO doctor (username, specialization, experience, education, work_time_begin, work_time_end, hos_id) VALUES (?,?,?,?,?,?,?)";
		if ($stmt3 = $con->prepare($query3)) {
			$specialization = $_POST["specialization"];
			$experience = $_POST["experience"];
			$education = $_POST["education"];
			$time_begin = $_POST["time_begin"];
			$time_end = $_POST["time_end"];
			$stmt3->bind_param("ssisssi", $username, $specialization, $experience, $education, $time_begin, $time_end, $hos_id);
			$stmt3->execute();
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
    <button type = "submit" class = "top_but" id = "right_2" name = "switch_doc">Patient</button>
    <button type = "submit" class = "top_but" id = "right_3" name = "switch_pharm">Pharmacist</button>
</div>

<div class = "menu">
    <button type = "submit" class = "menu_but" id = "top_1" name = "profile" >Profile</button>
    <button type = "submit" class = "menu_but" id = "top_2" name = "app" >Appointments</button>
    <button type = "submit" class = "menu_but" id = "top_3" name = "hospitals" >Hospitals</button>
    <button type = "submit" class = "menu_but" id = "top_4" name = "set" >Settings</button>
    <button type = "submit" class = "menu_but" id = "top_5" name = "developer" >Developers</button>
</div>

<div class="container">
    <img src="img_avatar2.jpg" alt="Avatar" class="avatar"><br>
    <div class="info">
	<?php
		/*$query4 = "SELECT phone, name, birthday, gender, add_id FROM user where username = ?";
		if ($stmt4 = $con->prepare($query4)) {
			if ($stmt4 && $stmt4->bind_param('s', $_POST["login_username"]) && $stmt4 -> execute() && $stmt4 -> store_result() && $stmt4 -> bind_result($phone, $name, $birthday, $gender, $cur_add_id)) {
				while ($stmt4 -> fetch()) {
					$add_id = 0;
					if ($add_id==0) {
						$image = $cimage;
						$phone = $cphone;
						$name = $cname;
						$password = $cpassword;
						$birthday = $cbirthday;
						$gender = $cgender;
						$add_id = $cur_add_id;
						break;
					}
				}
			}
		}
		
		/*$query5 = "SELECT * FROM address where add_id = ?";
		$flag = false;
		if ($stmt5 = $con->prepare($query5)) {
			if ($stmt5&& $stmt5->bind_param('s', $add_id) && $stmt5 -> execute() && $stmt5 -> store_result()) {
				$stmt5 -> bind_result($country, $city, $street, $apt_name, $apt_no);
				while ($stmt5 -> fetch()) {
					if (!$flag) {
						$flag = true;
						break;
					}
				}
			}
		}
		
		$query6 = "SELECT * FROM doctor where username = ?";
		$flag = false;
		if ($stmt6 = $con->prepare($query6)) {
			if ($stmt6 && $stmt6->bind_param('s', $_POST["login_username"]) && $stmt6 -> execute() && $stmt6 -> store_result()) {
				$stmt6 -> bind_result($username, $specialization, $experience, $education, $time_begin, $time_end, $hos_id);
				while ($stmt6 -> fetch()) {
					if (!$flag) {
						$flag = true;
						break;
					}
				}
			}
		}*/
		
	?>
	<label><b>Name:     <?php echo $name;?></b></label><br></div>
    <div class="info"> <label><b>Address:     <?php echo '  '.$country.',  '.$city.',  '.$street.',  '.$apt_name.' / '.$apt_no;?></b></label><br></div>
    <div class="info">
        <label><b>Date of birth:     <?php echo '  '.$birthday;?></b></label><br></div>
    <div class="info">
        <label><b>Gender:     <?php echo '  '.$gender;?></b></label><br></div>
    <div class="info">
        <label><b>Phone:     <?php echo '  '.$phone;?></b></label><br></div>
    <div class="info">
        <label><b>Education:     <?php echo '  '.$education;?></b></label><br></div>
    <div class="info">
        <label><b>Specialization:     <?php echo '  '.$specialization;?></b></label><br></div>
    <div class="info">
        <label><b>Experience:     <?php echo '  '.$experience;?></b></label><br></div>
    <div class="info">
        <label><b>Work time start:     <?php echo '  '.$time_begin;?></b></label><br></div>
    <div class="info">
        <label><b>Work time end:     <?php echo '  '.$time_end;?></b></label><br></div>
    <button type = "submit" class = "user_log" name = "edit">Edit profile</button>
</div>
</body>
</html>