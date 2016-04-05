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
					<h1>Recent activity for garage door controller</h1>
 
                    <p>The table shows the last 20 log records for the IoT device, a Rasperry Pi, that controls and monitors the electric garage door at my house.<br /></p>
                    <table border="0" cellpadding="3" cellspacing="0">
                      <tr align="center" valign="bottom">
                        <td width=200 align="left"><p ><strong><font size="2">Date &amp; Time </font></strong></p></td>
                        <td width=250 align="left"><p><strong><font size="2">Activity - result</font></strong></p></td>
                        <td align="left"><p><strong><font size="2">Door after activity</font></p></td>
                      </tr>             

                    <?php
					if (!isset($_GET['serial']))
					{ print "No valid device serial number was supplied.<br>";
					die();
					}
					else
					{
						$query="SELECT datetime, CONCAT(text,' - ',actionstatus), status
                                from doorlog,
								doorloglookup
								WHERE serial='".$_GET['serial']."' AND
								      doorlog.action=doorloglookup.action
								ORDER BY datetime DESC LIMIT 20";
						$get_all = mysql_query($query);
										  
						while($row = mysql_fetch_row($get_all)) {
							print "  <tr valign=\"top\" bgcolor=\"#ffffff\">
							<td nowrap><div align=\"left\"><font size=\"2\">$row[0]</font></div></td>
							<td nowrap><div align=\"left\"><font size=\"2\">$row[1]</font></div></td>
							<td nowrap><div align=\"left\"><font size=\"2\">$row[2]</font></div></td>
						  </tr>\n";
					  	}
					};
                    ?>
                    
                    </table>
                    <p>&nbsp;</p>
				</div> <!--contentwide -->
			</div> <!--contentArea -->
	   	<div style="clear:both;"></div>
 		<?php require ($documentroot.'/includes/footer.php'); ?>
  	</div> <!--container -->
   	</body>
</html>