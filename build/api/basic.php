<?
session_start();
header('Cache-control: private');

// settings
$access_denied_page = "../redirect.php?page=login/";

// prevent DOS attack
sleep(0.02);

// key for data encryption
$key = "XkmubbRcKx7lXp6fGP9A6pf7TNW01OU1";

// open connection to database
function connect($school) {
	// get school from session if not passed as parameter
	if (!$school) $school = $_SESSION[school];
	// connect to the school database
	$conn = mysqli_connect("localhost", "ulisse", "", "u_s_".$school) or die ('Errore interno. Si prega di riprovare più tardi.');
	return $conn;
}
// close connection do database
function disconnect($conn) {
	mysqli_close($conn);
}
function query($q) {
	global $conn;
	return mysqli_query($conn,$q);
}
// check if logged in (boolean)
function account() {
	return (isset($_SESSION[id])) ? true : false;
}
//password encryption
function pwd_encrypt($pwd) {
	return password_hash($pwd, PASSWORD_DEFAULT);
}
// encrypt string (return encrypted string)
function encrypt($string) {
	$iv = mcrypt_create_iv(
		mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC),
		MCRYPT_DEV_URANDOM
	);

	$encrypted = base64_encode(
		$iv .
		mcrypt_encrypt(
			MCRYPT_RIJNDAEL_128,
			hash('sha256', $key, true),
			$string,
			MCRYPT_MODE_CBC,
			$iv
		)
	);
	return $encrypted;
}
// decrypt string (return decrypted string)
function decrypt($string) {
	$data = base64_decode($string);
	$iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

	$decrypted = rtrim(
		mcrypt_decrypt(
			MCRYPT_RIJNDAEL_128,
			hash('sha256', $key, true),
			substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)),
			MCRYPT_MODE_CBC,
			$iv
		),
		"\0"
	);
	return $decrypted;
}
//execute action on db
function execs() {
	global $conn;

	// get all sql queries
	$queries = func_get_args();

	// process each sql
	foreach($queries as $sql)
		mysqli_query($conn, $sql) or die('error');
}
// execute action on db and then die
function execute() {
  call_user_func_array("execs", func_get_args());
	exit;
}
function esc_string($s) {
	global $conn;
	return ($s) ? mysqli_real_escape_string($conn, $s) : '';
}
// check parameters
function check_param() {
	$parameters = func_get_args();

	foreach($parameters as $par)
		if (!isset($par))
			die('error');
}
// check if var is not empty
function check_not_empty() {
	$parameters = func_get_args();

	foreach($parameters as $par)
		if (!isset($par) || $par == '' || $par == NULL)
			die('error');
}
// random string for temp password
function randStr() {
	$seed = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.'0123456789');
	shuffle($seed);
	$rand = '';
	foreach (array_rand($seed, 10) as $k) $rand .= $seed[$k];
	return $rand;
}
// generate JSON from SQL result (used in PHP inside 'get' folders)
function print_json($result) {
	header("Content-Type: application/json");
	while($r = mysqli_fetch_assoc($result)) $rows[] = $r;
	print json_encode($rows);
}
// swap two variables
function swap(&$x,&$y) {
    $x ^= $y ^= $x ^= $y;
}
// get data object from post parameters
function get_post_data() {
	return json_decode(file_get_contents('php://input'), true);
}
// get a class name for APIs (optimized to reduce SQL queries)
function get_class_name($id, $where) {
	global $classes;
  // if I already have the name than take it
  if ($classes[$id] != null) return $classes[$id];

	// otherwise sql query
  return $classes[$id] = mysqli_fetch_array(query("
    SELECT
      name
    FROM
      class
    WHERE
			id = '".$id."'
      ".$where."
    LIMIT 1
  "))[name];
}
// get a class name for APIs (optimized to reduce SQL queries)
function get_teacher_name($id, $where) {
	global $teachers;
  // if I already have the name than take it
  if ($teachers[$id] != null) return $teachers[$id];

	// otherwise sql query
	$retval = mysqli_fetch_array(query("
    SELECT
      surname, name
    FROM
      teacher
    WHERE
			id = '".$id."'
      ".$where."
    LIMIT 1
  "));

  return $teachers[$id] = $retval[surname].' '.$retval[name];
}
// get a class name for APIs (optimized to reduce SQL queries)
function get_student_name($id, $where) {
	global $students;
  // if I already have the name than take it
  if ($students[$id] != null) return $students[$id];

	// otherwise sql query
	$retval = mysqli_fetch_array(query("
    SELECT
      surname, name
    FROM
      student
    WHERE
			id = '".$id."'
      ".$where."
    LIMIT 1
  "));

  return $students[$id] = $retval[surname].' '.$retval[name];
}
// convert date to standard format or if it does not exist get today's date
function get_date($date) {
	return ($date) ? date("Y-m-d",strtotime($date)) : date("Y-m-d");
}
// get user id from data
function get_username($type, $id, $surname, $name) {
	$surname = strtolower(str_replace(' ','',$surname));
	$name = strtolower(str_replace(' ','',$name));
	return $type.$id.'.'.$surname.'.'.$name;
}
