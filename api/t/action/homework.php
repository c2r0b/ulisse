<?
include_once '../init.php';

function parse_data($_DATA) {
	check_param(($data = $_DATA['el']));
	// change date format or get today's date if none
	$data['date'] = get_date($data['date']);
	// sanitize text
	$data['text'] = esc_string($data['text']);

	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new homework ________________________________________________________
	case 'add':
		$retval = parse_data($_DATA);
		execs("
			INSERT INTO
				homework
				(
					subject_id,
					text,
					date
				)
			VALUES (
					'".$_SESSION['conn_info'][1]."',
					'".$retval['text']."',
					'".$retval['date']."'
				)
		");
		// get added row auto incremented ID
		$retval['id'] = mysqli_insert_id($conn);
		break;

	// Edit an already exsisting homework ________________________________________
	case 'edit':
		$data = parse_data($_DATA);
		execute("
			UPDATE
				homework
			SET
				text = '".$data['text']."',
				date = '".$data['date']."'
			WHERE
			 	id = '".$data['id']."'
				AND subject_id = '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;

	// Delete an already exsisting homework ______________________________________
	case 'remove':
		execute("
			DELETE FROM
				homework
			WHERE
				id = '".$_DATA['el']['id']."'
				AND subject_id = '".$_SESSION['conn_info'][1]."'
			LIMIT 1
		");
		break;
}
print json_encode($retval);
