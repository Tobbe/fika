<?php
	session_cache_limiter('nocache');
	include 'FikaDate.php';

	$currentYear = date('Y');
	$weeksInYear = date('W', mktime(0, 0, 0, 12, 28, $currentYear));
	$currentWeek = date('W');

	$personList = unserialize(file_get_contents('fikalist.dat'));
	$state = unserialize(file_get_contents('state.dat'));
	//$state = array('startWeek' => 49, 'startYear' => 2010, 'startPerson' => 'aaa');
	//file_put_contents('state.dat', serialize($state));
	$tempMods = unserialize(file_get_contents('tempmods.dat'));
	/*$tempMods = array(
		array('acronym' => 'cca', 'where' => 'up', 'when' => new FikaDate(2011, 11)),
		array('acronym' => 'cca', 'where' => 'up', 'when' => new FikaDate(2011, 10)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 10)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 11)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 12))
	);*/
	//file_put_contents('tempmods.dat', serialize($tempMods));

	$personIndex = 0;
	while ($personList[$personIndex]['acronym'] != $state['startPerson']) {
		$personIndex++;
	}

	$timeStartWeek = strtotime($state['startYear'] . '0104 + ' . ($state['startWeek'] - 1) . ' weeks');

	if ((time() - $timeStartWeek) > (count($personList) - 1)*7*24*60*60) {
	    $newStartWeek = $currentWeek * 1;
		$state['startWeek'] = $newStartWeek;
		$state['startYear'] = $currentYear;
		$personIndex += count($personList) - 1;
		$personIndex %= count($personList);
		$state['startPerson'] = $personList[$personIndex]['acronym'];

		file_put_contents('state.dat', serialize($state));
	}

	$reorderedPersonList = array();
	for ($i = 0; $i < count($personList); ++$i) {
		$reorderedPersonList[] = $personList[$personIndex];

		$personIndex++;

		if ($personIndex >= count($personList)) {
			$personIndex = 0;
		}
	}

	$weekYear = $state['startYear'];
	$weeksInYear = date('W', mktime(0, 0, 0, 12, 28, $weekYear));

	$weeks = array($state['startWeek']);
	for ($i = 0; $i < count($personList) - 1; ++$i) {
		$week = $weeks[count($weeks) - 1] + 1;

		if ($week > $weeksInYear) {
			$week -= $weeksInYear;
			$weekYear++;
			$weeksInYear = date('W', mktime(0, 0, 0, 12, 28, $weekYear));
		}

		$weeks[] = $week;
	}

	$weekYear = $state['startYear'];
	$weekPersonMapping = array();

	foreach ($weeks as $index => $week) {
		if ($week == 1) {
			$weekYear++;
		}

		$date = new FikaDate($weekYear, $week);
		$person = $reorderedPersonList[$index];

		$weekPersonMapping[] = array(
			'date' => $date,
			'year' => $weekYear,
			'week' => $week, 
			'person' => $person
		);
	}

	foreach ($tempMods as $mod) {
		$index = 0;
		$tmp = null;
		while (!$tmp && $index < count($weekPersonMapping)) {
			if ($weekPersonMapping[$index]['person']['acronym'] == $mod['acronym'] && $weekPersonMapping[$index]['date'] == $mod['when']) {
				$tmp = $weekPersonMapping[$index]['person'];
			} else {
				$index++;
			}
		}

		if ($tmp) {
			if ($mod['where'] == 'up') {
				if ($index == 0) {
					$len = count($weekPersonMapping);
					for ($i = 0; $i < $len - 1; $i++) {
						$weekPersonMapping[$i]['person'] = $weekPersonMapping[$i + 1]['person'];
					}
					$weekPersonMapping[$len - 1]['person'] = $tmp;
				} else {
					$weekPersonMapping[$index]['person'] = $weekPersonMapping[$index - 1]['person'];
					$weekPersonMapping[$index - 1]['person'] = $tmp;
				}
			} else {
				$len = count($weekPersonMapping);
				if ($index == $len - 1) {
					for ($i = $len - 1; $i > 0; $i--) {
						$weekPersonMapping[$i]['person'] = $weekPersonMapping[$i - 1]['person'];
					}
					$weekPersonMapping[0]['person'] = $tmp;
				} else {
					$weekPersonMapping[$index]['person'] = $weekPersonMapping[$index + 1]['person'];
					$weekPersonMapping[$index + 1]['person'] = $tmp;
				}
			}
		}
	}

	if (isset($_GET['email'])) {
		$email = '';
		$i = 0;

		while (empty($email) && $i < count($weekPersonMapping)) {
			$datePerson = $weekPersonMapping[$i];
			$date = $datePerson['date'];
			$person = $datePerson['person'];
			$currentWeek = FikaDate::fromString('now');
			$nextWeek = FikaDate::fromString('+1 week');

			if ($date == $currentWeek && date('N') < 4) {
				$email = $person['acronym'];
			} else if ($date == $nextWeek && date('N') >= 4) {
				$email = $person['acronym'];
			}

			++$i;
		}

		if (!strpos($email, '[kanelbulle]')) {
			$email .= '[kanelbulle]softhouse.se';
		}

		echo $email;
		die();
	}

	$reorderedPersonList = array_map(function ($e) { return $e['person']; }, $weekPersonMapping);

	include 'index.tpl.php';
?>
