<?php # Script 8.2 - view_users.php 
# (3rd version after Scripts 7.4 & 7.6)

// This script retrieves all the records from the users table.
// This new version links to edit and delete pages.

$page_title = 'View the Current Users';
include ('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Registered Users</h1>';

require_once ('../mysql_connect.php'); // Connect to the db.
		
// Make the query.
$query = "SELECT last_name, first_name, DATE_FORMAT(registration_date, '%M %d, %Y') AS dr, user_id FROM users ORDER BY registration_date ASC";		
$result = mysql_query ($query); // Run the query.
$num = mysql_num_rows($result);

if ($num > 0) { // If it ran OK, display the records.

	echo "<p>There are currently $num registered users.</p>\n";

	// Table header.
	echo '<table align="center" cellspacing="0" cellpadding="5">
	<tr>
		<td align="left"><b>Edit</b></td>
		<td align="left"><b>Delete</b></td>
		<td align="left"><b>Last Name</b></td>
		<td align="left"><b>First Name</b></td>
		<td align="left"><b>Date Registered</b></td>
	</tr>
';
	
	// Fetch and print all the records.
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		echo '<tr>
			<td align="left"><a href="edit_user.php?id=' . $row['user_id'] . '">Edit</a></td>
			<td align="left"><a href="delete_user.php?id=' . $row['user_id'] . '">Delete</a></td>
			<td align="left">' . $row['last_name'] . '</td>
			<td align="left">' . $row['first_name'] . '</td>
			<td align="left">' . $row['dr'] . '</td>
		</tr>
		';
	}

	echo '</table>';
	
	mysql_free_result ($result); // Free up the resources.	

} else { // If it did not run OK.
	echo '<p class="error">There are currently no registered users.</p>';
}

mysql_close(); // Close the database connection.

include ('./includes/footer.html'); // Include the HTML footer.
?>