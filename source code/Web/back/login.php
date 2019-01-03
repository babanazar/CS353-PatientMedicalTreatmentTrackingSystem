<?php
	include("config.php");
	session_start();
	if ($con->connect_error) {
		die("Connection failed: " . $con->connect_error);
	}
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//checking whether the user input is empty
		$username = mysqli_real_escape_string($con, $_POST['uname']);
		$password = mysqli_real_escape_string($con, $_POST['psw']);
		$query = "SELECT * FROM user WHERE username = ? and password = ?";
		
		//preparing a statement
		if ($stmt = $con->prepare($query)) {
			//binding parameters into prepared statement
			$stmt->bind_param('ss', $username, $password);
			
			//executing the query(statement)
			$stmt->execute();
			
			//getting the result
			$result = $stmt->get_result();
			
			//number of rows in result set
			$count = $result->num_rows;
			
			//if count is 1 the user entered correct data
			if ($count == 1) {
				
				//First we check if the user is doctor or not
				$doc_query = "SELECT * FROM doctor WHERE username = ?";
				if ($doc_stmt = $con->prepare($doc_query)) {
					$doc_stmt->bind_param('s', $username);
					$doc_stmt->execute();
					$doc_result = $doc_stmt->get_result();
					$doc_count = $doc_result->num_rows;
					
					//if the user is doctor we redirect to doctor profile page
					if ($doc_count == 1) {
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $password;
						
						echo '<form action="doctor.php" method="post">';
						echo '<input type="hidden" name = "cid" value="'.$username.'"></input>';
						echo '</form></td></tr>';
						
						header("location: doctor.php");
					}
				}
				
				//now we check is the user is a patient
				$pat_query = "SELECT * FROM patient WHERE username = ?";
				if ($pat_stmt = $con->prepare($pat_query)) {
					$pat_stmt->bind_param('s', $username);
					$pat_stmt->execute();
					$pat_result = $pat_stmt->get_result();
					$pat_count = $pat_result->num_rows;
					
					//if the user is patient we redirect to patient profile page
					if ($pat_count == 1) {
						$_SESSION['username'] = $username;
						$_SESSION['password'] = $password;
						header("location: patient.php");
					}
				}
			} else {
				//warning the user about the wrong data input
				$field_error = "Wrong username or password!";
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="index_css.css">
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
    <div class = "top">
        <img src="logo.png" class = "logo">
        <label id = "title"> Patient Medical Treatment Tracking System</label>
        <a href="signup_first.php"><button type = "submit" class = "top_but" id = "sign" >Sign up</button></a>
        <button type = "submit" class = "top_but" id = "log" >Log in</button>
    </div>

    <div class="container">
		<form action="" method=post>
        <label><b>Username</b></label>
        <input type="text" placeholder="Enter Username" name="uname" required></input>

        <label><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="psw" required></input>

        <button type="submit" class = "user_log">Login</button>
        <label>
            <input type="checkbox" class = "checked" name = "remember"> Remember me</input>
        </label>
		<div><?php echo $field_error?></div>
		</form>
    </div>
</body>
</html>