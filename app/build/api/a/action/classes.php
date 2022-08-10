<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get class object
	$data = $_DATA[add];
	// update DB
	execs("
		INSERT INTO
			class
			(
				name,
				coordinator_id
			)
		VALUES
			(
				'".$data[name]."',
				'".$data[coordinator_id]."'
			)
	");
	// get generated id
	$id = mysqli_insert_id($conn);
	// bind selected students with current class
	foreach($data[students] as $student) {
		execs("
			INSERT INTO
				student_class
				(
					student_id,
					class_id
				)
			VALUES
				(
					'".$student[id]."',
					'".$id."'
				)
		");
	}
	// bind selected teachers and subjects with current class
	foreach($data[teachers] as $teacher) {
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
					'".$id."',
					'".$teacher[id]."',
					'".$teacher[option]."'
				)
		");
	}
	// get coordinator name to be added to the inline list
	if ($data[coordinator_id]) {
		$coordinator = mysqli_fetch_array(query("
			SELECT
				concat(t.surname,' ',t.name) name
			FROM
				teacher t
			WHERE
				t.id = '".$data[coordinator_id]."'
			LIMIT 1
		"));
	}
	// data of the added class to be added in the app scope
	$result = array(
		"id" => $id,
		"name" => $data[name],
		"coordinator" => $coordinator[name]
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];
	// query SQL
	execs("
		UPDATE
			class
		SET
			name 								= 	'".$data[name]."',
			coordinator_id 			= 	'".$data[coordinator_id]."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
	// get coordinator name to be added to the inline list
	if ($data[coordinator_id]) {
		$coordinator = mysqli_fetch_array(query("
			SELECT
				concat(t.surname,' ',t.name) name
			FROM
				teacher t
			WHERE
				t.id = '".$data[coordinator_id]."'
			LIMIT 1
		"));
	}
	// data of the added class to be added in the app scope
	$result = array(
		"id" => $data[id],
		"name" => $data[name],
		"coordinator" => $coordinator[name]
	);
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// remove student related data from the DB
	execs("DELETE FROM student_class 		WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM subject					WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM schedule			 		WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM reminder 				WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM note 						WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM mark 	 					WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM homework					WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM desks_disposal  	WHERE class_id = '".$data[id]."'");
	execs("DELETE FROM argument			  	WHERE class_id = '".$data[id]."'");
	// remove the student from the DB
	execute("
		DELETE FROM
			class
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
