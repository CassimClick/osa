<?php

// use NumberFormatter;

function  trimmed(string $str): string
{
	return str_replace(' ', '', $str);
}

function toWords($number): string
{
	$f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
	return ucwords($f->format($number)) . ' Tanzanian Shillings Only';
}

function numberToWords($num = '')
{
	$num    = (string) ((int) $num);

	if ((int) ($num) && ctype_digit($num)) {
		$words  = array();

		$num    = str_replace(array(',', ' '), '', trim($num));

		$list1  = array(
			'', 'one', 'two', 'three', 'four', 'five', 'six', 'seven',
			'eight', 'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen',
			'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
		);

		$list2  = array(
			'', 'ten', 'twenty', 'thirty', 'forty', 'fifty', 'sixty',
			'seventy', 'eighty', 'ninety', 'hundred'
		);

		$list3  = array(
			'', 'thousand', 'million', 'billion', 'trillion',
			'quadrillion', 'quintillion', 'sextillion', 'septillion',
			'octillion', 'nonillion', 'decillion', 'undecillion',
			'duodecillion', 'tredecillion', 'quattuordecillion',
			'quindecillion', 'sexdecillion', 'septendecillion',
			'octodecillion', 'novemdecillion', 'vigintillion'
		);

		$num_length = strlen($num);
		$levels = (int) (($num_length + 2) / 3);
		$max_length = $levels * 3;
		$num    = substr('00' . $num, -$max_length);
		$num_levels = str_split($num, 3);

		foreach ($num_levels as $num_part) {
			$levels--;
			$hundreds   = (int) ($num_part / 100);
			$hundreds   = ($hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ($hundreds == 1 ? '' : 's') . ' ' : '');
			$tens       = (int) ($num_part % 100);
			$singles    = '';

			if ($tens < 20) {
				$tens = ($tens ? ' ' . $list1[$tens] . ' ' : '');
			} else {
				$tens = (int) ($tens / 10);
				$tens = ' ' . $list2[$tens] . ' ';
				$singles = (int) ($num_part % 10);
				$singles = ' ' . $list1[$singles] . ' ';
			}
			$words[] = $hundreds . $tens . $singles . (($levels && (int) ($num_part)) ? ' ' . $list3[$levels] . ' ' : '');
		}
		$commas = count($words);
		if ($commas > 1) {
			$commas = $commas - 1;
		}

		$words  = implode(', ', $words);

		$words  = trim(str_replace(' ,', ',', ucwords($words)), ', ');
		if ($commas) {
			$words  = str_replace(',', ' and', $words);
		}

		return $words;
	} else if (!((int) $num)) {
		return 'Zero';
	}
	return '';
}


function numString(int $size)
{
	return random_string('numeric', $size);
}


function randomString()
{
	//The integer value here SHOULD NOT be changed
	return random_string('alnum', 42);
}

function printer($array)
{
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}


function financialYear()
{
	// date_default_timezone_set('Africa/Dar es Salaam');
	$current_month = date('m');

	if ($current_month >= 7) {
		$start_date = date('Y-07-01');
		$end_date = date('Y-06-30', strtotime('+1 year'));
	} else {
		$start_date = date('Y-07-01', strtotime('-1 year'));
		$end_date = date('Y-06-30');
	}

	return (object)[
		'startDate' => $start_date,
		'endDate' => $end_date,
	];


}
