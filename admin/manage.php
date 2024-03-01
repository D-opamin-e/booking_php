<?php
include '../db.php'; /* db load */
?>

<html>
<head>
    <title>평화김해뒷고기_관리자</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../assets/m_hurts.css?r=1"/>
    <link href="assets/fontawesome-free-5.9.0-web/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script>
        function checkForUpdates() {
            // 파일의 내용을 비동기적으로 가져옴
            fetch('../signup/module/reservation_success.txt')
                .then(response => response.text())
                .then(data => {
                    if (data === 'Reservation successful') {
                        // 변경이 있을 경우 페이지 새로고침
                        location.reload();
                    }
                });
        }
        // 일정 주기로 checkForUpdates 함수를 호출
        setInterval(checkForUpdates, 5000); // 5초마다 확인
    </script>
</head>

<?php
if (!isset($_SESSION['userid'])) {
    echo "<script>alert('세션이 존재하지 않습니다. 재로그인 부탁드립니다.');</script>";
    echo "<script>document.location.href='index.php';</script>";
    exit;
}
?>

<body class="hurts_body">

<section class="hurts_main_alert">
    <div class="container">
        <br/><br/>
        <section class="hurts_table">
            <div class="container">
                <h1>미발송 대기자 명단</h1><br/>
                <p><a href="logout.php">로그아웃 <i class="fas fa-angle-right"></i></a></p>
                <p><a href="http://kkk234454.duckdns.org/phpmyadmin" target="_blank">phpmyadmin으로 이동 <i class="fas fa-angle-right"></i></a> | <a href="manage_all.php" >대기자 명단 모두 표시 <i class="fas fa-angle-right"></i></a></p>
                <br/>
                <div class="alert alert-danger" role="alert">
                    !주의! 칸을 클릭하면 대기자분에게 바로 문자를 전송합니다.
                </div>
   
</script>

                <?php
                $servername = "localhost";
                $username = "root";
                $password = "DB_password";
                $dbname = "peace_meet";

                // Create connection
                $conn = new mysqli($servername, $username, $password, $dbname);
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM booking ORDER BY number ASC"; // 오름차순으로 변경
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $counter = 1; // 1번부터 시작
                    while ($row = $result->fetch_assoc()) {
                        if ($row["number"] != 0) {
                            echo "<div class=\"hurts_table_box\">";
                            echo "<div class=\"container\">";
                            echo "<h1>대기 상태:" . $row["monitor"] . "</h1>";
                            echo "<a href='call.php?number=" . $row["call_number"] . "&name=" . $row["name"] . "&wait_number=" . $row["number"] . "'>";
                            echo "<h1>성함:" . $row["name"] . "</h1>";
                            echo "<h1>연락처:" . $row["call_number"] . "</h1>";
                            echo "<h1>대기번호:" . $row["number"] . "</h1>";
                            echo "<h1>대기자 등록시간:" . $row["day"] . "</h1></a>";
                            echo "</div>";
                            echo "</div><br />";
                            $counter++;
                        } 
                    }
                } else {
                    echo "<div style=\"text-align: left; font-family: 'nanumsquare'; color: black;\">";
                    echo "대기자가 없습니다.";
                    echo "</div>";
                }

                $conn->close();
                ?>
            </div>
        </section>
    </div>
</section>

<footer class="hurts_footer">
    <div class="container">
        <p>Dopamine 2020 (C) 모든 권한 보유.</p>
        <!-- <p><a href="logout.php">로그아웃 <i class="fas fa-angle-right"></i></a></p> -->
    </div>
</footer>

<!-- JavaScript 코드 수정 -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var hurtsTableBoxes = document.querySelectorAll('.hurts_table_box');
        hurtsTableBoxes.forEach(function (box) {
            box.addEventListener('click', function () {
                var callNumber = encodeURIComponent(box.querySelector('h1:nth-child(2)').innerText.split(':')[1].trim());
                var name = encodeURIComponent(box.querySelector('h1:nth-child(1)').innerText.split(':')[1].trim());
                var number = encodeURIComponent(box.querySelector('h1:nth-child(4)').innerText.split(':')[1].trim());
                
                // manage.php의 일부
                window.location.href = 'call.php?number=' + callNumber + '&name=' + name + '&wait_number=' + number;
            });
        });
    });
</script>

</body>
</html>
