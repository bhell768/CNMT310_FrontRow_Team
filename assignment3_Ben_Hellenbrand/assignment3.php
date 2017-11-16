<?php
	
	session_start();

	require_once("classes/Page.php");
	
	if(!$_SESSION['loggedIn']=="true")
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/lab3.php");
		exit();
	}
	
	$stack = 'stack';
	$title = 'songTitle';
	$artist = 'songArtist';
	$album = 'album';
	$label = 'label';
	$time = 'time';
	$announcer = 'username';

	$stackOp = 0;
	$titleVal = "";
	$artistVal = "";
	$albumVal = "";
	$labelVal = "";
	
	$stackBool = false;
	$songTitleBool = false;
	$songArtistBool = false;
	$albumBool = false;
	$labelBool = false;

if (isset($_POST["stack"]))
	{
	if (!empty($_POST["stack"]))
	{
		$stackBool = true;
		switch($_POST["stack"])
		{
			case "A":
				$stackOp = 1;
				break;
			case "B":
				$stackOp = 2;
				break;
			case "C":
				$stackOp = 3;
				break;
			case "D":
				$stackOp = 4;
				break;
			case "E":
				$stackOp = 5;
				break;
			case "LR":
				$stackOp = 6;
				break;
			case "MR":
				$stackOp = 7;
				break;
			case "HR":
				$stackOp = 8;
				break;
			case "NM":
				$stackOp = 9;
				break;
			case "WI":
				$stackOp = 10;
				break;
			default:
				$stackOp = 2;
		}
	}
	if (!empty($_POST["songTitle"]))
	{
		$songTitleBool = true;
		$titleVal = $_POST["songTitle"];
	}
	if (!empty($_POST["songArtist"]))
	{
		$songArtistBool = true;
		$artistVal = $_POST["songArtist"];
	}
	if (!empty($_POST["album"]))
	{
		$albumBool = true;
		$albumVal = $_POST["album"];
	}
	if (!empty($_POST["label"]))
	{
		$labelBool = true;
		$labelVal = $_POST["label"];
	}
	if ($stackBool && $songTitleBool && $songArtistBool && $albumBool && $labelBool)
	{
		//$minusHour = time()-3600;
		//$fh = fopen("/home/bbart595/webfiles/song_history.txt", 'w');
		//fwrite($fh, $_POST[$stack] . "|" . $_POST[$title] . "|" . $_POST[$artist] . "|" . $_POST[$album] . "|" . $_POST[$label] . "|" . $minusHour . "|" . $_SESSION[$announcer]);
	    $fileContent = file_get_contents ("/home/bbart595/webfiles/song_history.txt");
	    file_put_contents ("/home/bbart595/webfiles/song_history.txt", $_POST[$stack] . "|" . $_POST[$title] . "|" . $_POST[$artist] . "|" . $_POST[$album] . "|" . $_POST[$label] . "|" . time() . "|" . $_SESSION[$announcer] . "\n" . $fileContent);
		
		//$formPage->file_prepend("/home/bbart595/webfiles/song_history.txt", $_POST[$stack] . "|" . $_POST[$title] . "|" . $_POST[$artist] . "|" . $_POST[$album] . "|" . $_POST[$label] . "|" . $_POST[$time]);

		$stackOp = 0;
		$titleVal = "";
		$artistVal = "";
		$albumVal = "";
		$labelVal = "";
	}
}
	$formPage = new Form("UWSP Playlist");

	
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_acc.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_log.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_prev.css'>");
	
	//$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='assignment2.css'>");


	$formPage -> setTop();
	$formPage -> setBottom();

	print $formPage -> getTop();
	print $formPage -> getFormTop("POST","assignment3.php");
	print "<h1>UWSP Playlist Tracker</h1>\n";
	print $formPage -> addLabel("selStack","Stack: ");
	$selOption = array("","A","B","C","D","E","LR","MR","HR","NM","WI");
	print $formPage -> addSelect("stack","selStack",$selOption,$stackOp) . "<br />";
	print "Song Title: " . $formPage -> addTextInput("songTitle",$titleVal) . "<br />";
	print "Song Artist: " . $formPage -> addTextInput("songArtist",$artistVal) . "<br />";
	print "Album: " . $formPage -> addTextInput("album",$albumVal) . "<br />";
	print "Label: " . $formPage -> addTextInput("label",$labelVal) . "<br />";
	print $formPage->addHiddenTextInput("announcer",$_SESSION['username']) . "<br />";
	print $formPage -> addSubmit("submit");
	print $formPage -> getFormBottom();
	
	print"<div id='song_container'>";
	print"<table style='border-collapse: collapse; border: 1px solid black;'>";
	print"<tr>";
	print"<th style='border: 1px solid black;'>Stack</th>";
	print"<th style='border: 1px solid black;'>Title</th>";
	print"<th style='border: 1px solid black;'>Artist</th>";
	print"<th style='border: 1px solid black;'>Album</th>";
	print"<th style='border: 1px solid black;'>Label</th>";
	print"</tr>";
	$endingHour=0;
	if(!isset($_GET['current_search_time']))
	{
		$endingHour = time();
	}
	else
	{
		$endingHour=$_GET['current_search_time'];
	}
	$formPage->printPreviousSongs($endingHour);
	print"</table>";
	print"</div>";
	$time = 0;
	if(!isset($_GET['current_search_time']))
	{
		$time = time() -3600;
	}
	else
	{
		$time = $_GET['current_search_time'] -3600;
	}
	print"<div class='recentNav'>";
	print"<a href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php?current_search_time=" . $time . "'><button>Previous Hour</button></a>";
	$time = $time + 7200;
	if(!(time() <= ($time)))
	{
		print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php?current_search_time=" . time() . "'><button>Return to Current Hour</button></a>";
		print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php?current_search_time=" . $time . "'><button>Next Hour</button></a>";
	}
	print"</div>";
 
	print $formPage -> getBottom();
	
?>
