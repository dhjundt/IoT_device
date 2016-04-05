<?php  if (session_id() == '' ) {session_start();}; 
	   if(substr($_SERVER['DOCUMENT_ROOT'],0,15)=='C:/xampp/htdocs')
	     {$documentroot= 'C:/xampp/htdocs/d-jundt';} 
	else { $documentroot=$_SERVER['DOCUMENT_ROOT'];} ;   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>Internet Of Things Capstone - d-jundt.org</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="ShortTitle" content="Internet Of Things Capstone - d-jundt.org">
		<meta name="description" content="This Coursera Specialization covers embedded systems, the Raspberry Pi Platform, and the Arduino environment for building devices that can control the physical world. In the Capstone Project, I specified, designed, built and tested a connected smart controller."/>
		<meta name="Keywords" content="Coursera, Capstone, IOT, Rasperri Pi"/>
		<link rel="stylesheet" type="text/css" href="/includes/basestyles.css" />
	</head>
	<body>
	<div id="container">
	 	<?php require ($documentroot.'/Login/db_connect.php');  //-- connects to db and check permissions ?> 
	    <?php require ($documentroot.'/includes/banner_topnav.php'); ?>       
        <div id="contentArea">
				<div id="contentleft">
					<h1>Internet Of Things Capstone</h1>
		<p>This <a href="https://www.coursera.org/specializations/iot" title="Coursera specialization IOT" target="_new">Coursera Specialization</a> covers embedded systems, the Raspberry Pi Platform, and the Arduino environment for building devices that can control the physical world.</p>
        <p>In the Capstone Project, I specified, designed, built and tested a connected smart controller to control a garage door opener. A 6 digit code can be entered on a keypad outside the house to open the door. All door activities (including failed attempts to gain access) are tracked on this webpage.</p>
					<p>The hardware controller chosen is a <a href="https://www.raspberrypi.org/" target="_new">Rasperry Pi</a> because of its relative ease of networking as compared to a microcontroller board such as the <a href="https://www.arduino.cc/" target="_new">Arduino</a>. The follwing diagram shows the newly specified device (within the blue box) and how it interfaces to existing hardware (door control in green, router and this webserver).</p>
					<p><img src="../blockDiagram.jpg" alt="Block diagram of existing door opener and IoT device" width="447" height="352" /></p>
					<p>&nbsp;</p>
				</div> 
				<!--contentleft -->
				<div id="contentright">
					<h2>See more on this project</h2>
 					<p><a href="/pdf/Week1_Specification.pdf" target="_blank">Specification document</a></p>
 					<p><a href="/pdf/IoT_capstone_wk2.pdf" target="_blank">Overall system design</a></p>
 					<p><a href="/pdf/IoT-capstone-wk3.pdf" target="_blank">Testing protocol</a></p>
  					<p><a href="/pdf/IoTDetailed_design.pdf" target="_blank">Detailed design and test</a></p>                   
                    <p><a href="http://www.d-jundt.org/IoT/doorlog_serials.php" target="_blank">Link to different logs (i.e. customers)</a></p>
				  <p><a href="/doorlog.php?serial=00000000ff8110f7?" target="_self">Recent activity example</a></p>
				</div> <!--contentright -->
		</div> <!--contentArea -->
	   	<div style="clear:both;"></div>
		<?php require ($documentroot.'/includes/footer.php'); ?>
  	</div> <!--container -->
   	</body>
</html>
