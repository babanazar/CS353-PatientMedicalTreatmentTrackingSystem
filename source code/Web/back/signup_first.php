
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="index_css.css">
    <meta charset="UTF-8">
    <title>Sign up first</title>
	
	<script type="text/javascript">
	function check() {
		if (!document.getElementsByName("patient")[0].checked && !document.getElementsByName("doctor")[0].checked && !document.getElementsByName("pharm")[0].checked) {
			alert("Choose at least one option!");
			return false;
		}
		return true;
	}
	</script>
</head>
<body>
<div class = "top">
    <img src="logo.png" class = "logo">
    <label id = "title"> Patient Medical Treatment Tracking System</label>
    <button type = "submit" class = "top_but" id = "sign" >Sign up</button>
    <button type = "submit" class = "top_but" id = "log" >Log in</button>
</div>

<div class = "container" id = "sign_first">
    <label><b>Create account as</b></label> <br><br>
	<form action="signup_second.php" method="post" onsubmit="return check()">
		<label>
			<input type="checkbox" class = "checked" name = "patient">Patient</input>
		</label> <br><br>
		<label>
			<input type="checkbox" class = "checked" name = "pharm">Pharmacist</input>
		</label> <br><br>
		<label>
			<input type="checkbox" class = "checked" name = "doctor">Doctor</input>
		</label> <br><br>
		<button type = "submit"  class = "user_log">Next step</button>
		
	</form>
</div>
</body>
</html>