<? include_once '../init.php';

// get data objects
$test = ($_DATA = get_post_data())['test'];
$student = $_DATA['student'];

switch($_DATA['cmd']) {
	// Add or edit exsisting test result _________________________________________
	case 'save':
		$retval = query("
			SELECT
				id
			FROM
				mark
			WHERE
				subject_id 			= '".$_SESSION['conn_info'][1]."'
				AND student_id 	= '".$student['id']."'
				AND test_id 		= '".$test['id']."'
			LIMIT 1
		");
		// check if it already exsists
		if (mysqli_num_rows($retval) > 0) {
			execute("
				UPDATE
					mark
				SET
					mark = '".$student['mark']."'
				WHERE
					subject_id 			= '".$_SESSION['conn_info'][1]."'
					AND student_id 	= '".$student['id']."'
					AND test_id 		= '".$test['id']."'
					AND id 					= '".mysqli_fetch_assoc($retval)['id']."'
				LIMIT 1
			");
		}
		else {
			execute("
				INSERT INTO
					mark
					(
						subject_id,
						student_id,
						mark,
						test_id
					)
				VALUES
					(
						'".$_SESSION['conn_info'][1]."',
						'".$student['id']."',
						'".$student['mark']."',
						'".$test['id']."'
					)
			");
		}
		break;

	// Delete exsisting test result ______________________________________________
	case 'remove':
		execute("
			DELETE FROM
				mark
			WHERE
				subject_id 			= '".$_SESSION['conn_info'][1]."'
				AND student_id 	= '".$student['id']."'
				AND test_id 		= '".$test['id']."'
			LIMIT 1
		");
		break;
}
// return result in json if any
print json_encode($result);
