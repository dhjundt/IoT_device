<?php  if (session_id() == '' ) {session_start();}; 
		date_default_timezone_set('America/Los_Angeles');
	   if(substr($_SERVER['DOCUMENT_ROOT'],0,15)=='C:/xampp/htdocs')
	     {$documentroot= 'C:/xampp/htdocs/d-jundt';} 
	else { $documentroot=$_SERVER['DOCUMENT_ROOT'];} ;   
	/* 
	0: push-button open - success
	1: push-button open - failure
	2: keypad open - success
	3: keypad open - failure
	4: push-button close - success
	5: push-button close - failure
	6: keypad close - success
	7: keypad close - failure
	8: keypad open - wrong code
	9: keypad open - timeout
	 */
function urlsafe_b64encode($data) 
{ 
  return trim(strtr(base64_encode($data), '+/', '-_'),' '); 
} ;
function urlsafe_b64decode($data) 
{ 
  return base64_decode(strtr($data,'-_', '+/')); 
} ;
function base64url_encode($data) 
{ 
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
} ;
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
	<head>
		<title>IoT example - d-jundt.org</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	    <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW", "NOARCHIVE"/>
        <META HTTP-EQUIV="Expires" CONTENT="-1">  
	 	<?php require ($documentroot.'/Login/db_connect.php'); //-- connects to db and check permissions ?> 
		<link rel="stylesheet" type="text/css" href="/includes/basestyles.css" />
	</head> 
	<body>
	<div id="container">
        <div id="contentArea">
	<?php 
		$kb64safe='BSjocT2Ik9wEi3UP7FvFeyye8i2oqiuB_mE7dYBu6mE='; //generated 3/27/2016
		$key=urlsafe_b64decode($kb64safe); 
		if (isset($_GET['c']))
		{	$ctext=$_GET['c'];
			$ciphertext_dec = urlsafe_b64decode($ctext);
			$iv_dec = substr($ciphertext_dec, 0, 8);
    		$ciphertext_dec = substr($ciphertext_dec, 8);
 			$plaintext_dec = mcrypt_decrypt(MCRYPT_BLOWFISH, $key,
                                   $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
			parse_str($plaintext_dec);
			 if (isset($serial))
			 {	
				if (isset($action))
				{
					if ($action==99)
					{	//record token for this and return
						$token = base64url_encode(mcrypt_create_iv(16, MCRYPT_RAND));
						echo ($token);
						$ret = mysql_query("DELETE FROM tokens WHERE serial='".$serial."'");
						$ret = mysql_query("INSERT INTO tokens (serial, token) VALUES ('".$serial."','".$token."')");																 								};  //end send token
					if (is_numeric($action) && $action >=0 && $action<=9 and $action== round($action))
					{  // valid actions, still need to verify token
						if (isset($token))
						{										
							$tkn = mysql_result(mysql_query("SELECT token FROM tokens WHERE serial='".$serial."'"),0);
							if ($token==$tkn)
							{
								//match - do table insert
								$query= "INSERT INTO doorlog( serial, action) VALUES ('".$serial."',".$action.")";								
								$ret = mysql_query($query);
							} // end of correct insert
						else 
							{ print 'tokens mismatch '.$tkn.' <> '.$token."<br>";
							};
							$ret = mysql_query("DELETE FROM tokens WHERE serial='".$serial."'");
						}; // end a token was provided
					}; // end action 0..9
				};
			 };
		}; //end isset($_GET['c']


    ?>
			</div> <!--contentArea -->
	   	<div style="clear:both;"></div>
  	</div> <!--container -->
   	</body>
</html>