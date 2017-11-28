<?php
	
	session_start();

	require_once("classes/Page.php");
	
	if(!$_SESSION['loggedIn']=="true")
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/Merged/lab3.php");
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
				$insertAlbum = "insert into album(albumname,labelid,artistid) values('{$safeAlbum}',{$labelId},{$artistId});";
				$queryAlbum = mysqli_query($link,$insertAlbum);
				$albumId = mysqli_insert_id($link);
			}
		}
		
		$searchSong = "select song.id from song,stack,album,label,artist " . 
			"where song.stackid = stack.id and song.albumid = album.id and album.labelid = label.id and album.artistid = artist.id " .
			"and title = '{$safeTitle}' and stackid = '{$stackId}' and albumid = '{$albumId}' and artistid = '{$artistId}' and labelid = '{$labelId}';";
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
		$insertHistory = "insert into history(time,songid,userid) values(now(),{$songId},{$_SESSION["userId"]});";
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
	print "<header>
				<div class='header-cover'>
					<div class='head-title'>
						<h1>WWWSP - 90FM</h1>
						<h2>DJ Hub</h2>
					</div>
					<div class='head-logo'>
						<img alt='90FM Logo' src='img/WWSP_90fm_mic.png'/>
					</div>
					<div class='head-login'>
						<h3>Welcome</h3>
						<span id='login_errors'></span>
					</div>
				</div>
				<nav>
					<!--TODO implement the navigation bar -->
					<ul>
						<li><a href='assignment3.php'>Song History and Addition</a></li>
						<li><a href='main_report_page.php'>Reporting Page</a></li>
						<li><a href=''>Test Nav</a></li>
						<li><a href=''>Test Nav</a></li>					
					</ul>
				</nav>
			</header>";
	print "<section class='main-container'>
				<div class='title-wrapper'>
					<h2>Song History and Addition</h2>
				</div>
				<div class='main-content'>";
	print $formPage -> getFormTop("POST","assignment3.php");
	print "<h1>UWSP Playlist Tracker</h1>";
	print "<div class='row'>";
	print "<div class='row-item'>Song Title: " . $formPage -> addTextInput("songTitle",$titleVal) . "</div>";
	print "<div class='row-item'>Song Artist: " . $formPage -> addTextInput("songArtist",$artistVal) . "</div>";
	print "</div>";
	print "<div class='row'>";
	print "<div class='row-item'>Album: " . $formPage -> addTextInput("album",$albumVal) . "</div>";
	print "<div class='row-item'>Label: " . $formPage -> addTextInput("label",$labelVal) . "</div>";
	print "</div>";
	print "<div class='row'>";
	print "<div class='row-item'>";
	print $formPage -> addLabel("selStack","Stack: ");
	$selOption = array("","A","B","C","D","E","LR","MR","HR","NM","WI");
	print $formPage -> addSelect("stack","selStack",$selOption,$stackOp);
	print "</div></div>";
	print $formPage->addHiddenTextInput("announcer",$_SESSION['userId']);
	print "<div class='row'>";
	print $formPage -> addSubmit("submit");
	print "</div>";
	print $formPage -> getFormBottom();
	
	print "</div>";
	print"<div id='song_container'>";
	$endingHour=0;
	if(!isset($_GET['index']))
	{
		$_SESSION["previousIndex"] = 0;
	}
	else
	{
		$_SESSION["previousIndex"]=$_GET['index'];
	}
	$formPage->printPreviousSongs($link,$_SESSION["previousIndex"]);
	print"</div>";
	print"<div class='recentNav'>";
	$previousButton = $_SESSION["previousIndex"] + 1;
	print"<a href='http://cnmtsrv2.uwsp.edu/~bbart595/Merged/assignment3.php?index=" . $previousButton . "'><button>Previous Hour</button></a>";
	$time = $time + 7200;
	if($_SESSION["previousIndex"] > 0)
	{
		print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/Merged/assignment3.php'><button>Current Hour</button></a>";
		$nextButton = $_SESSION["previousIndex"] - 1;
		if($nextButton == 0)
		{
			print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/Merged/assignment3.php'><button>Next Hour</button></a>";
		}
		else
		{	
			print"<a class='added' href='http://cnmtsrv2.uwsp.edu/~bbart595/Merged/assignment3.php?index=" . $nextButton . "'><button>Next Hour</button></a>";
		}
	}
	print"</div>";
	print"</div>";
	print"</section>";
	print"<div class='push'>
				
		  </div>";
 
	print $formPage -> getBottom();
	
?>
