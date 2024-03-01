<!DOCTYPE html>
<html lang="ko">
<head>
    <title>평화김해뒷고기 율하2지구</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="assets/login_m_hurts.css?r=2"/>
    <link href="assets/fontawesome-free-5.9.0-web/css/all.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        // 폼 제출 전 검증 함수
        function validateForm() {
            var name = document.getElementById('name').value;
            if (name.trim() === "") {
                alert("성함은 공백일 수 없습니다.");
                return false;
            }
            return true; // 모든 검증 통과 시
        }
    </script>
</head>
<body class="hurts_body">
    <section class="hurts_login">
        <div class="container">
            <h1>평화김해뒷고기 율하2지구 대기자 등록</h1><br />
            <!-- 약관 -->
            <?php include("signup/module/TOS.php") ?>
            <form method="post" action="signup/module/signup_c.php" onsubmit="return validateForm()">
                <div class="input-group mb-3 login_max_width">
                    <small>성함</small>
                    <input type="text" style="text-align: center;" id="name" name="name" autocomplete="off" class="hurts_input" placeholder="성함" aria-label="성함" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-3 login_max_width">
                    <small>연락처 | 입장 가능시 연락을 받으실 번호를 입력해주세요.</small>
                    <input type="number" style="text-align: center;" id="call_number" name="call_number" autocomplete="off" class="hurts_input" placeholder="연락처" aria-label="연락처" aria-describedby="basic-addon1" oninput="validatePhoneNumber(this)">
                </div>
                <?php
                // 현재 한국 시간을 얻어옴
                date_default_timezone_set('Asia/Seoul');
                $current_korea_time = date('Y-m-d H:i');
                ?>
                <div class="input-group mb-3 login_max_width">
                    <input type="text" id="day" name="day" autocomplete="off" readonly value="<?php echo $current_korea_time; ?>">
                </div>
                <div class="input-group mb-3 login_max_width">
                    <button type="submit" class="hurts_btn btn-outline-dark">예약하기 <i class="fas fa-angle-right"></i></button>
                </div>
            </form>
        </div>
    </section>
    <footer class="hurts_footer">
        <div class="container">
            <p>Dopamine 2020 (C) 모든 권한 보유.</p>
        </div>
    </footer>
</body>
</html>
