<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$r = mysqli_fetch_array(query("
  SELECT
    i.id,
    i.name,
    i.surname,
    s.temp_password
  FROM
    admin i
    JOIN
      admin_settings s
      ON
        i.id = s.admin_id
  WHERE
    i.id = '".$_DATA[id]."'
  LIMIT 1
"));

// generate array of data to be shown as json
$rows = array(
  'username'   =>   'a'.$r[id].'.'.strtolower(str_replace(' ','',$r[name]).'.'.str_replace(' ','',$r[surname])),
  'password'   =>   decrypt($r[temp_password])
);

// print json data
print json_encode($rows);
