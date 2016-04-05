<?php  if (session_id() == '' ) {session_start();}; 
	date_default_timezone_set('America/Los_Angeles');
   if(substr($_SERVER['DOCUMENT_ROOT'],0,15)=='C:/xampp/htdocs')
	     {$documentroot= 'C:/xampp/htdocs/d-jundt';} 
	else { $documentroot=$_SERVER['DOCUMENT_ROOT'];} ;   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>IoT example - d-jundt.org</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="ShortTitle" content="IoT doorlog - d-jundt.org">
		<meta name="description" content="This log captures the activity of the garage door. This was done as part of the Capstone Project for a Coursera Class."/>
		<meta name="Keywords" content="internet of things, capstone, coursera, activity log"/>
		<link rel="stylesheet" type="text/css" href="/includes/basestyles.css" />
	</head>
	<body>
	<div id="container">
	 	<?php require ($documentroot.'/Login/db_connect.php');  //-- connects to db and check permissions ?> 
	    <?php require ($documentroot.'/includes/banner_topnav.php'); ?>       
        <div id="contentArea">
				<div id="contentwide">
					<h1>Select which record to view</h1>
 
                    <p>This page would not be available in production mode. The customer would sign in to access his or her page rather than doing it via this page.</p>
                     <p>Pick one serial number (identifying a unique controller) to see its record.</p>

                    <table border="0" cellpadding="3" cellspacing="0">
                      <tr align="center" valign="bottom">
                        <td><p ><strong><font size="2">Serial number </font></strong></p></td>
                        <td><p ><strong><font size="2">Last activity logged </font></strong></p></td>
                      </tr>             

                    <?php
					$table='doorlog';
					
					$get_all = mysql_query('SELECT serial, max(datetime) 
                                from '.$table.' GROUP BY serial ORDER BY serial' );
										  
						while($row = mysql_fetch_row($get_all)) {
							print "  <tr valign=\"top\" bgcolor=\"#ffffff\">
							<td nowrap><div align=\"left\"><a href=\"doorlog.php?serial=$row[0]\">
									<font size=\"2\">$row[0]</font></div></td>
							<td nowrap><font size=\"2\">$row[1]</font></td>						
						  </tr>\n";
					  	}
                    ?>
                   <p>&nbsp;</p>
				</div> <!--contentwide -->
			</div> <!--contentArea -->
	   	<div style="clear:both;"></div>
 		<?php require ($documentroot.'/includes/footer.php'); ?>
  	</div> <!--container -->
   	</body>
</html>