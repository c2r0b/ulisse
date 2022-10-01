<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get class object
	$data = $_DATA[add];
	// check parrameters
	check_not_empty($data['class'],$data['student']);
	// update DB
	execs("
		INSERT INTO
			student_class
			(
				class_id,
				student_id
			)
		VALUES
			(
				'".$data['class']."',
				'".$data['student']."'
			)
	");
	// data of the added student to the class
	$result = mysqli_fetch_array(query("
		SELECT
			id,
			surname,
			name
		FROM
			student
		WHERE
			id = '".$data['student']."'
		LIMIT 1
	"));
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// check parrameters
	check_not_empty($data['class'],$data['student']);
	// remove the student from the class
	execs("
		DELETE FROM
			student_class
		WHERE
			class_id = '".$data['class']."'
			AND student_id = '".$data['student']."'
	");
}
// return result in json if any
print json_encode($result);
