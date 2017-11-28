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
	
	$stackBool = false;
	$stackOp = 0;
	$result = "";
	
	if (isset($_POST["stack"]) && !empty($_POST["stack"]))
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
		
		$result = $db_connection -> dbCall("select s.title,COUNT(h.id) AS SongCount
										from history h,song s,stack
										where time between date_sub(now(), interval 7 day) and now()
										AND stack.id = s.stackid
										AND s.id = h.songid
										AND stack.stackname = '" . $_POST['stack'] . "'
										group by songid
										order by SongCount desc
										limit 5;");

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
					<h2>Five Most Frequent From Stack Report</h2>
				</div>
				<div class='main-content'>";
				
	print $formPage -> getFormTop("POST","five_most_frequent.php");
	print "<h1>Stack</h1>";
	$selOption = array("","A","B","C","D","E","LR","MR","HR","NM","WI");
	print $formPage -> addSelect("stack","selStack",$selOption,$stackOp);
	print $formPage -> addSubmit('submit_stack');
	print $formPage -> getFormBottom();
	
	print "<div id='song_container'>";
	
	if($result != "")
	{
		foreach($result as $arr)
		{
			print "<div class='colors rec-0 rec-1 song'>";
			print "<div class='song_section timeData'>";
			print("<h3 class='day'>" . $arr["title"] . "</h3>");
			print "</div>";
			print "<div class='song_section songData'>";
			print("<h2 class='title'>" . $arr["SongCount"] . "</h3>");
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