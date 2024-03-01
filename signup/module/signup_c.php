<?php
$mysqli = mysqli_connect("localhost", "root", "DB_password", "peace_meet");

// 데이터베이스에 SQL 쿼리를 전송하고 결과를 반환하는 함수
function mq($sql) {
    global $mysqli;
    return $mysqli->query($sql);
}

session_start();

// 사용자로부터 받아온 성함
$name = isset($_POST['name']) ? $_POST['name'] : '';
$call_number = $_POST['call_number'];
$day = $_POST['day'];

// T 제거
$day_without_T = str_replace('T', ' ', $day);

// 서버의 시간대를 한국 시간대로 설정
date_default_timezone_set('Asia/Seoul');

// $day가 공백인 경우에만 현재 날짜 및 시간을 할당
if (empty($day_without_T)) {
    $timestamp = strtotime("now");
    $day_without_T = date("Y-m-d H:i:s", $timestamp);
}

// number 컬럼에 새로운 데이터의 번호 할당
$sql_number = "SELECT IFNULL(MAX(number), 0) + 1 AS new_number FROM booking";
$result_number = mq($sql_number);
$row_number = $result_number->fetch_assoc();
$new_number = $row_number['new_number'];

// 데이터 추가
$sqlq = "INSERT INTO booking (name, call_number, day, number) VALUES ('" . $name . "','" . $call_number . "', '" . $day_without_T . "', " . $new_number . ")";
$sql = mq($sqlq);

// 전송할 메시지
$message = "$name" . "님, 평화김해뒷고기 율하2지구입니다. $day 대기자 등록이 정상적으로 진행되었습니다! 취소 문의는 010-4415-2391로 부탁드립니다.";

// 알리고문자 API 정보
$api_key = "API_KEY_알리고문자";
$sender_number = "01034872391";

// 전화번호를 국가번호 포함하여 정규화
$normalized_number = $call_number;

// 알리고문자 API URL
$sms_url = "https://apis.aligo.in/send/";

// SMS 전송 요청 데이터
$data = [
    "key" => $api_key,
    "user_id" => "kkk234454",
    "sender" => $sender_number,
    "receiver" => $normalized_number,
    "msg" => $message,
    "testmode_yn" => "N", // N=전송, Y=테스트
];

// cURL을 사용하여 SMS 전송 요청
$ch = curl_init($sms_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL 검증 비활성화

// 응답 받기
$response = curl_exec($ch);

// cURL 에러 확인
if (curl_errno($ch)) {
    echo 'cURL error: ' . curl_error($ch);
} else {
    // 응답 확인 및 처리
    $result = json_decode($response, true);

// 예약 성공 시의 처리
if ($result && isset($result['result_code']) && $result['result_code'] == '1') {
    echo "<script>alert('" . $name . "님, 대기자 등록이 완료되었습니다.\\n대기 번호: " . $new_number . "\\n전화번호: " . $call_number . "\\n예약일: " . $day_without_T . "');</script>";
    echo "<script>document.location.href='../../index.php';</script>";
    file_put_contents("reservation_success.txt", "Reservation successful");
} else {
    // 예약 실패 시의 처리
    echo "<script>alert('" . $name . "님, 대기자 등록이 실패했습니다.\\n예약 번호: " . $new_number . "\\n전화번호: " . $call_number . "\\n예약일: " . $day_without_T . "');</script>";
    echo "<script>document.location.href='../../index.php';</script>";
}
}

// cURL 세션 닫기
curl_close($ch);
?>
