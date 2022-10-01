<?
include_once '../init.php';

function parse_data($_DATA) {
	// check posted data
	check_not_empty($data = $_DATA['el']);
	// escape strings
	$data['title'] = esc_string($data['title']);
	$data['argument'] = esc_string($data['argument']);
	// convert date to standard format or if it does not exist get today's date
	$data['date'] = get_date($data['date']);
	// return the data array
	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new test ____________________________________________________________
	case 'add':
		// get object
		$retval = parse_data($_DATA);
		// convert date to standard format or if it does not exist get today's date
		$retval['date'] = get_date($retval['date']);
		// update DB
		execs("
			INSERT INTO
				test
				(
					subject_id,
					title,
					arguments,
					type,
					date
				)
			VALUES (
					'".$_SESSION['conn_info'][1]."',
					'".$retval['title']."',
					'".$retval['arguments']."',
					'".$retval['type']."',
					'".$retval['date']."'
				)
		");
		// ID of the inserted test
		$retval['id'] = mysqli_insert_id($conn);
		$retval['authorIsMe'] = 1;
		$retval['subject'] = mysqli_fetch_assoc(query("
			SELECT
				name
			FROM
				subject
			WHERE
				id = '".$_SESSION['conn_info'][1]."'
				AND teacher_id = '".$_SESSION['id']."'
		"))['name'];
		break;

	// Edit an already exsisting test ____________________________________________
	case 'edit':
		$data = parse_data($_DATA);
		execute("
			UPDATE
				test
			SET
				title 		= '".$data['title']."',
				arguments = '".$data['arguments']."',
				date 			= '".$data['date']."',
				type 			= '".$data['type']."'
			WHERE
			 	id 	= '".$data['id']."'
				AND subject_id = '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;

	// Delete an already exsisting test __________________________________________
	case 'remove':
		// get object
		$data = $_DATA['el'];
		execs("
			DELETE FROM
				mark
			WHERE
				test_id = '".$data['id']."'
				AND subject_id = '".$_SESSION['conn_info'][1]."'
		");
		execs("
			DELETE FROM
				test
			WHERE
				id = '".$data['id']."'
				AND subject_id = '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;
}
print json_encode($retval);
