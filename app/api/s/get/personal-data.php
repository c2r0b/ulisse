<?
include_once '../init.php';

header("Content-Type: application/json");

$r = mysqli_fetch_array(query("
  SELECT
    s.id,
    s.name,
    s.surname,
    i.email,
    i.birthday,
    i.birthplace,
    i.tax_code,
    i.cap,
    i.address,
    i.city_of_residence,
    i.country,
    i.telephone,
    i.mobile,
    i.state_of_birth,
    i.sex,
    i.religion
  FROM
    student_info i
  JOIN
    student s
    ON
      i.student_id = s.id
  WHERE
    i.student_id = '".$_SESSION[id]."'
  LIMIT 1
"));
// generate array of data to be shown as json
$rows[info] = array(
  'school_id'           =>    $_SESSION[school],
  'user_id'             =>    get_username('s',$r[id],$r[surname],$r[name]),
  'email'               =>    decrypt($r[email]),
  'birthday'            =>    $r[birthday],
  'birthplace'          =>    decrypt($r[birthplace]),
  'tax_code'            =>    decrypt($r[tax_code]),
  'cap'                 =>    decrypt($r[cap]),
  'address'             =>    decrypt($r[address]),
  'city_of_residence'   =>    decrypt($r[city_of_residence]),
  'country'             =>    decrypt($r[country]),
  'telephone'           =>    decrypt($r[telephone]),
  'mobile'              =>    decrypt($r[mobile]),
  'state_of_birth'      =>    decrypt($r[state_of_birth]),
  'sex'                 =>    $r[sex],
  'religion'            =>    $r[religion]
);

// print json data
print json_encode($rows);
