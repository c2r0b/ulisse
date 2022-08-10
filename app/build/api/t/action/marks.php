<?
include_once '../init.php';

function parse_data($_DATA) {
	check_not_empty(($data = $_DATA['mark'])['mark']);

	// cahnge date format or get today's date if none
	$data['date'] = get_date($data['date']);

	// default value for type: 0
	if ($data['type'] == '') $data['type'] = 0;

	// sanitize desc if any
	$data['text'] = esc_string($data['text']);

	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new mark ____________________________________________________________
	case 'add':
		$retval = parse_data($_DATA);
		execs("
			INSERT INTO
				mark
				(
					subject_id,
					student_id,
					type,
					mark,
					text,
					date
				)
			VALUES
				(
					'".$_SESSION['conn_info'][1]."',
					'".$_DATA['student_id']."',
					'".$retval['type']."',
					'".$retval['mark']."',
					'".$retval['text']."',
					'".$retval['date']."'
				)
		");
		// get added row auto incremented ID
		$retval['id'] = mysqli_insert_id($conn);
		break;

	// Edit an already exsisting mark ____________________________________________
	case 'edit':
		$data = parse_data($_DATA);
		execs("
			UPDATE
				mark
			SET
				mark 	=	'".$data['mark']."',
				type 	= '".$data['type']."',
				date 	= '".$data['date']."',
				text 	= '".$data['text']."'
			WHERE
				id 							= '".$data['id']."'
				AND student_id 	= '".$_DATA['student_id']."'
				AND subject_id 	= '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;

	// Edit an already exsisting mark that is linked with a test _________________
	case 'editTestMark':
		check_not_empty(
			($data = $_DATA['mark'])['mark']
		);
		execs("
			UPDATE
				mark
			SET
				mark =	'".$data['mark']."'
			WHERE
				id 							= '".$data['id']."'
				AND student_id 	= '".$_DATA['student_id']."'
				AND subject_id 	= '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;

	// Delete an already exsisting mark (also if linked with a test) _____________
	case 'remove':
		execute("
			DELETE FROM
				mark
			WHERE
				id 							= '".$_DATA['mark_id']."'
				AND student_id 	= '".$_DATA['student_id']."'
				AND subject_id 	= '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;
}
print json_encode($retval);
