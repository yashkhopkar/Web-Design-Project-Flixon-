<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="registerStyle.css">
</head>
<body>
  <div class="header">
  	<h2>Login</h2>
  </div>
	 
  <form method="post" action="login.php">
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Username</label>
  		<input type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>
  	<p>
  		Not yet a member? <a href="register1.php">Sign up</a>
  	</p>
  </form>
  <video id="videobcg" preload="auto" autoplay="true" loop="loop" muted="muted" volume="0">
        <source src="videoplayback.mp4" type="video/mp4">
        <source src="movie.webm" type="video/webm">
             Sorry, your browser does not support HTML5 video.
</video>
<!--Footer starts here-->
  <footer>
      </footer>
            <!--Footer ends here-->
</body>
</html>