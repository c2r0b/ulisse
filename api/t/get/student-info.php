<?
include_once '../init.php';

$_DATA = get_post_data();

header("Content-Type: application/json");

$r = mysqli_fetch_array(query("
  SELECT
    i.birthday,
    i.birthplace,
    i.tax_code,
    i.cap,
    i.address,
    i.city_of_residence,
    i.country,
    i.state_of_birth,
    i.sex,
    i.religion
  FROM
    student_info i
  JOIN
    student_class c
    ON i.student_id = c.student_id
  WHERE
    i.student_id = '".$_DATA[id]."'
    AND c.class_id = '".$_SESSION[conn_info][0]."'
  LIMIT 1
"));

// generate array of data to be shown as json
$rows = array(
  'birthday'            =>   ($r[birthday] == '0000-00-00' ? '' : $r[birthday]),
  'birthplace'          =>   decrypt($r[birthplace]),
  'tax_code'            =>   decrypt($r[tax_code]),
  'cap'                 =>   decrypt($r[cap]),
  'address'             =>   decrypt($r[address]),
  'city_of_residence'   =>   decrypt($r[city_of_residence]),
  'country'             =>   decrypt($r[country]),
  'state_of_birth'      =>   decrypt($r[state_of_birth]),
  'sex'                 =>   $r[sex],
  'religion'            =>   $r[religion]
);

// print json data
print json_encode($rows);
