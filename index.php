<?php
    include "./db/config.php";

    session_start();

    if(isset($_SESSION["id"])){
        $page = "pages/dashboard.php?page=template";
        
        // header("Location: dashboard.php?page=template");
        header("Location: ./pages/dashboard.php?page=template");
    }

    if(isset($_POST["submit-login"])){
        $email = $_POST["email"];
        $password = $_POST["password"];

        $query = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email' AND password = '$password'");
        $result = mysqli_num_rows($query);
        
        if($result == 1){
            $data = mysqli_fetch_assoc($query);
            $_SESSION["id"] = $data["id"];
            $_SESSION["email"] = $data["email"];
            $page = "./pages/dashboard.php?page=template";
            
            // header("Location: dashboard.php?page=template");
            header("Location: ./pages/dashboard.php?page=template");
        }else{
            echo '<script>alert("Email atau password salah!");</script>';
        }
    }else if(isset($_POST["submit-register"])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $fullName = $_POST["full-name"];
        $date = date('Y-m-d H:i:s');
        
        $query = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email'");
        $result = mysqli_num_rows($query);
        
        if($result == 0){
            $query = "INSERT INTO users (email, password, fullName, createdAt, updatedAt) VALUES ('$email', '$password', '$fullName', '$date', '$date')";
            $result = mysqli_query($connection, $query);

            if($result == 1){
                echo '<script>alert("Berhasil mendaftarkan akun\nSilakan dapat melakukan login");</script>';
            }else{
                echo '<script>alert("Oops terjadi kesalahan :(");</script>';
            }
        }else{
            echo '<script>alert("Email sudah terdaftar!");</script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- My CSS -->
    <link rel="stylesheet" href="style.css">

    <title>UAS</title>
</head>
<body>
    <div class="landing-page">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid px-5">
                <a class="navbar-brand" href="#">
                    <img src="./assets/logo.png" alt="logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <div class="ms-auto">
                        <button type="button" class="btn me-2" data-bs-toggle="modal" data-bs-target="#modal-login">Login</button>
                        <button type="button" class="btn btn-warning btn-register" data-bs-toggle="modal" data-bs-target="#modal-register">Register</button>
                    </div>
                </div>
            </div>
        </nav>
        <div class="content">
            <div class="row">
                <div class="col left-side">
                    <div>
                        <h1>The Only Link<br>You’ll Ever Need</h1>
                        <p>Add a link for your<br>Social Bio and optimize your social media traffic.</p>
                        <p>safe, fast and easy to use</p>
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#modal-register">Get Started For Free</button>
                    </div>
                </div>
                <div class="col right-side">
                    <div>
                        <img src="./assets/phone-1.png" alt="phone" class="phone-image">
                        <img src="./assets/pc.png" alt="pc">
                    </div>
                </div>
            </div>
        </div>

        <!-- ======================================== Modal ======================================== -->
        <div class="modal fade" id="modal-login" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h1>Login</h1>
                        <form action="" method="POST" >
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" aria-describedby="emailHelp" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" id="exampleInputPassword1" required>
                            </div>
                            <button name="submit-login" class="btn btn-warning btn-login w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-register" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <h1>Register</h1>
                        <form action="" method="POST" >
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email" aria-describedby="emailHelp" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Password" id="exampleInputPassword1" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="full-name" class="form-control" placeholder="Full Name" id="exampleInputPassword1" required>
                            </div>
                            <button name="submit-register" class="btn btn-warning btn-register w-100">Register</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>    
</body>
</html>