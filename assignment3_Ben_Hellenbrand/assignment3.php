<?php

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
		$link = mysqli_connect("cnmtsrv1.uwsp.edu","barthel_b_user","hit79jin","barthel_b");
		
		if (!$link){
			echo "Error: Unable to connect to MySQL." . PHP_EOL;
			echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
			echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			exit;
		}
		//sanatise that input
		$safeArtist = mysqli_real_escape_string($link,$artistVal);	
		$safeLabel = mysqli_real_escape_string($link,$labelVal);
		$safeStack = mysqli_real_escape_string($link,$_POST["stack"]);
		$safeAlbum = mysqli_real_escape_string($link,$albumVal);
		$safeTitle = mysqli_real_escape_string($link,$titleVal);
		
		$searchArtist = "select id from artist where artistname = '{$safeArtist}';";
		if($queryArtist = mysqli_query($link,$searchArtist)){
			$row = mysqli_fetch_assoc($queryArtist);
			if(!empty($row))
			{
				$artistId = $row['id'];
			}
			else
			{
				$insertArtist = "insert into artist(artistname) values('{$safeArtist}');";
				$queryArtist = mysqli_query($link,$insertArtist);
				$artistId = mysqli_insert_id($link);
			}
		}		

		$searchLabel = "select id from label where labelname = '{$safeLabel}';";
		if($queryLabel = mysqli_query($link,$searchLabel)){
			$row = mysqli_fetch_assoc($queryLabel);
			if(!empty($row))
			{
				$labelId = $row['id'];
			}
			else
			{
				$insertLabel = "insert into label(labelname) values('{$safeLabel}');";
				$queryLabel = mysqli_query($link,$insertLabel);
				$labelId = mysqli_insert_id($link);
			}
		}		
	
		$searchStack = "select id from stack where stackname = '{$safeStack}';";
		if($queryStack = mysqli_query($link,$searchStack)){
			$row = mysqli_fetch_assoc($queryStack);
			if(!empty($row))
			{
				$stackId = $row['id'];
			}
			else
			{
				$stackId = 1;
			}
		}		

		$searchAlbum = "select id from album where albumname = '{$safeAlbum}';";
		if($queryAlbum = mysqli_query($link,$searchAlbum)){
			$row = mysqli_fetch_assoc($queryAlbum);
			if(!empty($row))
			{
				$albumId = $row['id'];
			}
			else
			{
				$insertAlbum = "insert into album(albumname) values('{$safeAlbum}');";
				$queryAlbum = mysqli_query($link,$insertAlbum);
				$albumId = mysqli_insert_id($link);
			}
		}
		
		$searchSong = "select song.id from song,stack,album,label,artist " . 
			"where song.stackid = stack.id and song.albumid = album.id and album.labelid = label.id and album.artistid = artist.id " .
			"and title = '{$safeTitle}' and stackid = '{$stackId}' and albumname = '{$albumId}' and artistname = '{$artistId}' and labelname = '{$labelId}';";
		if($querySong = mysqli_query($link,$searchSong)){
			$row = mysqli_fetch_assoc($querySong);
			if(!empty($row))
			{
				$songId = $row['id'];
			}
			else
			{
				$insertSong = "insert into song(title,stackid,albumid) values('{$safeTitle}',{$stackId},{$albumId});";
				$querySong = mysqli_query($link,$insertSong);
				$songId = mysqli_insert_id($link);
			}
		}

		$insertHistory = "insert into history(time,songid,userid) values(now(),{$songId},{$_SESSION['userId']}";
		$queryHistory = mysqli_query($link,$insertHistory);
		
		
		$stackOp = 0;
		$titleVal = "";
		$artistVal = "";
		$albumVal = "";
		$labelVal = "";
	}
}
	$link = mysqli_connect("cnmtsrv1.uwsp.edu","barthel_b_user","hit79jin","barthel_b");
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
//	print $formPage->addHiddenTextInput("announcer",$_SESSION['userId']) . "<br />";
//willl add back quick testing resolving errors
	print $formPage -> addSubmit("submit");
	print $formPage -> getFormBottom();
	
	print"<div id='song_container'>";
	print"<table style='border-collapse: collapse; border: 1px solid black;'>";
	print"<tr>";
	print"<th style='border: 1px solid black;'>Time</th>'";
	print"<th style='border: 1px solid black;'>Stack</th>";
	print"<th style='border: 1px solid black;'>Title</th>";
	print"<th style='border: 1px solid black;'>Artist</th>";
	print"<th style='border: 1px solid black;'>Album</th>";
	print"<th style='border: 1px solid black;'>Label</th>";
	print"</tr>";
	if(!isset($_GET['index']))
	{
		$_SESSION["previousIndex"] = 0;
	}
	else
	{
		$_SESSION["previousIndex"]=$_GET['index'];
	}
	$formPage->printPreviousSongs($link,$_SESSION["previousIndex"]);
	print"</table>";
	print"</div>";
	print"<div class='recentNav'>";
	$previousButton = $_SESSION["previousIndex"] + 1;
	print"<a href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php?index=" . $previousButton . "'><button>Previous Hour</button></a>";
	$time = $time + 7200;
	if($_SESSION["previousIndex"] > 0)
	{
		print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php'><button>Current Hour</button></a>";
		$nextButton = $_SESSION["previousIndex"] - 1;
		if($nextButton == 0)
		{
			print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php'><button>Next Hour</button></a>";
		}
		else
		{	
			print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/assignment3_Ben_Hellenbrand/assignment3.php?index=" . $nextButton . "'><button>Next Hour</button></a>";
		}
	}
	print"</div>";
 
	print $formPage -> getBottom();
	
?>
