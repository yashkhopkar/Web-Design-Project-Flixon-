<?php # Script 8.3 - delete_user.php

// This page deletes a user.
// This page is accessed through view_users.php.

$page_title = 'Delete a User';
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

	if ($_POST['sure'] == 'Yes') { // Delete them.

		// Make the query.
		$query = "DELETE FROM users WHERE user_id=$id";		
		$result = @mysql_query ($query); // Run the query.
		if (mysql_affected_rows() == 1) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Delete a User</h1>
		<p>The user has been deleted.</p><p><br /><br /></p>';	
		
		} else { // If the query did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The user could not be deleted due to a system error.</p>'; // Public message.
			echo '<p>' . mysql_error() . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
		}
	
	} else { // Wasn't sure about deleting the user.
		echo '<h1 id="mainhead">Delete a User</h1>
		<p>The user has NOT been deleted.</p><p><br /><br /></p>';	
	}

} else { // Show the form.

	// Retrieve the user's information.
	$query = "SELECT CONCAT(last_name, ', ', first_name) FROM users WHERE user_id=$id";		
	$result = @mysql_query ($query); // Run the query.
	
	if (mysql_num_rows($result) == 1) { // Valid user ID, show the form.

		// Get the user's information.
		$row = mysql_fetch_array ($result, MYSQL_NUM);
		
		// Create the form.
		echo '<h2>Delete a User</h2>
	<form action="delete_user.php" method="post">
	<h3>Name: ' . $row[0] . '</h3>
	<p>Are you sure you want to delete this user?<br />
	<input type="radio" name="sure" value="Yes" /> Yes 
	<input type="radio" name="sure" value="No" checked="checked" /> No</p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	
	} else { // Not a valid user ID.
		echo '<h1 id="mainhead">Page Error</h1>
		<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	}

} // End of the main Submit conditional.

mysql_close(); // Close the database connection.
		
include ('./includes/footer.html');
?>