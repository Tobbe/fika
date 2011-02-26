<?php
	if (isset($_POST) && isset($_POST['personname']) && $_POST['personname'] != '') {
		$personList = unserialize(file_get_contents('fikalist.dat'));
		$personList[] = array('name' => $_POST['personname'], 'acronym' => $_POST['acronym']);
		file_put_contents('fikalist.dat', serialize($personList));
	}

	header('HTTP/1.0 303 See Other');
	header('Location: index.php');
?>
