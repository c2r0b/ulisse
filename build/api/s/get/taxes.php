<?
include_once '../init.php';

print_json(query("
  SELECT
    t.amount,
    t.description,
    (
      SELECT
        count(*)
      FROM
        payment
      WHERE
        student_id = '".$_SESSION[id]."'
        AND tax_id = t.id
      LIMIT 1
    ) paid
  FROM
    tax t
"));
