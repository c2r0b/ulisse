<?
include_once '../init.php';

// check if the selected student is part of the logged in class
function check_student($id) {
	if (mysqli_num_rows(query("
		SELECT
			*
		FROM
			student_class
		WHERE
			student_id = '".$id."'
			AND class_id = '".$_SESSION['conn_info'][0]."'
		LIMIT 1
	")) == 0) die('error');
}

// parse absence object data
function parse_data($_DATA) {
	// check data object
	check_param(($data = $_DATA['el']));

	// check student id
	check_not_empty(($data = $_DATA['student_id']));

	// type
	$data['type'] = (isset($data['type'])) ? $data['type'] : 0;

	// justification
	$data['justification'] = (isset($data['justification'])) ? $data['justification'] : '';

	// is justified?
	$data['justified'] = (isset($data['justified'])) ? $data['justified'] : 0;

	// time
	if (isset($data['hour']) && isset($data['minutes']))
		$data['time'] = sprintf('%02u', $data['hour']).':'.sprintf('%02u', $data['minutes']);
	else
		$data['time'] = null;

	// clear old data
	$data['hour'] = $data['minutes'] = null;
	return $data;
}

switch(($_DATA = get_post_data())['cmd']) {
	// Add a new absence _________________________________________________________
	case 'add':
		$data = parse_data($_DATA);
		// insert absence into DB
		execs("
			INSERT INTO
				absence
				(
					student_id,
					teacher_id,
					date,
					time,
					justified,
					justification,
					type
				)
			VALUES
				(
					'".$_DATA['student_id']."',
					'".$_SESSION['id']."',
					'".date('Y-m-d')."',
					'".$time."',
					'".$justified."',
					'".$justification."',
					'".$type."'
				)
		");

		// delete current absences on the same date
		if ($type == 1 || $type == 2) {
			execs("
				DELETE FROM
					absence
				WHERE
					student_id = '".$_DATA['student_id']."'
					AND teacher_id = '".$_SESSION[id]."'
					AND date = '".date("Y-m-d")."'
					AND (type = 0 || type = 3)
			");
		}

		// data of the added absence
		$retval = [
			"date" => date("Y-m-d"),
			"time" => $time,
			"type" => $type
		];
		break;

	// Justify an exsisting absence ______________________________________________
	case 'justify':
		check_param(($data = $_DATA['el']));

		check_not_empty(
			$data['date'],
			$data['type']
		);

		execs("
			UPDATE
				absence
			SET
				justified = 1,
				justification = '".esc_string($data['justification'])."'
			WHERE
				student_id = '".$data['justify']."'
				AND date = '".$data['date']."'
				AND type = '".intval($data['type'])."'
				AND teacher_id = '".$_SESSION['id']."'
		");
		break;

	// Delete an exsisting absence ______________________________________________
	case 'remove':
		check_param(($data = $_DATA['el']));
		check_student($_DATA['student_id']);

		$data['date'] = get_date($data['date']);
		$data['type'] = isset($data['type']) ? $data['type'] : "0 || type = 2";

		execs("
			DELETE FROM
				absence
			WHERE
				student_id = '".$_DATA['student_id']."'
				AND teacher_id = '".$_SESSION['id']."'
				AND date = '".$date."'
				AND
					(
						type = '.$type.'
						|| type = 3
					)
		");
		break;

	// Increment/decrement student delays count  _________________________________
	case 'delay':
		check_not_empty($_DATA['step']);
		check_student($_DATA['student_id']);

		execs("
			UPDATE
				student
			SET
				delays = delays + '".$_DATA['step']."'
			WHERE
				id = '".$_DATA['student_id']."'
		");

		 execs("
			 DELETE FROM
					absence
				WHERE
					student_id = '".$_DATA['student_id']."'
					AND teacher_id = '".$_SESSION['id']."'
					AND date = '".date("Y-m-d")."'
					AND (type = 0 || type = 3)
			");
			break;
}
// return result in json if any
print json_encode($retval);
