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

			form fieldset {
				position: relative;
				padding-top: 1.3em;
			}

			fieldset legend {
				position: absolute;
				top: -.8em;
				left: .1em;
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
	<td><a href="move.php?where=up&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>"><img src="up.gif" alt="Move up" class="up"></a></td>
	<td class="lastTD"><a href="move.php?where=down&acronym=<?php echo $person['acronym']; ?>&year=<?php echo $date->year; ?>&week=<?php echo $date->week; ?>" class="lastA"><img src="down.gif" alt="Move down" class="down"></a></td>
</tr>
<?php endforeach; ?>
</table>

<a href="http://github.com/tobbe/fika" class="githubribbon"><img src="https://assets1.github.com/img/30f550e0d38ceb6ef5b81500c64d970b7fb0f028?repo=&url=http%3A%2F%2Fs3.amazonaws.com%2Fgithub%2Fribbons%2Fforkme_right_orange_ff7600.png&path=" alt="Fork me on GitHub"></a>

</body>
</html>

