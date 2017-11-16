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
	
	function printPreviousSongs($passedTime) {
		$endingHour = $passedTime;

		$beginningHour=$endingHour-3600;
		
		$fh = fopen("/home/bbart595/webfiles/song_history.txt","r");

		if (!is_resource($fh)) 
		{
			print"<span>Unable to access song database</span>";
		}
		else 
		{
			$foundFirstEntry = false;
			while ($line = fgets($fh)) 
			{
				print"<div class='colors rec-0 rec-1 song'>";
				$songDetails = explode("|",$line);
				if($songDetails[5] >= $beginningHour && $songDetails[5] <= $endingHour)
				{
					$foundFirstEntry=true;
					print"<tr>";
					unset($songDetails[5]);
					unset($songDetails[6]);
					print"<div class='song_section timeData'>";
					foreach($songDetails as $currentEntry)
					{
						print"<td style='border: 1px solid black;'>" . $currentEntry . "</td>";
					}
					print"</div>";
					print"</tr>";
				}
				else if($foundFirstEntry==true)
				{
					break;
				}
				print"</div>";
			}//end while
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
	
	function addSubmit($name="submit")
	{
		return "<input type='submit' name='" . $name . "'>";
	}
}
	
?>
