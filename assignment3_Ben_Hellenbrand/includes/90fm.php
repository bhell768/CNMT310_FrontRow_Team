<?php
require_once("Page.php");

class WWSP extends Page{
	private $_top = "";
	private $_bottom = "";
	private $_pageTitle = "";
	
	function __construct($title = "Default") {
	$this->_pageTitle = $title;
	 parent::__construct($title . ' | WWSP-90FM');
}
	
	function setTop()
	{
		parent::setTop();
		$this->_top .= parent::getTop();
		$this->_top .='<div class = "wrapper">';
		$this->_top .='<header>';
		$this->_top .='<div class="header-cover">';
		$this->_top .='<div class="head-title"><h1>WWSP - 90FM</h1>';
		$this->_top .='<h2>DJ Hub</h2></div>';
		$this->_top .='<div class="head-logo"><img src="img/WWSP_90fm_mic.png" alt="90fm" style="width:75px;height:90px;"></div>';
		$this->_top .='<div class="head-login">';
		
		//Add Login Form
		if(!isset($_SESSION['login_user']))
		{
			$this->_top .='<h3>Login</h3>';
			$this->_top .='<form method="POST">';
			$this->_top .='<input type="text" name="uid" placeholder="Username/E-mail" required>';
			$this->_top .='<input type="password" name="pwd" placeholder="Password" required>';
			$this->_top .='<button type="submit" name="submit">Login</button>';
			$this->_top .='</form>';
		}
		else
		{
			$this->_top .= '<div id="logout"><h3>Welcome '.$_SESSION['login_user'].'!</h3><br><h4><a href="myAccount.php">My Account</a>    <a href="includes/logout.php">Log Out</a></h4></div>';
		}
		
		//List Errors
		if (isset($_SESSION['errors']))
		{
			 $this->_top .= '<span id="login_errors">' . $_SESSION['errors'] . '</span>';
		}
		
		$this->_top .='</div></div>';
		$this->_top .='<nav>';
		$this->_top .='<ul>';
		$this->_top .='<li><a href="prev.php">Previously Played</a></li>';
		$this->_top .='<li><a href="log.php">Playlist Log</a></li>';
		$this->_top .='<li><a href="reporting.php">Free-Form Reporting</a></li>';
		$this->_top .='<li><a href="edit.php">Admin Edit Page</a></li>';
		$this->_top .='</ul>';
		$this->_top .='</nav>';
		$this->_top .='</header>';
		$this->_top .='<section class="main-container">';
		$this->_top .='<div class="title-wrapper">';
		$this->_top .='<h2>' . $this->_pageTitle . '</h2>';
		$this->_top .='</div>';
		$this->_top .='<div class="main-content">';
	}
	
	function setBottom()
	{
		$this->_bottom .= '</div>';
		$this->_bottom .= '</section>';
		$this->_bottom .= '<div class="push"></div>';
		$this->_bottom .= '</div>';
		$this->_bottom .= '<footer>';
		$this->_bottom .= '<div class="footer-container">';
		$this->_bottom .= '<p>WWSP-90FM Stevens Point, Wisconsin</p><p>1101 Reserve Street (CAC 105) Stevens Point, WI 54481</p><p>Office Phone: 715-346-3755   |   Studio Phone: 715-346-2696</p>';
		$this->_bottom .= '</div>';
		$this->_bottom .= '</footer>';
		parent::setBottom();
		$this->_bottom .= parent::getBottom();
	}
	
	function getTop()
	{
		return $this->_top;
	}
	
	function getBottom()
	{
		return $this->_bottom;
	}
}
?>