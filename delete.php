<?php

if (isset($_GET) && isset($_GET['acronym']) && $_GET['acronym'] != '') {
	$personList = unserialize(file_get_contents('fikalist.dat'));
	$state = unserialize(file_get_contents('state.dat'));

	$updateStartPerson = false;
	$newPersonList = array();
	foreach ($personList as $person) {
		if ($updateStartPerson) {
			$state['startPerson'] = $person['acronym'];
			$updateStartPerson = false;
			file_put_contents('state.dat', serialize($state));
		}

		if ($person['acronym'] != $_GET['acronym']) {
			$newPersonList[] = $person;
		} else {
			if ($person['acronym'] == $state['startPerson']) {
				$updateStartPerson = true;
			}
		}
	}

	if ($updateStartPerson) {
		$state['startPerson'] = $personList[0]['acronym'];
		$updateStartPerson = false;
		file_put_contents('state.dat', serialize($state));
	}

	file_put_contents('fikalist.dat', serialize($newPersonList));
}

header('HTTP/1.0 303 See Other');
header('Location: index.php');

?>
