<?
include_once '../init.php';

$_DATA = get_post_data();

// destroy session in DB
execs("
  UPDATE
    student_settings
  SET
    connected_class = ''
  WHERE
    student_id = '".$_SESSION['id']."'
");
// destroy session parameter
unset($_SESSION['conn_info']);

if (isset($_DATA['class'])) {
  // get requested instance data
  $data = mysqli_fetch_array($retval = query("
    SELECT
      c.name name,
      c.id
    FROM
      student_class s
    JOIN
      class c
      ON
        s.class_id = c.id
    WHERE
      s.student_id = '".$_SESSION['id']."'
      AND s.class_id = '".$_DATA['class']."'
    LIMIT 1
  "));
  // check if the student has permissions
  if (mysqli_num_rows($retval) > 0) {
    // update in DB and set new connection info into session
    execs("
      UPDATE
        student_settings
      SET
        connected_class = '".($_SESSION['conn_info'] = $data['id'])."'
      WHERE
        student_id = '".$_SESSION['id']."'
    ");
  	// data of the new session
    $rows['class'] = mysqli_fetch_array(query("
  		SELECT
  			*
  		FROM
  			class
  		WHERE
  			id = '".$_SESSION['conn_info']."'
  		LIMIT 1
  	"));
  	print json_encode($rows);
  }
}
