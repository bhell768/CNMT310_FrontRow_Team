<?php

class Page
{
	private $_pageTitle = "";
	private $_headItemArray;
	private $_headItemIndex = 0;
	private $_topContent = "";
	private $_bottomContent = "";
	private $_headItems;
	private $_tableHead;

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
      
   }

   function setBottom()
   {
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

	function printPreviousSongs($passedTime)
	{
		$endingHour = $passedTime - 1;
		$beginningHour = $endingHour;

		

		$searchHistory = "select history.time, song.title, artist.artistname, album.albumname, label.labelname from history, song, artist, album, label where history.songid = song.id and album.id = song.albumid and artist.id = album.artisid and label.id = album.labelid and time between date_sub(now(), interval {$endingHour} hour) and now();";
		


		$fh = fopen("/home/bhell768/webfiles/playlist_storage.txt","r");

		if(!is_resource($fh))
		{
			print"<span>Unable to access song database</span>";
		}
		else
		{
			$foundFirstEntry = false;
			while ($line = fgets($fh))
			{
				$songDetails = explode("|",$line);
				if($songDetails[5] >= $beginningHour && $songDetails[5] <= $endingHour)
				{
					$foundFirstEntry = true;
					print"<tr>";
					unset($songDetails[5]);
					unset($songDetails[6]);
					foreach($songDetails as $currentEntry)

					{
						print"<td>" . $currentEntry . "</td>";
					}
					print "</tr>";
				}
				else if($foundFirstEntry==true)
				{
					break;
				}
			}
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
		return $this -> _formBottom;
	}

	function addTextInput($name, $value="")
	{
		return "<input type='text' name='" . $name . "' value='" . $value . "'>";
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
	
	function addSubmit($name="submit")
	{
		return "<input type='submit' name='" . $name . "'>";
	}
}
	
?>
