<?php
    session_start();

    if(!isset($_SESSION["id"])){
        $page = "../index.php";
        header("Location: index.php");
        // header("Location: $page");
    }

    if(isset($_GET["page"])){
        $page = $_GET["page"];
        $replacePageName = str_replace("-", " ", $page);
        $display = ucwords($replacePageName);
        echo "<title>Halaman $display</title>";
    }

    // session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <script src="https://kit.fontawesome.com/b095f3f105.js" crossorigin="anonymous"></script>

    <!-- My CSS -->
    <link rel="stylesheet" href="../style.css">
    <title>Halaman Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="top">
                <img src="../assets/logo.png" alt="logo">
                <div class="menu">
                    <div class="template">
                        <a href="dashboard.php?page=template">
                            <?php
                                if(isset($_GET["page"])){
                                    $page = $_GET["page"];
                                    if($page === "template"){
                                        echo "<i class='fa fa-cubes' style='color: #FF9F00'></i><span style='color: #FF9F00'>Template</span>";
                                    }else{
                                        echo "<i class='fa fa-cubes'></i><span>Template</span>";
                                    }
                                }else if(isset($_GET["template"])){
                                    $template = $_GET["template"];
                                    if($template === "1" || $template === "2" || $template === "3"){
                                        echo "<i class='fa fa-cubes' style='color: #FF9F00'></i><span style='color: #FF9F00'>Template</span>";
                                    }
                                }
                            ?>
                        </a>
                    </div>
                    <div class="profile">
                        <a href="dashboard.php?page=profile">
                            <?php
                                if(isset($_GET["page"])){
                                    $page = $_GET["page"];
                                    if($page === "profile"){
                                        echo "<i class='fa-regular fa-circle-user' style='color: #FF9F00'></i><span style='color: #FF9F00'>Profile</span>";
                                    }else{
                                        echo "<i class='fa-regular fa-circle-user'></i><span>Profile</span>";
                                    }
                                }else if(isset($_GET["template"])){
                                    $template = $_GET["template"];
                                    if($template === "1" || $template === "2" || $template === "3"){
                                        echo "<i class='fa fa-cubes' style='color: #FF9F00'></i><span style='color: #FF9F00'>Profile</span>";
                                    }
                                }
                            ?>
                        </a>
                    </div>
                    <div class="my-link">
                        <a href="dashboard.php?page=my-link">
                            <?php
                                if(isset($_GET["page"])){
                                    $page = $_GET["page"];
                                    if($page === "my-link"){
                                        echo "<i class='fa fa-link' style='color: #FF9F00'></i><span style='color: #FF9F00'>My Link</span>";
                                    }else{
                                        echo "<i class='fa fa-link'></i><span>My Link</span>";
                                    }
                                }else if(isset($_GET["template"])){
                                    $template = $_GET["template"];
                                    if($template === "1" || $template === "2" || $template === "3"){
                                        echo "<i class='fa fa-cubes' style='color: #FF9F00'></i><span style='color: #FF9F00'>My Link</span>";
                                    }
                                }
                            ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="logout">
                    <a href="../services/logout.php">
                        <i class="fa fa-arrow-right-from-bracket"></i><span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="content">
            <?php
                if(isset($_GET["page"]) && isset($_GET["template"])){
                    $template = $_GET["template"];
                    if($template){
                        include "manage-brand.php";
                    }else{
                        echo "<center><h1>Halaman tidak ditemukan!</h1></center>";
                    }
                }else if(isset($_GET["page"])){
                    $page = $_GET["page"];
                    switch($page){
                        case "template" : 
                            include "template.php";
                            break;
                        case "profile" :
                            include "profile.php";
                            break;
                        case "my-link" :
                            include "my-link.php";
                            break;
                        default :
                            echo "<center><h1>Halaman tidak ditemukan!</h1></center>";      
                    }
                }else{
                    header("Location: dashboard.php?page=template");
                }
            ?>
        </div>
    </div>
</body>
</html>