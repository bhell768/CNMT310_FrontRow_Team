<?php
class Page{

	private $_pageTitle;
	private $_topHtml = "";
	private $_extraHeadItems = "";
	private $_bottomHtml = "";
	
	function __construct($enterTitle = "Default Title")
	{
		$this->_pageTitle = $enterTitle;
	}

	function setTop()
	{
		$this->_topHtml = "";
		$this->_topHtml .='<!DOCTYPE html><html>';
		$this->_topHtml .='<head><title>' . $this->_pageTitle . '</title>';
		$this->_topHtml .= $this->_extraHeadItems;
		$this->_topHtml .='</head>';
		$this->_topHtml .='<body>';
	}
	
	function addHeadItem($newItem = "")
	{
		$this->_extraHeadItems .= $newItem;
	}
	
	function setBottom()
	{
		$this->_bottomHtml = "";
		$this->_bottomHtml .= '</body>';
		$this->_bottomHtml .= '</html>';
	}
	
	//##### GET RETURN FUNCTIONS #####//	
	function getTop()
	{
		return $this->_topHtml;
	}
	function getBottom()
	{
		return $this->_bottomHtml;
	}
	
}  //end Page Class

?>