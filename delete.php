<?php

if (isset($_GET) && isset($_GET['acronym']) && $_GET['acronym'] != '') {
	$personList = unserialize(file_get_contents('fikalist.dat'));

	$newPersonList = array();
	foreach ($personList as $person) {
		if ($person['acronym'] != $_GET['acronym']) {
			$newPersonList[] = $person;
		}
	}

	file_put_contents('fikalist.dat', serialize($newPersonList));
}

header('HTTP/1.0 303 See Other');
header('Location: index.php');

?>
