<?php
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
				$weekPersonMapping[$index]['person'] = $weekPersonMapping[$index - 1]['person'];
				$weekPersonMapping[$index - 1]['person'] = $tmp;
			} else {
				$weekPersonMapping[$index]['person'] = $weekPersonMapping[$index + 1]['person'];
				$weekPersonMapping[$index + 1]['person'] = $tmp;
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

		if (!strpos($email, '@')) {
			$email .= '@softhouse.se';
		}

		echo $email;
		die();
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="sv">
<head>
	<title>Fika list</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="Language" content="Swedish, sv-SE">
	<meta name="Author" content="Tobbe Lundberg">
	<meta name="Robots" content="index,follow">
	<meta name="Description" content="Draggable list items">

	<meta name="Keywords" content="list javascript jquery draggable">

	<link rel="stylesheet" href="style.css" type="text/css" media="screen">

	<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript"></script>
	<!--[if lte IE 7]>
		<style>
			/* Hack to make IE behave as if "border-collapse: separate; 
			   border-spacing: 0;" is set */
			table {
				border-collapse: collapse;
			}

			td {
				position: relative;
			}

			/* Vertical center */
			tr.currentWeek img {
				margin-bottom: 1px;
			}

			tr.nextFika td span {
				border-top: 1px solid #fff;
			}

		</style>
	<![endif]-->
	<script type="text/javascript">
	</script>
</head>
<body>
<div id="header">
	<img src="cupcake.jpg" alt="Cupcake">
	<h1>Fika list</h1>
	<img src="lemonpie.jpg" alt="Lemon Pie">
</div>
<table>
<tr>
	<th></th>
	<th></th>
	<th>Week</th>
	<th>Person</th>
	<th></th>
	<th></th>
	<th></th>
</tr>
<?php foreach($weekPersonMapping as $index => $datePerson): ?>
<?php $date = $datePerson['date']; ?>
<?php $person = $datePerson['person']; ?>
<?php $currentWeek = FikaDate::fromString('now'); ?>
<?php $nextWeek = FikaDate::fromString('+1 week'); ?>

<?php if ($date->isFirstWeekInYear()): ?>
<tr class="mark">	
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td><div class="hr"></div></td>
	<td>
		<div class="hr">
			<p><?php echo $date->year; ?></p>
		</div>
	</td>
</tr>
<?php endif; ?>

<?php if ($date == $currentWeek): ?>
<tr class="currentWeek <?php if (date('N') < 4): ?>nextFika<?php endif; ?>">
	<td class="currentWeek"><img src="calendar.gif" alt="Current Week"></td>
	<?php if (date('N') < 4): ?>
		<td class="nextFika"><img src="cupcake.png" alt="Next person to have fika"></td>
	<?php else: ?>
		<td class="empty"><span>&nbsp;</span></td>
	<?php endif; ?>
<?php elseif ($date == $nextWeek && date('N') >= 4): ?>
<tr class="nextFika">
	<td class="nomark">&nbsp;</td>
	<td class="nextFika"><img src="cupcake.png" alt="Next person to have fika"></td>
<?php else: ?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
<?php endif; ?>
	<td><span class="week"><?php echo $date->week; ?></span></td>
	<td>
		<span class="name"><?php echo $person['name']; ?> (<?php echo $person['acronym']; ?>)</span>
	</td>
	<td><a href="delete.php?acronym=<?php echo $person['acronym']; ?>"><img src="delete.gif" alt="Delete person" class="delete"></a></td>
	<?php if ($index == 0): ?>
	<td><span><img src="blank.gif" class="up"></span></td>
	<?php else: ?>
	<td><a href="move.php?where=up&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>"><img src="up.gif" alt="Move up" class="up"></a></td>
	<?php endif; ?>
	<?php if ($index == count($weekPersonMapping) - 1): ?>
	<td class="lastTD"><span class="lastSPAN"><img src="blank.gif" class="down"></span></td>
	<?php else: ?>
	<td class="lastTD"><a href="move.php?where=down&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>" class="lastA"><img src="down.gif" alt="Move down" class="down"></a></td>
	<?php endif; ?>
</tr>
<?php endforeach; ?>
</table>
<form method="POST" action="add.php">
	<fieldset>
		<legend>Add person to fika list</legend>
		<label>Name: <input type="text" name="personname"></label>
		<label>Full email or Softhouse acronym: <input type="text" name="acronym"></label>
		<input type="submit" value="Add">
	</fieldset>
</form>

</body>
</html>

