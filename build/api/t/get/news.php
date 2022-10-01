<?
include_once '../init.php';

print_json(
  query("
    SELECT
      title,
      content,
      date
    FROM
      report
    ORDER BY
      date DESC
  ")
);
