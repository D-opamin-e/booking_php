<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['number']) && isset($_GET['name']) && isset($_GET['wait_number'])) {
    // DB 연결 정보
    function mq($sql) {
        global $mysqli;
        return $mysqli->query($sql);
    }

    $servername = "localhost";
    $username = "root";
    $password = "DB_password";
    $dbname = "peace_meet";

    // Create connection
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    }

    $wait_number = $_GET['wait_number'];
    $number = $_GET['number'];
    $name = $_GET['name'];

    // 알리고문자 API 정보
    $api_key = "api_key_알리고문자";
    $sender_number = "수신번호";
    $message = "평화김해뒷고기 율하2지구입니다. 상 정리를 마무리 중이니, 입장 대기 부탁드리겠습니다";
    $sms_url = "https://apis.aligo.in/send/";

    $data = [
        "key" => $api_key,
        "user_id" => "kkk234454",
        "sender" => $sender_number,
        "receiver" => $number,
        "msg" => $message,
        "testmode_yn" => "Y",
    ];

    $ch = curl_init($sms_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $status = ""; // Initialize status variable

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
    } else {
        $result = json_decode($response, true);
        if ($result && isset($result['result_code']) && $result['result_code'] == '1') {
            // 성공적으로 전송되었을 때의 처리
            echo "<script>
                alert('SMS가 성공적으로 전송되었습니다.\\n이름: $name | 전화번호: $number | 대기번호: $wait_number');
                window.location.href = 'manage.php';
            </script>";
            $status = "[ 전송 완료 ]";
        } else {
            // 전송 실패 시의 처리
            echo "<script>
                alert('SMS 전송에 실패했습니다.\\n전달된 전화번호: $number | 대기번호: $wait_number\\n메시지: " . mb_convert_encoding($result['message'], 'UTF-8') . "');
                window.location.href = 'manage.php';
            </script>";
            $status = "[ 전송 실패 ]";
        }
    }
    curl_close($ch);

    // SQL 쿼리 준비
    $sql = "UPDATE booking SET monitor=? WHERE call_number=?";
    
    // 쿼리를 위한 prepared statement 준비
    $stmt = mysqli_prepare($mysqli, $sql);
    
    // 파라미터 바인딩
    mysqli_stmt_bind_param($stmt, "ss", $status, $number);
    
    // 쿼리 실행
    if (mysqli_stmt_execute($stmt)) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($mysqli);
    }
    
    // 스테이트먼트와 커넥션 종료
    mysqli_stmt_close($stmt);
    // mysqli_close($mysqli);

    // 기존의 mq 함수가 정의되지 않아서 생략함

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
