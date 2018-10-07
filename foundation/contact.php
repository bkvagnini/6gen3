<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

    <!-- Custom styles for this template -->
   <link href="css/aom.css" rel="stylesheet"> 
<title>Contact agentsofmad</title>
</head>

<body>
  
  <h1>Contact</h1>
   <p align="center"><img src="images/AOMlogo-White.jpg" alt="Agents of MAD" width="35%" height="35%" /></p>
    <!-- Columns are always 50% wide, on mobile and desktop -->
    <div class="container-fluid">
     <div class="row-fluid"> 
      <div class="col-xs-6-" style="float:left;">
        <img src="images/aom_drums.gif" width ="80%" heigth ="80%" alt=" agents of mad drummer feet">
      </div> <!-- Close col-xs-6-->
    
      <div class="span6-right"> 
          <!-- <div class="navbar" id="navbar" align="center"> -->
            <div id ="navtext">
              <br><br><br>
             <h3> 
              <a href="about.htm">About</a> <br>
              <a href="media.htm">Media</a> <br>
              <a href="tour.php">Dates</a> <br>
              <a href="comingsoon.htm">Merch</a> <br> 
              <a href="contact.php">Contact</a><br><br>
            </h3>
            
            </div> <!-- Close Navtext-->
            <!--</div>  --><!-- Close Nav Bar-->
          </div> <!-- Close col-xs-6-->
         </div> <!-- Close row -->       
      <!-- </div>  --><!-- Close container-->
      <div class="col-xs-12">
        <div id="text">
          	 <div id="feedback"> 
            <?php 
          if (!isset($_POST["firstname"])) { 
          ?>
            <h1 class="header">Feedback/Comments</h1>


            <form method="post">

          <pre>
          First Name     		<input name="firstname" size="35" maxlength="70" />
          Last Name      		<input name="lastname" size="35" maxlength="70" />
          E-mail          	<input name="email" size="45" maxlength="70" />
          Phone			<input name="phone" size="20" maxlength="70" />	
          Comments:		<textarea rows="4" cols="50" name="comments">Type your comments here...</textarea>

          			<input type="submit" value="Submit" /> <input type="reset" />
          </pre>
            </form>
              
      <p>Contact us at <a href="mailto:brian@briankvagnini.com"><strong>brian@briankvagnini.com</strong></a> , call us at <strong>850-583-0842</strong> , Follow us on <strong><a href="https://www.facebook.com/agentsofmad">Facebook</a></strong>, or follow us on twitter <strong><a href="http://twitter.com/agentsofmad">twitter.com/agentsofmad</a></strong>...</p>
      <p><strong>Debut Album "Get MAD" EP is OUT!!</strong><br /> You can find it at <a href="http://agentsofmad.bandcamp.com">agentsofmad.bandcamp.com</a><br />Thanks for your patience.
      </p>
    </div> <!-- Close text-->
  </div> <!-- Close col-xs-12-->
  </div> <!-- Close container-->
     
      <?php
      } else {
      	$firstname = $_POST["firstname"];
      	$lastname = $_POST["lastname"];
      	$email = $_POST["email"];
      	$phone = $_POST["phone"];
      	$comments = $_POST["comments"];

      	$subject = "Feedback or Comment on agentsofmad.com by $firstname $lastname";
	
      	$message = "Feedback or Comment
	
      First name:         	$firstname
      Last name:         		$lastname
      E-Mail:            		$email
      Phone:					$phone
      Comments:				$comments";

      	$mailresult = mail("brian@briankvagnini.com", $subject, $message, "From: " . $email);
      	if ($mailresult){
      		echo "<h1 class=\"header\">Feedback Received for $firstname $lastname </h1>
      <pre>$message</pre>";
      	} else {
      		echo "<h2>Error! Please go <a href=\"javascript:history.back()\">back</a> and fill in the form again.</h2>";
      	}
      }
      ?>
      </div><!-- feedback form end-->	
      
      
      <!-- Bootstrap core JavaScript
      ================================================== -->
      <!-- Placed at the end of the document so the pages load faster -->
      <script src="js/jquery.js"></script>
      <script src="js/bootstrap.min.js"></script>
      <script src="js/holder.js"></script>
      <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
          <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
