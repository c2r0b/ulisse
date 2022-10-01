<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get request and subject object
	$data = $_DATA[add];
	// check parrameters
	check_not_empty($data['class'],$data['teacher'],$data['subject']);

	$data['subject'] = mysqli_real_escape_string($conn,$data['subject']);
	// update DB
	execs("
		INSERT INTO
			subject
			(
				class_id,
				teacher_id,
				name
			)
		VALUES
			(
				'".$data['class']."',
				'".$data['teacher']."',
				'".$data['subject']."'
			)
	");
	// data of the added subject to the class
	$result = array(
		'id'					=>		mysqli_insert_id($conn),
		'name'				=>		$data['subject'],
		'teacher_id'	=>		$data['teacher'],
		'teacher'			=>		mysqli_fetch_array(query("
													SELECT
														concat(surname,' ',name) name
													FROM
														teacher
													WHERE
														id = '".$data['teacher']."'
													LIMIT 1
												"))['name']
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];
	// check parrameters
	check_not_empty(
		$data[class_id],
		$data[teacher_id],
		$data[subject_id]
	);
	// edit the subject
	execs("
		UPDATE
			subject
		SET
			name = '".$data[subject_name]."',
			teacher_id = '".$data[teacher_id]."'
		WHERE
			id = '".$data[subject_id]."'
			AND class_id  = '".$data[class_id]."'
		LIMIT 1
	");
	// data of the added subject to the class
	$result = array(
		'id'					=>		$data[subject_id],
		'name'				=>		$data[subject_name],
		'teacher_id'	=>		$data[teacher_id],
		'teacher'			=>		mysqli_fetch_array(query("
											SELECT
												concat(surname,' ',name) name
											FROM
												teacher
											WHERE
												id = '".$data[teacher_id]."'
											LIMIT 1
										"))[name]
	);
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// check parrameters
	check_not_empty($data['class'],$data['subject']);
	// remove related data
	execs("DELETE FROM mark 				WHERE subject_id = '".$data[subject]."'");
	execs("DELETE FROM homework 		WHERE subject_id = '".$data[subject]."'");
	execs("DELETE FROM argument 		WHERE subject_id = '".$data[subject]."'");
	// remove the subject from the class
	execute("
		DELETE FROM
			subject
		WHERE
			class_id = '".$data['class']."'
			AND id = '".$data['subject']."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
