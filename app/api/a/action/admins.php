<?
include_once '../init.php';

$_DATA = get_post_data();

if (isset($_DATA[add])) {
	// get admin object
	$data = $_DATA[add];
	// convert date to standard format
	$data[birthday] = get_date($data[birthday]);
	// generate and encrypt password
	$pwd = randStr();
	// update DB
	execs("
		INSERT INTO
			admin
			(
				name,
				surname
			)
		VALUES
			(
				'".$data['name']."',
				'".$data['surname']."'
			)
	");
	// get generated id
	$id = mysqli_insert_id($conn);
	execs("
		INSERT INTO
			admin_info
			(
				admin_id,
				email,
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
			admin_settings
			(
				admin_id,
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
	// data of the added admin to be added in the app scope
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
			admin
		SET
			name 								= 	'".$data['name']."',
			surname 						= 	'".$data['surname']."'
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
	execute("
		UPDATE
			admin_info
		SET
			email 							= 	'".encrypt($data['email'])."',
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
			admin_id = '".$data[id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[permissions])) {
	// get object
	$data = $_DATA[permissions];
	// query for parent 1
	execute("
		UPDATE
			admin_settings
		SET
			adminsManagement			= 	'".$data[0]['adminsManagement']."',
			teachersManagement		= 	'".$data[0]['teachersManagement']."',
			studentsManagement		= 	'".$data[0]['studentsManagement']."',
			classesManagement			= 	'".$data[0]['classesManagement']."',
			taxesManagement				= 	'".$data[0]['taxesManagement']."',
			meetingsManagement		= 	'".$data[0]['meetingsManagement']."',
			newsManagement				= 	'".$data[0]['newsManagement']."',
			scheduleManagement		= 	'".$data[0]['scheduleManagement']."'
		WHERE
			admin_id = '".$data[0][id]."'
		LIMIT 1
	");
}
elseif (isset($_DATA[remove])) {
	// get object
	$data = $_DATA[remove];
	// remove admin related data from the DB
	execs("DELETE FROM admin_info 		WHERE admin_id = '".$data[id]."'");
	execs("DELETE FROM admin_settings WHERE admin_id = '".$data[id]."'");
	// remove the admin from the DB
	execute("
		DELETE FROM
			admin
		WHERE
			id = '".$data[id]."'
		LIMIT 1
	");
}
// return result in json if any
print json_encode($result);
