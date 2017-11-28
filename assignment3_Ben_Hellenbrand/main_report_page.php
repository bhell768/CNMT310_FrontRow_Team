<?php
	session_start();

	require_once("classes/Page.php");
	
	if(!$_SESSION['loggedIn']=="true")
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/Merged/lab3.php");
		exit();
	}
	
	if(isset($_POST['top_ten_report']))
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/Merged/top_ten_report.php");
		exit();
	}
	else if (isset($_POST['five_most_frequent']))
	{
		header("Location: http://cnmtsrv2.uwsp.edu/~bbart595/Merged/five_most_frequent.php");
		exit();
	}
	
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
					<h2>Reporting Page</h2>
				</div>
				<div class='main-content'>";
				
	print $formPage -> getFormTop("POST","main_report_page.php");
	print "<h1>Select a Report to View:</h1><br/>";
	print $formPage -> addSubmit('top_ten_report', 'Top 10 Songs Played');
	print "<br/><br/>" . $formPage -> addSubmit('five_most_frequent', 'Five Most Frequent Songs From Stack');
	print $formPage -> getFormBottom();
	print"</div>";
	print"</section>";
	print"<div class='push'>
				
		  </div>";
 
	print $formPage -> getBottom();



?>