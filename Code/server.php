<?php
session_start();
//secure_session_start();
// initializing variables
$user_id = "";
$firstname = "";
$lastname = "";
$username = "";
$email    = "";
$errors = array(); 


// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'flixon');
//require_once ('mysql_connect.php');
// REGISTER USER
if (isset($_POST['reg_user'])) {
//require_once ('mysql_connect.php');
  // receive all input values from the form
    $firstname = mysqli_real_escape_string($db, $_POST['firstname']);
	 $lastname = mysqli_real_escape_string($db, $_POST['lastname']);
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
  $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($username)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($password_1)) { array_push($errors, "Password is required"); }
  if ($password_1 != $password_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['username'] === $username) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $_SESSION['username'] = $username;
   //Save some other things you might need like login key.
    //$_SESSION['login_key'] = user_login_key;
  	$password = md5($password_1);//encrypt the password before saving in the database
    $url .= '/ulman/08/FlixonProject/Movie.php';
  	$query = "INSERT INTO users (user_id,first_name,last_name,username, email, password) 
  			  VALUES('$user_id','$firstname','$lastname','$username', '$email', '$password')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $username;
  	$_SESSION['success'] = "You are now logged in";
  	header("location: $url");
  }
  
}

// LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($db, $_POST['username']);
  $password = mysqli_real_escape_string($db, $_POST['password']);

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	$password = md5($password);
  	$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  	$results = mysqli_query($db, $query);
	$url .= '/ulman/08/FlixonProject/Movie.php';
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header("location: $url");
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}

?>