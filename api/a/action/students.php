<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get student object
	$data = $_DATA[add];
	// convert date to standard format
	$data[birthday] = get_date($data[birthday]);
	// generate and encrypt password
	$pwd = randStr();
	// update DB
	execs("
		INSERT INTO
			student
			(
				name,
				surname,
				delays,
				representative
			)
		VALUES
			(
				'".$data['name']."',
				'".$data['surname']."',
				0,
				0
			)
	");
	// get generated id
	$id = mysqli_insert_id($conn);
	execs("
		INSERT INTO
			student_info
			(
				student_id,
				email,
				religion,
				birthday,
				birthplace,
				sex,
				tax_code,
				cap,
				address,
				city_of_residence,
				country,
				telephone,
				mobile,
				state_of_birth
			)
		VALUES
			(
				'".$id."',
				'".encrypt($data['email'])."',
				'".$data['religion']."',
				'".$data['birthday']."',
				'".encrypt($data['birthplace'])."',
				'".$data['sex']."',
				'".encrypt($data['tax_code'])."',
				'".encrypt($data['cap'])."',
				'".encrypt($data['address'])."',
				'".encrypt($data['city_of_residence'])."',
				'".encrypt($data['country'])."',
				'".encrypt($data['telephone'])."',
				'".encrypt($data['mobile'])."',
				'".encrypt($data['state_of_birth'])."'
			)
	");
	execs("
		INSERT INTO
			student_settings
			(
				student_id,
				temp_password,
				lang
			)
		VALUES
			(
				'".$id."',
				'".encrypt($pwd)."',
				'it'
			)
	");
	// setup parents data
	execs("INSERT INTO parent (student_id,representative) VALUES('".$id."',0)");
	execs("INSERT INTO parent_info (parent_id) VALUES('".mysqli_insert_id($conn)."')");
	execs("INSERT INTO parent (student_id,representative) VALUES('".$id."',0)");
	execs("INSERT INTO parent_info (parent_id) VALUES('".mysqli_insert_id($conn)."')");
	// data of the added student to be added in the app scope
	$result = array(
		"id" => $id,
		"surname" => $data['surname'],
		"name" => $data['name']
	);
}
elseif (isset($_DATA[edit])) {
	// get object
	$data = $_DATA[edit];
	// convert date to standard format
	$data[birthday] = get_date($data[birthday]);
	// query SQL
	execs("
		UPDATE
			student
		SET
			name 			= 	'".$data['name']."',
			surname 	= 	'".$data['surname']."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
	execute("
		UPDATE
			student_info
		SET
			email 							= 	'".encrypt($data['email'])."',
			religion 						= 	'".$data['religion']."',
			birthday 						= 	'".$data['birthday']."',
			birthplace 					= 	'".encrypt($data['birthplace'])."',
			sex 								= 	'".$data['sex']."',
			tax_code 						= 	'".encrypt($data['tax_code'])."',
			cap 								=		'".encrypt($data['cap'])."',
			address 						= 	'".encrypt($data['address'])."',
			city_of_residence 	= 	'".encrypt($data['city_of_residence'])."',
			country 						= 	'".encrypt($data['country'])."',
			telephone 					= 	'".encrypt($data['telephone'])."',
			mobile 							= 	'".encrypt($data['mobile'])."',
			state_of_birth 			= 	'".encrypt($data['state_of_birth'])."'
		WHERE
			student_id = '".$data[id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[parents])) {
	// get object
	$data = $_DATA[parents];
	// convert date to standard format
	$data[birthday] = get_date($data[birthday]);
	// query for parent 1
	execs("
		UPDATE
			parent
		SET
			name 								= 	'".$data[0]['name']."',
			surname 						= 	'".$data[0]['surname']."'
		WHERE
			id = '".$data[0][id]."'
		LIMIT 1
	");
	execs("
		UPDATE
			parent_info
		SET
			email 							= 	'".encrypt($data[0]['email'])."',
			birthday 						= 	'".$data[0]['birthday']."',
			sex 								= 	'".$data[0]['sex']."',
			tax_code 						= 	'".encrypt($data[0]['tax_code'])."',
			telephone 					= 	'".encrypt($data[0]['telephone'])."',
			mobile 							= 	'".encrypt($data[0]['mobile'])."'
		WHERE
			parent_id = '".$data[0][id]."'
		LIMIT 1
	");
	// query for parent 2
	execs("
		UPDATE
			parent
		SET
			name 								= 	'".$data[1]['name']."',
			surname 						= 	'".$data[1]['surname']."'
		WHERE
			id = '".$data[1][id]."'
		LIMIT 1
	");
	execute("
		UPDATE
			parent_info
		SET
			email 							= 	'".encrypt($data[1]['email'])."',
			birthday 						= 	'".$data[1]['birthday']."',
			sex 								= 	'".$data[1]['sex']."',
			tax_code 						= 	'".encrypt($data[1]['tax_code'])."',
			telephone 					= 	'".encrypt($data[1]['telephone'])."',
			mobile 							= 	'".encrypt($data[1]['mobile'])."'
		WHERE
			parent_id = '".$data[1][id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// remove student related data from the DB
	execs("DELETE FROM student_info 		WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM student_settings WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM student_class 		WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM parent 					WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM absence 					WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM mark 	 					WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM booking 					WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM payment 				 	WHERE student_id = '".$data[id]."'");
	execs("DELETE FROM note_student		 	WHERE student_id = '".$data[id]."'");
	// remove the student from the DB
	execute("
		DELETE FROM
			student
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
