<?php
$mysqli = mysqli_connect("localhost", "root", "DB_password", "peace_meet");

// 데이터베이스에 SQL 쿼리를 전송하고 결과를 반환하는 함수
function mq($sql) {
    global $mysqli;
    return $mysqli->query($sql);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'])) {
    $name = $_POST['name'];

    // 현재 대기자의 number를 0으로 DB_password
    $sqlUpdateCurrent = "UPDATE booking SET number = 0 WHERE name = '$name'";
    mq($sqlUpdateCurrent);

    // 나머지 대기자의 number를 1씩 감소
    $sqlUpdateRest = "UPDATE booking SET number = number - 1 WHERE number > 0";
    mq($sqlUpdateRest);

    echo "Update success";
} else {
    echo "Invalid request";
}
?>
