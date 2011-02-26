<?php
	if (isset($_GET) && isset($_GET['acronym']) && $_GET['acronym'] != '') {
		$tempMods = unserialize(file_get_contents('tempmods.dat'));

		$tempMods[] = array('acronym' => $_GET['acronym'], 'where' => 'up');

		file_put_contents('tempmods.dat', serialize($tempMods));
	}

	header('HTTP/1.0 303 See Other');
	header('Location: index.php');
?>
