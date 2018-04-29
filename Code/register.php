<?php # Script 8.7 - register.php
// Send NOTHING to the Web browser prior to the header() line!

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	require_once ('mysql_connect.php'); // Connect to the db.
		
	$errors = array(); // Initialize error array.
	
	// Check for a first name.
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = escape_data($_POST['first_name']);
	}
	
	// Check for a last name.
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = escape_data($_POST['last_name']);
	}
	
	// Check for an email address.
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = escape_data($_POST['email']);
	}
	

	// Check for an address_line_1.
	if (empty($_POST['address_line_1'])) {
		$errors[] = 'You forgot to enter your address_line_1.';
	} else {
		$ad1 = escape_data($_POST['address_line_1']);
	}
	
	// Check for an address_line_2.
	if (empty($_POST['address_line_2'])) {
		$errors[] = 'You forgot to enter your address_line_2.';
	} else {
		$ad2 = escape_data($_POST['address_line_2']);
	}

	// Check for city
	if (empty($_POST['city'])) {
		$errors[] = 'City cannot be blank.';
	} else {
		$ct = escape_data($_POST['city']);
	}
	// Check for State
	if (empty($_POST['state'])) {
		$errors[] = 'State cannot be blank.';
	} else {
		$st = escape_data($_POST['state']);
	}
	
	// Check for Country
	if (empty($_POST['country'])) {
		$errors[] = 'Country cannot be blank.';
	} else {
		$cou = escape_data($_POST['country']);
	}

	// Check for a password and match against the confirmed password.
	if (!empty($_POST['password1'])) {
		if ($_POST['password1'] != $_POST['password2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$p = escape_data($_POST['password1']);
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Register the user in the database.
		
		// Check for previous registration.
		$query = "SELECT user_id FROM users WHERE email='$e'";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {

			// Make the query.
			$query1 = "INSERT INTO users (first_name, last_name, email, password, registration_date) VALUES ('$fn', '$ln', '$e', SHA('$p'), NOW() )";
			$result1 = @mysql_query ($query1); // Run the query.
			$lastId = @mysql_insert_id();
			
			//$query2 = "SELECT user_id FROM users WHERE email='$e'";
			//$result2 = @mysql_query($query2);
			//$value = @mysql_fetch_field($result2);
			//$strvalue = @intval($value);
			$query3 = "INSERT INTO contactdetails (user_id, address_line_1, address_line_2, city, state, country) VALUES ( '$lastId', '$ad1','$ad2', '$ct', '$st', '$cou' )";			
			$result3 = @mysql_query ($query3); // Run the query.
			
			if ($result1) { // If it ran OK.
			
				// Send an email, if desired.
				
				// Redirect the user to the thanks.php page.
				// Start defining the URL.
				$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
				
				// Check for a trailing slash.
				if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
					$url = substr ($url, 0, -1); // Chop off the slash.
				}
				
				// Add the page.
				$url .= '/thanks.php';
				
				header("Location: $url");
				exit();
				
			} else { // If it did not run OK.
				$errors[] = 'You could not be registered due to a system error. We apologize for any inconvenience.'; // Public message.
				$errors[] = mysql_error() . '<br /><br />Query: ' . $query; // Debugging message.
			}
				
		} else { // Email address is already taken.
			$errors[] = 'The email address has already been registered.';
		}
				
	} // End of if (empty($errors)) IF.

	mysql_close(); // Close the database connection.
		
} else { // Form has not been submitted.

	$errors = NULL;

} // End of the main Submit conditional.

// Begin the page now.
$page_title = 'Register';
include ('./includes/header.html');

if (!empty($errors)) { // Print any error messages.
	echo '<h1 id="mainhead">Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) { // Print each error.
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Create the form.
?>
<h2>Register</h2>
<form action="register.php" method="post">
	<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="<?php if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
	<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="<?php if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="password1" size="10" maxlength="20" /></p>
	<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>

	<p>Address Line1: <input type="text" name="address_line_1" size="30" maxlength="40" value="<?php if (isset($_POST['address_like_1'])) echo $_POST['address_line_1']; ?>"  /> </p>
	<p>Address Line2: <input type="text" name="address_line_2" size="30" maxlength="40" value="<?php if (isset($_POST['address_like_2'])) echo $_POST['address_line_2']; ?>" /> </p>
	<p>City: <input type="text" name="city" size="20" maxlength="20" value="<?php if (isset($_POST['city'])) echo $_POST['city']; ?>" /> </p>
	<p>State: <input type="text" name="state" size="20" maxlength="20" value="<?php if (isset($_POST['state'])) echo $_POST['state']; ?>" /> </p>
	<p>Country: <input type="text" name="country" size="30" maxlength="30" value="<?php if (isset($_POST['country'])) echo $_POST['country']; ?>" /> </p>
	<p><input type="submit" name="submit" value="Register" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<?php
include ('./includes/footer.html');
?>