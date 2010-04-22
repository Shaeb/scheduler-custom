<?php
require_once( "resources/bin/constants.php");

define(CALENDAR_STYLE_CONTAINER,"calendar_container");
define(CALENDAR_STYLE_HEADER,"calendar_header");
define(CALENDAR_STYLE_TABLE, "calendar_table");
define(CALENDAR_STYLE_DATE_NOT_IN_MONTH, "calendar_date_not_in_month");
define(CALENDAR_STYLE_DATE, "calendar_date");
define(CALENDAR_STYLE_LAST_COLUMN, "calendar_last_column");
define(CALENDAR_STYLE_ROW,"calendar_row");
define(CALENDAR_STYLE_BOTTOM_ROW, "calendar_bottom_row");

class Calendar extends Document {
	private $firstDayOfWeek = '';
	private $weekValues = null;
	private $format;
	private $date_info;
	private $month;
	private $mon;
	private $year;
	private $daysInMonth;
	private $dayOfMonthToDisplay; // i.e., which day are we outputing
	private $firstDay;
	private $lastDay;
	private $firstDayInfo;
	private $lastDayInfo;
	private $firstDayWeek;
	private $lastDayWeek;
	private $daysMissingInFirstWeek; // this represents how many days from the first week the month starts on belongs to the prior month
	private $daysMissingInLastWeek; // this represents how many days are left in the week when the month is over
	private $numberOfWeeks;
	private $previousMonthYear;
	private $previousMonthMonth;
	private $previousMonthFirstDay;
	private $previousMonth;
	private $daysInPreviousMonth;
	private $displayDaysForNextMonth;
	private $today;
	
	public function __construct($createForDate = null, $format = "Y-m-d") {
		$this->firstDayOfWeek = $this->setFirstDayOfWeek();
		$this->weekValues = array( "Sunday" =>1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6, "Saturday" => 7 );
		if(isset($createForDate)) {
			if( $createForDate instanceof DateTime ) {
				$this->today = $createForDate;
			}
			//if( is_string( $createForDate ) && isset($format) ) {
			if( is_string( $createForDate ) ) {
				//$this->today = DateTime::createFromFormat($format, $createForDate);
				$this->today = date_create($createForDate);
			}
		} else {
			$this->today = new DateTime();			
		}
		$this->firstDayOfWeek = "Sunday";
		$this->weekValues = array( "Sunday" =>1, "Monday" => 2, "Tuesday" => 3, "Wednesday" => 4, "Thursday" => 5, "Friday" => 6, "Saturday" => 7 );
		$this->format = "Y-m-d";
		//$this->date_info = getdate($this->today->getTimestamp());
		$this->date_info = getdate($this->today->format("U"));
		$this->month = $this->date_info["month"];
		$this->mon = $this->date_info["mon"];
		$this->year = $this->date_info["year"];
		//$this->daysInMonth = date('t', $this->today->getTimestamp());
		$this->daysInMonth = date('t', $this->today->format("U"));
		$this->dayOfMonthToDisplay = 1;
		//$this->firstDay = DateTime::createFromFormat($this->format,"{$this->year}-{$this->mon}-01");
		$this->firstDay = date_create("{$this->year}-{$this->mon}-01");
		//$this->lastDay = DateTime::createFromFormat($this->format,"{$this->year}-{$this->mon}-{$this->daysInMonth}");
		$this->lastDay = date_create("{$this->year}-{$this->mon}-{$this->daysInMonth}");
		//$this->firstDayInfo = getdate($this->firstDay->getTimestamp());
		$this->firstDayInfo = getdate($this->firstDay->format("U"));
		//$this->lastDayInfo = getdate($this->lastDay->getTimestamp());
		$this->lastDayInfo = getdate($this->lastDay->format("U"));
		$this->firstDayWeek = $this->firstDayInfo["wday"];
		$this->lastDayWeek = $this->lastDayInfo["wday"];
		$this->daysMissingInFirstWeek = $this->firstDayWeek;
		$this->daysMissingInLastWeek = 7 - ($this->lastDayWeek + 1);
		$this->numberOfWeeks = floor( ($this->daysMissingInFirstWeek + $this->daysInMonth + $this->daysMissingInLastWeek) / 7 );
		
		// okay ... build the previous month
		$this->previousMonthYear = $this->year;
		$this->previousMonthMonth = $thid->mon - 1;
		$this->previousMonthFirstDay = 1;
		// cover the january issue
		if( 1 == $thid->mon) {
			$this->previousMonthMonth = 12;
			$this->previousMonthYear--;
		}
		
		//$this->previousMonth = DateTime::createFromFormat($this->format,"{$this->previousMonthYear}-{$this->previousMonthMonth}-{$this->previousMonthFirstDay}");
		$this->previousMonth = date_create("{$this->previousMonthYear}-{$this->previousMonthMonth}-{$this->previousMonthFirstDay}");
		//$this->daysInPreviousMonth = date("t",$this->previousMonth->getTimestamp());
		$this->daysInPreviousMonth = 31;
		$this->displayDaysForNextMonth = 1;
	}
	
	public function buildCalendar(){
		$this->loadXML('<div class="'. CALENDAR_STYLE_CONTAINER . '"></div>');
		
		// create header
		$header = $this->createElement("h1", "{$this->month} {$this->year}");
		$header->setAttribute("class",CALENDAR_STYLE_HEADER);
		$this->appendChild($header);
		
		// create the table
		$table = $this->createElement("table");
		$table->setAttribute("class",CALENDAR_STYLE_TABLE);
		$table->setAttribute("cellspacing", "0");
		$table->setAttribute("cellpadding", "0");

		// create the table head
		$thead = $this->createElement("thead");
		$tr = $this->createElement("tr");
		$weekdayNames = array_keys($this->weekValues);
		foreach($weekdayNames as $weekday){
			$td = $this->createElement("td", $weekday);
			$tr->appendChild($td);
		}
		$thead->appendChild($tr);
		$table->appendChild($thead);
		
		//create the table body
		
		$tbody = $this->createElement("tbody");
		$tr = null;
		$td = null;
		$dateDiv = null;
		
		for( $i = 1; $i <= $this->numberOfWeeks; ($i++)) {
			$classToAdd = ( $i != $this->numberOfWeeks ) ? CALENDAR_STYLE_ROW : CALENDAR_STYLE_ROW . ' ' . CALENDAR_STYLE_BOTTOM_ROW;
			$tr = $this->createElement("tr");
			$tr->setAttribute("class", $classToAdd);
			
			for( $j = 0; $j < 7; ($j++) ){
				$classToAdd = (6 == $j) ? CALENDAR_STYLE_LAST_COLUMN : null;
				$displayDate = 0;
				// firstm check if we are at the beggining of the month and need to display padding values
				if($j < $this->daysMissingInFirstWeek && $i == 1 ) {
					// temp of only displaying 28
					$classToAdd = (isset($classToAdd)) ? $classToAdd . ' ' . CALENDAR_STYLE_DATE_NOT_IN_MONTH : CALENDAR_STYLE_DATE_NOT_IN_MONTH;
					$displayDate = ($this->daysInPreviousMonth - ($this->daysMissingInFirstWeek - $j) + 1); // just a random value for now ...
				} else {
					// guess not, display regular values
					if( $this->dayOfMonthToDisplay <= $this->daysInMonth ) {
						$displayDate = $this->dayOfMonthToDisplay;
						$this->dayOfMonthToDisplay++;
					} else {
						// we are at the end of the month now ...
						$classToAdd = (isset($classToAdd)) ? $classToAdd . ' ' . CALENDAR_STYLE_DATE_NOT_IN_MONTH : CALENDAR_STYLE_DATE_NOT_IN_MONTH;
						$displayDate = $this->displayDaysForNextMonth;
						$this->displayDaysForNextMonth++;
					}
				}
				$dateDiv = $this->createElement("div", $displayDate);
				$dateDiv->setAttribute("class", CALENDAR_STYLE_DATE);
				$td = $this->createElement("td");
				$td->setAttribute("class", $classToAdd);
				
				$td->appendChild($dateDiv);
				$tr->appendChild($td);
				
				$td = null;
				$dateDiv = null;				
			}
			$tbody->appendChild($tr);
			$tr = null;
		}

		$table->appendChild($tbody);
		$this->appendChild($table);
	}
	
	public function setFirstDayOfWeek($day = "Sunday"){
		$this->firstDayOfWeek = $day;
	}
	
	public function getOutput($outputAsXML = false){
		return (true == $outputAsXML) ? $this->saveXML() : $this->saveHTML();
	}
}
?>