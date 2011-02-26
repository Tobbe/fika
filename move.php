<?php

include 'FikaDate.php';

function inputIsOk() {
	if (!isset($_GET)) {
		return false;
	}

	if (!isset($_GET['acronym']) || !isset($_GET['where']) || !isset($_GET['year']) || !isset($_GET['week'])) {
		return false;
	}

	if ($_GET['acronym'] == '' || $_GET['where'] == '' || $_GET['year'] == '' || $_GET['week'] == '') {
		return false;
	}

	return true;
}

if (inputIsOk()) {
	$tempMods = unserialize(file_get_contents('tempmods.dat'));

	$tempMods[] = array(
		'acronym' => $_GET['acronym'],
		'where' => $_GET['where'],
		'when' => new FikaDate($_GET['year'], $_GET['week'])
	);

	file_put_contents('tempmods.dat', serialize($tempMods));
}

header('HTTP/1.0 303 See Other');
header('Location: index.php');

?>
