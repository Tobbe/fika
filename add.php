<?php
	function kanelbullify($acronym) {
		return str_replace('@', '[kanelbulle]', $acronym);
	}

	if (isset($_POST) && isset($_POST['personname']) && $_POST['personname'] != '') {
		$personList = unserialize(file_get_contents('fikalist.dat'));
		$personList[] = array('name' => trim($_POST['personname']), 'acronym' => kanelbullify(trim($_POST['acronym'])));
		file_put_contents('fikalist.dat', serialize($personList));
	}

	header('HTTP/1.0 303 See Other');
	header('Location: index.php?rnd=' . time());
?>
