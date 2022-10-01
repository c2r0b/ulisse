<?
include_once '../init.php';

function parse_data($_DATA) {
	// check posted data
	check_not_empty($data = $_DATA['el']);
	// generate note type
	switch(count($data['students'])) {
		case 0:
			$data['type'] = 2; // all class
			break;
		case 1:
			$data['type'] = 1; // one
			break;
		default:
			$data['type'] = 0; // more than one
	}
	// convert date to standard format or if it does not exist get today's date
	$data['date'] = get_date($data['date']);
	// return the data array
	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new note ____________________________________________________________
	case 'add':
		$retval = parse_data($_DATA);
		// DB request
		execs("
			INSERT INTO
				note
				(
					class_id,
					teacher_id,
					type,
					text,
					date
				)
			VALUES
				(
					'".$_SESSION['conn_info'][0]."',
					'".$_SESSION['id']."',
					'".$retval['type']."',
					'".mysqli_real_escape_string($conn,$retval['text'])."',
					'".$retval['date']."'
				)
		");
		// get generated note id
		$id = mysqli_insert_id($conn);
		// set note recepients
		if ($retval['type'] != 2) {
			foreach($retval['students'] as $recipient) {
				execs("
					INSERT INTO
						note_student
					VALUES
						(
							'".$id."',
							'".$recipient['id']."'
						)
				");
			}
	  }
		// data of the added note
		$retval["authorIsMe"] = true;
		break;

	// Edit an already exsisting note ____________________________________________
	case 'edit':
		$retval = parse_data($_DATA);
		execs("
			UPDATE
				note
			SET
				text = '".mysqli_real_escape_string($conn, $retval['text'])."',
				date = '".$retval['date']."',
				type = '".$retval['type']."'
			WHERE
			 	id = '".$retval['id']."'
				AND teacher_id = '".$_SESSION['id']."'
				AND class_id = '".$_SESSION['conn_info'][0]."'
			LIMIT 1
		");
		execs("
			DELETE FROM
				note_student
			WHERE
				note_id = '".$retval['id']."'
		");
		// set note recepients
		if ($retval['type'] != 2) {
			foreach($retval['students'] as $recipient) {
				execs("
					INSERT INTO
						note_student
					VALUES
						(
							'".$retval['id']."',
							'".$recipient['id']."'
						)
				");
			}
	  }
		// data of the edited note
		$retval["authorIsMe"] = true;
		break;

	// Delete an already exsisting note __________________________________________
	case 'remove':
		$data = $_DATA['el'];
		execs("
			DELETE FROM
				note
			WHERE
				id = '".$data['id']."'
				AND teacher_id = '".$_SESSION['id']."'
				AND class_id = '".$_SESSION['conn_info'][0]."'
			LIMIT 1
		");
		execute("
			DELETE FROM
				note_student
			WHERE
				note_id = '".$data['id']."'
		");
		break;
}
print json_encode($retval);
