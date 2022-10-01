<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get student object
	$data = $_DATA[add];
	// convert date to standard format
	$data[date] = get_date($data[date]);
	// update DB
	execs("
		INSERT INTO
			meeting
			(
				teacher_id,
				date,
				time,
				people
			)
		VALUES (
			'".$data[teacher]."',
			'".$data[date]."',
			'".implode(':',$data[time])."',
			'".$data[people]."'
		)
	");
	// data of the added student to be added in the app scope
	$result = array(
		"id" => mysqli_insert_id($conn),
		"teacher" => get_teacher_name($data[teacher]),
		"teacher_id" => $data[teacher],
		"date" => $data[date],
		"time" => $data[time],
		"people" => $data[people],
		"bookedCount" => 0
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];
	// convert date to standard format
	$data[date] = get_date($data[date]);

	execs("
		UPDATE
			meeting
		SET
			time = '".implode(':',$data[time])."',
			people = '".$data[people]."',
			date = '".$data[date]."',
			teacher_id = '".$data[teacher]."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
	// data of the added meeting to be added in the app scope
	$result = array(
		"id" => $data[id],
		"teacher" => get_teacher_name($data[teacher]),
		"teacher_id" => $data[teacher],
		"date" => $data[date],
		"time" => $data[time],
		"people" => $data[people],
		"bookedCount" => $data[bookedCount]
	);
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// remove bookings for the selected meeting
	execs("DELETE FROM booking WHERE meeting_id = '".$data[id]."'");
	// remove the student from the DB
	execute("
		DELETE FROM
			meeting
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
