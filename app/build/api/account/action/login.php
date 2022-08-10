<?
include '../../basic.php';

// do nothing if already logged and return error code
if (account()) {
	print 0;
	die;
}

// get data from post request
$_DATA = get_post_data();

// get data from post request in variables
$school_id = $_DATA[school_id];
$user = $_DATA[user_id];
$password = $_DATA[password];

// if something has been posted
if ($user != '' && $password != '' && $school_id != '') {
	// get login code parts (id, name, surname)
	$user = explode('.',$user);
	/*
		account type:
		1 = student
		2 = teacher
		3 = admin
	*/
	$type = strpos('sta', $user[0][0]);

	// database tables for each account type
	$table = ['student','teacher','admin'][$type];

	// account id is the user_id first part without first letter (s/t/a)
	$id = substr($user[0], 1);

 	// connect to the selected school DB
	$conn = connect($school_id);

	// get data from DB
	$ACCOUNT = mysqli_fetch_array(
						 	mysqli_query(
								$conn,
								"SELECT
									t.id,
									t.surname,
									t.name,
									s.password,
									s.temp_password
								FROM
									".$table." t
								JOIN
									".$table."_settings s
									ON
										t.id = s.".$table."_id
								WHERE
									t.id = '".$id."'
								LIMIT 1"
							)
						);
	// close DB connection
	disconnect($conn);

	// validate data
	if (
			(
				password_verify($password, $ACCOUNT[password])
				||
					(
						$ACCOUNT[password] == NULL
						&& $password == encrypt($ACCOUNT[temp_password])
					)
			)
			&& $user[1] == strtolower(str_replace(' ','',$ACCOUNT[name]))
			&& $user[2] == strtolower(str_replace(' ','',$ACCOUNT[surname]))
		) {
		// set session data
		session_start();
		$_SESSION[school] = $school_id;
		$_SESSION[id] = $ACCOUNT[id];
		$_SESSION[type] = ++$type;
		session_commit();
		// succcess response
		print $type;
	}
}
else print 0; // error
