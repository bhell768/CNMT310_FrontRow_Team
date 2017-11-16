<?php

session_start();

require_once("classes/Page.php");

$errors = array();
$tempArray = array();
$credentials = array();
$i = 0;
$loginCase = "first";

if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']=="true")
{
	header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php");
	exit();
}
else
{
	$_SESSION['loggedIn'] = "false";
}
if (isset($_POST["username"]))
{
	if (!empty($_POST["username"]) && !empty($_POST["password"]))
	{
		$fh = fopen("/home/bhell768/webfiles/creds.txt","r");

		if (is_resource($fh))
		{
			while ($line = fgets($fh))
			{
				$tempArray = explode("|",$line);
				array_push($credentials,$tempArray[0],$tempArray[1]);
			}
		
			while($i < count($credentials))
			{
				if($_POST['username'] == rtrim($credentials[$i]))
				{
					$i = $i + 1;
					if($_POST['password'] == rtrim($credentials[$i]))
					{
						$_SESSION['loggedIn']="true";
						$_SESSION['username']=$_POST['username'];
						break;
					}
					else
					{
						$i = $i + 1;
					}
				}
				else
				{	
					$i = $i + 2;
				}
			}
			if($_SESSION['loggedIn']=="true")
			{	
				header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php");
				exit();
			}
			else
			{
// incorrect login
				$loginCase = "incorrect";
			}
			fclose($fh);
		}
		else
		{
// unable to access database
			$loginCase = "error";
		}	
	}
	else
	{
// nothing entered
		if(empty($_POST['username'])&& empty($_POST['password']))
		{
			$loginCase = "nothing";
		}	
		else if(empty($POST['username']))
		{
			$loginCase = "userNothing";
		}
		else
		{
			$loginCase = "pswNothing";
		}
	}
}

$loginPage = new Form("UWSP Playlist Login");

$loginPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='pretty.css'>");

$loginPage -> setTop();
$loginPage -> setBottom();

print $loginPage -> getTop();
print $loginPage -> getFormTop("POST","lab3.php");
print "<h2>Welecome!</h2>";
print $loginPage -> addLabel("username","Username:");
print $loginPage -> addTextInput("username") . "<br />";
print $loginPage -> addLabel("password","Password:");
print $loginPage -> addPassword("password","password") . "<br />"; 
switch($loginCase)
{
	case "incorrect":
		print "Username or Password Incorrect<br />";
		break;
	case "nothing":
		print "No Username or Password Entered<br />";
		break;
	case "userNothing":
		print "No Username Entered<br />";
		break;
	case "pswNothing":
		print "No Password Entered<br />";
		break;
	case "error":
		print "Could not read from database<br />";
		break;
	default:
		break;
}
print $loginPage -> addSubmit("Login");
print $loginPage -> getFormBottom();
print $loginPage -> getBottom(); 


?>
