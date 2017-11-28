<?php
	session_start();

	require_once("classes/Page.php");
	require_once("classes/DB.class.php");
	
	$db_connection = new DB();
	
	
	
	if(!$_SESSION['loggedIn']=="true")
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/Merged/lab3.php");
		exit();
	}
	
	$result = $db_connection -> dbCall("select song.title,artist.artistname,COUNT(history.id) AS SongCount 
										from history,song,album,artist
										where time between date_sub(now(), interval 7 day) and now()
											AND song.id = history.songid
											AND song.albumid = album.id
											AND album.artistid = artist.id
										group by songid
										order by SongCount desc
										limit 10;");
	
	$formPage = new Form("UWSP Playlist");
	
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_acc.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_log.css'>");
	$formPage -> addHeadItem("<link rel='stylesheet' type='text/css' href='css/style_prev.css'>");

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
					<h2>Top Ten Songs Report</h2>
				</div>
				<div class='main-content'>";
	
	print "<div id='song_container'>";
	
	if($result != "")
	{
		print "<div class='colors rec-0 rec-1 song'>";
		print "<div class='song_section timeData'>";
		print("<h3 class='day'>Song Title:</h3>");
		print "</div>";
		print "<div class='song_section songData'>";
		print("<h2 class='title'>Song Artist:</h2>");
		print "</div>";
		print "<div class='song_section albumData'>";
		print("<h4 class='album'>Times Played:</h4>");
		print "</div>";
		print "</div>";
		foreach($result as $arr)
		{
			print "<div class='colors rec-0 rec-1 song'>";
			print "<div class='song_section timeData'>";
			print("<h3 class='day'>" . $arr["title"] . "</h3>");
			print "</div>";
			print "<div class='song_section songData'>";
			print("<h2 class='title'>" . $arr["artistname"] . "</h2>");
			print "</div>";
			print "<div class='song_section albumData'>";
			print("<h4 class='album'>" . $arr["SongCount"] . "</h4>");
			print "</div>";
			print "</div>";
		}
	}
	
	print"</div>";
	print"</div>";
	print"</section>";
	print"<div class='push'>
				
		  </div>";
 
	print $formPage -> getBottom();

?>