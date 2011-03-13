<?php

if (isset($_POST) && isset($_POST['newOrder']) && $_POST['newOrder'] != '') {
	$personList = unserialize($_POST['newOrder']);
	$state = unserialize(file_get_contents('state.dat'));
	$state['startPerson'] = $personList[0]['acronym'];

	file_put_contents('state.dat', serialize($state));
	file_put_contents('fikalist.dat', serialize($personList));
	file_put_contents('tempmods.dat', serialize(array()));
}

header('HTTP/1.0 303 See Other');
header('Location: index.php?rnd=' . time());

?>
