<?php

// Define your username and password

$username = "USERNAME_HERE";

$password = "PASSWORD_HERE";

if ($_POST['txtUsername'] != $username || $_POST['txtPassword'] != $password) {

?>

<body bgcolor="#000000">

<h1 align="center"><img src="images/alice-disney-caterpillar.gif" width="450" height="338"></h1>

<h1 align="center"><font color="#FFFFFF">Who are YOU?</font></h1>

<CENTER>



<form name="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

 <p><font color="#FFFFFF"><label for="txtUsername">Username:</label>

 <br /><input type="text" title="Enter your Username" name="txtUsername" /></p>

 <p><font color="#FFFFFF"><label for="txtpassword">Password:</label>

 <br /><input type="password" title="Enter your password" name="txtPassword" /></p>

 <p><input type="submit" name="Submit" value="Login" /></p>

</form>

<?php

}

else {

?><body>

<div>
  <ul>
  <li><a href="cala/">cala</a></li>

  <li><a href="HiMarx_Technical_Manual/">HiMarx Technical Manual</a></li>
  <li><a href="lcso/">LCSO</a></li>
  <li><a href="nwrdc/">NWRDC</a></li>
</ul>
</div>



  <?php

}

?>

</div>

