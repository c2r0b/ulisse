<?
include_once '../init.php';

// get instant
$time = time();
$sign =
	[
		'date' => date('Y-m-d', $time),
		'time' => date('H:i:s', $time)
	];

// insert in DB
execs("
	INSERT INTO
		signature
		(
			teacher_id,
			subject_id,
			date,
			time
		)
	VALUES (
			'".$_SESSION[id]."',
			'".$_SESSION[conn_info][1]."',
			'".$sign[date]."',
			'".$sign[time]."'
		)
");
// print signature info
print json_encode($sign);
