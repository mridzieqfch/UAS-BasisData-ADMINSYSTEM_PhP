<?php
include "service/database.php";
session_start();

$login_message = "";

if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true) {
    header("Location: dashboard/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM tb_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $data['password'])) {
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;

            header("Location: dashboard/index.php");
            exit;
        } else {
            $login_message = "Password salah.";
        }
    } else {
        $login_message = "Akun tidak ditemukan.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            background-image: url('dashboard/img/login.jpg');
            background-size: cover; 
            background-position: center center;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .submit:hover{
            background: rgba(57, 57, 57, 0.937);
            color: #fff;
        } 
        .wrapper{
            background: rgb(186, 46, 46);
            padding: 0 20px 0 20px;
        }

        @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .hero-content {
                animation: fadeIn 1.5s ease-out;
            }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
    <div class="wrapper" style="background:rgba(63, 63, 63, 0); padding: 0 20px 0 20px;">
    <div class="container hero-content main">
        <div class="row" style="background:rgb(0, 0, 0);">
            <div class="col-md-6 side-image">
                <img src="dashboard/img/img_3792.jpg" alt="" style="width: 100%; border-radius:10px; height:88%;">
                <div class="text">
                    <p>Employe Management System <i>- PT. Danta</i></p>
                </div>
            </div>
            <div class="hero-content col-md-6 right">
                <?php if (!empty($login_message)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($login_message); ?>
                </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                <div class="input-box" style="color:rgb(255, 255, 255);">
                    <header style="color:rgb(255, 255, 255);">Log In</header>
                    <div class="input-field">
                        <input style="border-bottom: 1px solid rgb(255, 255, 255); color: #ffffff;" 
                                type="text" class="input" name="username" id="username" required="" autocomplete="off">
                        <label for="username">Username</label> 
                    </div> 
                    <div class="input-field">
                        <input style="border-bottom: 1px solid rgb(255, 255, 255); color: #ffffff;" 
                                type="password" class="input" name="password" id="pass" required="">
                        <label for="pass">Password</label>
                    </div> 
                    <div class="input-field">
                    <input type="submit" class="submit" name="login" value="Log In">
                    </div> 
                </div>  
                </form>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
