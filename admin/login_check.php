<?php
    session_start();
    $id = $_POST['id'];
    $pw = $_POST['pw'];
    $mysqli = mysqli_connect("localhost","root","DB_password","peace_meet");
    
    $check = "SELECT * FROM admin WHERE userid='$id'";
    $result = $mysqli->query($check); 
    if($result->num_rows==1){
        $row=$result->fetch_array(MYSQLI_ASSOC); //하나의 열을 배열로 가져오기
        if($row['userpw']==$pw){  //MYSQLI_ASSOC 필드명으로 첨자 가능
            $_SESSION['userid']=$id;           //로그인 성공 시 세션 변수 만들기
            if(isset($_SESSION['userid']))    //세션 변수가 참일 때
            {
                header('Location: manage.php');   //로그인 성공 시 페이지 이동
            }
            else{
                echo "<script>alert(\"로그인 세션 저장에 실패하였습니다.\");</script>";
                echo "<script>document.location.href='index.php'; </script>";
            }            
        }
        else{
            echo "<script>alert(\"아이디 또는 비밀번호가 일치하지 않습니다.\");</script>";
            echo "<script>document.location.href='index.php'; </script>";
        }
    }
    else{
        echo "<script>alert(\"아이디 또는 비밀번호가 일치하지 않습니다.\");</script>";
            echo "<script>document.location.href='index.php'; </script>";
    }
?>
