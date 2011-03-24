<?php
include 'FikaDate.php';

if ($_POST) {
	var_dump($_POST); die();
}

if ($_POST && $_POST['install']) {
	$personList = array(
		array('name' => 'Adam Aaronsson', 'acronym' => 'aaa'), 
		array('name' => 'Boris Bertilsson', 'acronym' => 'bbe'),
		array('name' => 'Ceasar Carlsson', 'acronym' => 'cca'),
		array('name' => 'Dennis Davidsson', 'acronym' => 'dda'),
		array('name' => 'Einar Eriksson', 'acronym' => 'eer'),
		array('name' => 'Felix Fredriksson', 'acronym' => 'ffr'),
		array('name' => 'G&ouml;ran Gottfriedsson', 'acronym' => 'ggo'),
		array('name' => 'Hans Henriksson', 'acronym' => 'hhe'),
		array('name' => 'Ibsen Ingolfsson', 'acronym' => 'iin'),
		array('name' => 'Jakob Jansson', 'acronym' => 'jja'),
		array('name' => 'Krister K&aring;resson', 'acronym' => 'kka'),
		array('name' => 'Ludwig Larsson', 'acronym' => 'lla')
	);
	file_put_contents('fikalist.dat', serialize($personList));

	$state = array('startWeek' => 49, 'startYear' => 2010, 'startPerson' => 'aaa');
	file_put_contents('state.dat', serialize($state));

	$tempMods = array(
		/*array('acronym' => 'cca', 'where' => 'up', 'when' => new FikaDate(2011, 11)),
		array('acronym' => 'cca', 'where' => 'up', 'when' => new FikaDate(2011, 10)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 10)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 11)),
		array('acronym' => 'aaa', 'where' => 'down', 'when' => new FikaDate(2011, 12))*/
	);
	file_put_contents('tempmods.dat', serialize($tempMods));
}

include 'install.tpl.php';
?>
