<?
include_once '../init.php';

function parse_data($_DATA) {
	// check posted data
	check_not_empty($data = $_DATA['el']);
	// convert date to standard format or if it does not exist get today's date
	$data['date'] = get_date($data['date']);
	// sanitize text
	$data['text'] = esc_string($data['text']);
	// return the data array
	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new note ____________________________________________________________
	case 'add':
		$retval = parse_data($_DATA);
		// update DB
		execs("
			INSERT INTO
				reminder
				(
					class_id,
					teacher_id,
					text,
					date
				)
			VALUES
				(
					'".$_SESSION['conn_info'][0]."',
					'".$_SESSION['id']."',
					'".$retval['text']."',
					'".$retval['date']."'
				)
		");
		// data of the added absence
		$retval['id'] = mysqli_insert_id($conn);
		$retval['authorIsMe'] = 1;
		break;

	// Edit an already exsisting reminder ________________________________________
	case 'edit':
		$data = parse_data($_DATA);
		execute("
			UPDATE
				reminder
			SET
				text = '".$data['text']."',
				date = '".$data['date']."'
			WHERE
				teacher_id = '".$_SESSION['id']."'
			 	AND id = '".$data['id']."'
				AND class_id = '".$_SESSION['conn_info'][0]."'
			LIMIT 1
		");
		break;

case 'remove':
	execute("
		DELETE FROM
			reminder
		WHERE
			id = '".$_DATA['el']['id']."'
			AND teacher_id = '".$_SESSION['id']."'
			AND class_id = '".$_SESSION['conn_info'][0]."'
		LIMIT 1
	");
	break;
}
// return result in json if any
print json_encode($retval);
