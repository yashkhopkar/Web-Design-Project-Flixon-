<?php # Script 8.4 - edit_user.php

// This page edits a user.
// This page is accessed through view_users.php.

$page_title = 'Edit a User';
include ('./includes/header.html');

// Check for a valid user ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	include ('./includes/footer.html'); 
	exit();
}

require_once ('../mysql_connect.php'); // Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

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
	
	if (empty($errors)) { // If everything's OK.
	
		//  Test for unique email address.
		$query = "SELECT user_id FROM users WHERE email='$e' AND user_id != $id";
		$result = mysql_query($query);
		if (mysql_num_rows($result) == 0) {

			// Make the query.
			$query = "UPDATE users SET first_name='$fn', last_name='$ln', email='$e' WHERE user_id=$id";
			$result = @mysql_query ($query); // Run the query.
			if (mysql_affected_rows() == 1) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a User</h1>
				<p>The user has been edited.</p><p><br /><br /></p>';	
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The user could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Already registered.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The email address has already been registered.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form.

// Retrieve the user's information.
$query = "SELECT first_name, last_name, email FROM users WHERE user_id=$id";		
$result = @mysql_query ($query); // Run the query.

if (mysql_num_rows($result) == 1) { // Valid user ID, show the form.

	// Get the user's information.
	$row = mysql_fetch_array ($result, MYSQL_NUM);
	
	// Create the form.
	echo '<h2>Edit a User</h2>
<form action="edit_user.php" method="post">
<p>First Name: <input type="text" name="first_name" size="15" maxlength="15" value="' . $row[0] . '" /></p>
<p>Last Name: <input type="text" name="last_name" size="15" maxlength="30" value="' . $row[1] . '" /></p>
<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="' . $row[2] . '"  /> </p>
<p><input type="submit" name="submit" value="Submit" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
</form>';

} else { // Not a valid user ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
}

mysql_close(); // Close the database connection.
		
include ('./includes/footer.html');
?>