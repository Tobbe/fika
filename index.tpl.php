<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<title>Fika list</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="Language" content="English, en-EN">
	<meta name="Author" content="Tobbe Lundberg">
	<meta name="Robots" content="index,follow">
	<meta name="Description" content="Draggable list items">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">

	<meta name="Keywords" content="list javascript jquery draggable">

	<link rel="stylesheet" href="style.css" type="text/css" media="screen">

	<!--<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/themes/base/jquery-ui.css" type="text/css" media="all" />
	<link rel="stylesheet" href="http://static.jquery.com/ui/css/demo-docs-theme/ui.theme.css" type="text/css" media="all" />-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript"></script>
	<!--[if lte IE 7]>
		<style>
			form fieldset {
				position: relative;
				padding-top: 1.3em;
			}

			fieldset legend {
				position: absolute;
				top: -.8em;
				left: .1em;
			}

			ul a {
				top: 1px;
			}

			ul a.delete {
				top: 2px;
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

<?php $admin = isset($_GET['admin']); ?>

<ul>
<li class="header">
	<div class="currentWeekDiv">
		<div class="fikaWeekDiv">
			<span class="week">Week</span><span class="person">Person</span>
		</div>
	</div>
</li>
<?php foreach($weekPersonMapping as $index => $datePerson): ?>
<?php $date = $datePerson['date']; ?>
<?php $person = $datePerson['person']; ?>
<?php $currentWeek = FikaDate::fromString('now'); ?>
<?php $nextWeek = FikaDate::fromString('+1 week'); ?>
<?php $isCurrentWeek = ($date == $currentWeek); ?>
<?php $isFikaWeek = (($date == $currentWeek && date('N') < 4) || ($date == $nextWeek && date('N') >= 4)); ?>

<li>
	<div class="currentWeekDiv<?php if ($isCurrentWeek):?> current<?php endif; ?>">
		<?php if ($isCurrentWeek): ?><img src="calendar.gif" alt="Current Week" class="currentWeek<?php if ($isFikaWeek):?> andFikaWeek<?php endif; ?>"><?php endif; ?><!-- Comment needed for IE7 :(
		--><div class="fikaWeekDiv<?php if ($isFikaWeek): ?> next<?php endif; ?>">
			<?php if ($isFikaWeek): ?>
				<img src="cupcake.png" alt="Next person to have fika" class="nextFika">
			<?php endif; ?>
			<span class="week"><?php echo $date->week; ?></span><span class="person"><?php echo $person['name']; ?> (<?php echo $person['acronym']; ?>)</span>
			<a href="move.php?where=up&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>" class="moveup"><img src="up.gif"></a>
			<a href="move.php?where=down&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>" class="movedown"><img src="down.gif"></a>
			<?php if ($admin): ?>
				<a href="delete.php?acronym=<?php echo $person['acronym']; ?>" class="delete"><img src="delete.gif"></a>
			<?php endif; ?>
		</div>
	</div>
</li>

<?php endforeach; ?>
</ul>

<?php if ($admin): ?>
<form method="POST" action="add.php">
	<fieldset>
		<legend>Add person to fika list</legend>
		<div><label for="personname">Name: </label><input type="text" name="personname"></div>
		<div><label for="acronym">Full email or Softhouse acronym: </label><input type="text" name="acronym"></div>
		<input type="submit" value="Add">
	</fieldset>
</form>

<form method="POST" action="setdefault.php">
	<fieldset>
		<legend>Set default order of people</legend>
		<div>Set the current order of people in the fika list as the default order.</div>
		<input type="hidden" name="newOrder" value="<?php echo htmlentities(serialize($reorderedPersonList), ENT_QUOTES); ?>">
		<input type="submit" value="Set default">
	</fieldset>
</form>
<?php endif; ?>

<a href="http://github.com/tobbe/fika" class="githubribbon"><img src="https://assets1.github.com/img/30f550e0d38ceb6ef5b81500c64d970b7fb0f028?repo=&url=http%3A%2F%2Fs3.amazonaws.com%2Fgithub%2Fribbons%2Fforkme_right_orange_ff7600.png&path=" alt="Fork me on GitHub"></a>

</body>
</html>

