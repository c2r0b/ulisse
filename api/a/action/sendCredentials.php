<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$id = $_DATA[send];

$r = mysqli_fetch_assoc(query("
  SELECT
    name,
		surname,
		email,
		temp_password
  FROM
    student
  WHERE
    id = '".$id."'
  LIMIT 1
"));

$r[email] = decrypt($r[email]);

if (filter_var($r[email], FILTER_VALidATE_EMAIL)) {
	// email generation
	$to = $r[email];
	$subject = "Ulisse.school - Account credentials";
	$headers = "";
	$message = "Username: s".$id."."
						 .strtolower(str_replace(' ','',decrypt($r[name])).'.'
						 .str_replace(' ','',decrypt($r[surname])))."\r\n".
						 "Password: ".decrypt($r[temp_password]);

	// send
	mail($r[email], $subject, $message, $headers);

	$response = array(
		"msg" => "sent"
	);
}
else {
	$response = array(
		"msg" => "error"
	);
}

// return result in json if any
print json_encode($response);
