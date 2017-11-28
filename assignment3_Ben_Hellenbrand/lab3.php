<?php

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
		$link = mysqli_connect("cnmtsrv1.uwsp.edu","barthel_b_user","hit79jin","barthel_b");
		if(!$link){
			$debugging = "Error: Unable to connect to MySQL. " . PHP_EOL;
		//	$debugging .= "Debuggin errno: " mysqli_connect_errno() . PHP_EOL;
		//	$debugging .= "Debuggin error: " mysqli_connect_error() . PHP_EOL;
		}
		$userEntered = $_POST['username'];
		$safeUser = mysqli_real_escape_string($link,$userEntered);
		
		$psswEntered = $_POST['password'];
		$safePssw = mysqli_real_escape_string($link,$psswEntered);

		$searchUser = "select id from user where username = '{$safeUser}';";
		if($queryUser = mysqli_query($link,$searchUser)){
			$row = mysqli_fetch_assoc($queryUser);
			if(!empty($row)){
				$userId = $row['id'];
				$searchUser = "select password from user where id = {$userId};";
				if($queryUser = mysqli_query($link,$searchUser)){
					$row = mysqli_fetch_assoc($queryUser);
					if(!empty($row)){
						$storedPssw = $row['password'];
						if(password_verify($safePssw,$storedPssw)){//not sure if sanitized password will mess with hashing
							$_SESSION['loggedIn']="true";
							$_SESSION['userId']=$userId;//works better for playlist page use
						}
						else{
							$loginCase = "incorrect";//password didn't checkout
						}
					}
					else {
						$debuggin .= "User: " . $userId . " has no password.";//if here userid exists for username but no password is in entry
						$loginCase = "incorrect";
					}
				}
			}else{
				$loginCase = "incorrect";//no userid no user
			}
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
	case "incorrect"://should probably bring along username for ease of use
		print "Username or Password Incorrect<br />";
		break;
	case "nothing":
		print "No Username or Password Entered<br />";
		break;
	case "userNothing":
		print "No Username Entered<br />";
		break;
	case "pswNothing"://should probably bring along username for ease of use
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
