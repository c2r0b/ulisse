<?
include_once '../init.php';

$_DATA = get_post_data();

// destroy session in DB
execs("
  UPDATE
    teacher_settings
  SET
    connected_class = ''
  WHERE
    teacher_id = '".$_SESSION['id']."'
");
// destroy session parameter
unset($_SESSION['conn_info']);

if (isset($_DATA['class']) && isset($_DATA['subject'])) {
  // get requested instance data
  $data = mysqli_fetch_array($retval = query("
    SELECT
      c.name class,
      s.name subject,
      c.id class_id,
      s.id subject_id
    FROM
      subject s
    JOIN
      class c
      ON
        s.class_id = c.id
    WHERE
      s.id = '".$_DATA['subject']."'
      AND s.teacher_id = '".$_SESSION['id']."'
      AND s.class_id = '".$_DATA['class']."'
    LIMIT 1
  "));
  // check if the teacher has permissions
  if (mysqli_num_rows($retval) > 0) {

    // save connection to class
    $connected_class = $data['class_id'].'$//$'.$data['subject_id'];
    // update in DB
    execs("
      UPDATE
        teacher_settings
      SET
        connected_class = '".$connected_class."'
      WHERE
        teacher_id = '".$_SESSION['id']."'
    ");
    // set new connection info into session
    $_SESSION['conn_info'] = explode('$//$', $connected_class);
  	// get data of the new session
    $rows['class'] = mysqli_fetch_array(query("
  		SELECT
  			*
  		FROM
  			class
  		WHERE
  			id = '".$_SESSION['conn_info'][0]."'
  		LIMIT 1
  	"));
    $rows['subject'] = mysqli_fetch_array(query("
  		SELECT
  			*
  		FROM
  			subject
  		WHERE
  			id = '".$_SESSION['conn_info'][1]."'
  		LIMIT 1
  	"));
  	print json_encode($rows);
  }
}
