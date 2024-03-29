<?php

class Page
{
	private $_pageTitle = "";
	private $_headItemArray;
	private $_headItemIndex = 0;
	private $_topContent = "";
	private $_bottomContent = "";
	private $_headItems;


	function __construct($title)
	{
		$this -> _pageTitle = $title;
	}

    function addHeadItem ($headItem)
    {
		// $this -> _headItemArray[$this -> _headItemIndex] = $headItem;
		// $this -> _headItemIndex = $this -> _headItemIndex + 1;
		$this -> _headItems .= $headItem;
    }  

    function setTop()
    {
        $this -> _topContent .= "<!doctype html><html>";
        $this -> _topContent .= "<head><title>" . $this -> _pageTitle . "</title>";
      //foreach ($this -> _headItemArray as $headItem)
      //{
        $this -> _topContent .= $this -> _headItems;
      //}
		$this -> _topContent .= "</head>";
		$this -> _topContent .= "<body>";
		$this -> _topContent .= "<div id=wrapper>";
		
      
    }

    function setBottom()
    {
		$this -> _bottomContent .= "</div>";
		$this -> _bottomContent .= "<footer>
										<div class='footer-container'>
											<p>CNMT 310 Semester Project</p>
										</div>
									</footer>";
		$this -> _bottomContent .= "</body>";
		$this -> _bottomContent .= "</html>"; 
    }

    function getTop()
    {
		return $this -> _topContent;
    }

    function getBottom()
    {
		return $this -> _bottomContent;
    }
	
	function printPreviousSongs($link, $passedTime) 
	{
		$endingHour = $passedTime + 1;
		$beginningHour = $passedTime;
		$searchHistory = "select stack.stackname as stack, history.time as time, song.title as title, artist.artistname as artist, album.albumname as album, label.labelname as label from history, stack, song, artist, album, label where history.songid = song.id and album.id = song.albumid and artist.id = album.artistid and label.id = album.labelid and song.stackid = stack.id and time between date_sub(now(), interval {$endingHour} hour) and date_sub(now(),interval {$beginningHour} hour);";
		
		if($queryHistory = mysqli_query($link,$searchHistory))
		{
			while($row = mysqli_fetch_assoc($queryHistory)){
				$dateTime = explode(' ', $row['time']);
                                print"<div class='colors rec-0 rec-1 song'>";
                                        print"<div class='song_section timeData'>";
                                                print"<h3 class='day'>" . $dateTime[0] . "</h3>";
                                           	print"<h3 class='time'>" . $dateTime[1] . "</h3>";
					print"</div>";
					print"<div class='song_section songData'>";
						print"<h2 class='title'>" . $row['title'] . "</h2>";
						print"<h3 class='artist'>" . $row['artist'] . "</h3>";
					print"</div>";
					print"<div class='song_section albumData'>";
						print"<h4 class='album'>" . $row['album'] . "</h4>";
						print"<h4 class='label'>" . $row['label'] . "</h4>";
					print"</div>";
				print"</div>";
			}	
		}
		else
		{
			print "An error occured with sql connection";
			var_dump($queryHistory);
		}
	}
}

class Form extends Page
{

	private $_formTop = "";
	private $_formBottom = "";
	private $_select = "";
	

	function getFormTop($method, $action)
	{
		$this-> _formTop .= "<div id='log-css'>";
		if($method == "GET" || $method == "POST")
		{
			$this -> _formTop .= "<form method='" . $method . "' action='" . $action ."'>";
		}
		else
		{
			$this -> _formTop .= "<form method='POST' action='" . $action . "'>";
		}
		return $this -> _formTop;
	}

	function getFormBottom()
	{
		$this -> _formBottom .= "</form>";
		$this -> _formBottom .= "</div>";
		return $this -> _formBottom;
	}

	function addTextInput($name, $value="")
	{
		return "<input type='text' name='" . $name . "' value='" . $value . "'>";
	}
	
	function addHiddenTextInput ($name, $value="")
	{
		return "<input type='hidden' name='" . $name . "' value='" . $value . "'>";
	}

	function addPassword($id, $name)
	{
		return "<input type='password' id='" . $id . "' name='" . $name . "'/>";
	}

	function addLabel($forName, $labelText)
	{
		return "<label for='" . $forName . "'>" . $labelText . "</label>"; 
	}

	function addSelect($selectName, $selectId, $optionArray=5, $optionDefault)
	{
		$this -> _select .= "<select name='" . $selectName . "' id='" . $selectId . "'>";
		if(count($optionArray) <= $optionDefault)
		{
			$optionDefault = 0;
		}
		for($i = 0 ; $i < count($optionArray); $i++)
		{
			if($optionDefault == $i)
			{
				$this -> _select .= "<option selected>" . $optionArray[$i] . "</option>";
			}
			else
			{
				$this -> _select .= "<option>" . $optionArray[$i] . "</option>";
			}
		}
		$this -> _select .= "</select>";
		return $this -> _select;
	}
	
	function addSubmit($name="submit", $value="submit")
	{
		return "<input type='submit' value='" . $value . "' name='" . $name . "'>";
	}
}
	
?>
