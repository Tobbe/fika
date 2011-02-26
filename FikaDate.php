<?php

class FikaDate {
	public $year = -1;
	public $week = -1;

	public function __construct($y, $w) {
		$this->year = $y;
		$this->week = $w;
	}

	public static function fromString($d) {
		$d = strtotime($d);
		$year = date('Y', $d);
		$week = date('W', $d);
		return new FikaDate($year, $week);
	}

	public function isFirstWeekInYear() {
		return $this->week == 1;
	}
}

?>
