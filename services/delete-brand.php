<?php
    include "../db/config.php";

    session_start();

    if(isset($_GET["delete"])){
        $brandId = $_GET["delete"];
        $userId = $_SESSION["id"];
        $querySelectBrand = "SELECT * FROM brands WHERE id = '$brandId' AND userId = '$userId'";
        $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
        $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);

        if($resultSelectBrand > 0){
            $querySelectLink = "SELECT * FROM links WHERE brandId = '$brandId'";
            $executeQuerySelectLink = mysqli_query($connection, $querySelectLink);
            $resultSelectLink = mysqli_num_rows($executeQuerySelectLink);
            
            if($resultSelectLink > 0){
                $dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand);
                $targetDeleteAllImage = ["../uploads/" . $dataBrand["image"]];

                while($dataLink = mysqli_fetch_assoc($executeQuerySelectLink)){
                    array_push($targetDeleteAllImage, "../uploads/" . $dataLink["image"]);
                }
                
                if(count($targetDeleteAllImage) == 3){
                    $fileExists = [];

                    for($i = 0; $i < count($targetDeleteAllImage); $i++){
                        if(file_exists($targetDeleteAllImage[$i])){
                            array_push($fileExists, true);
                        }
                    }

                    if(count($fileExists) == 3){
                        $queryDeleteLink = "DELETE FROM links WHERE brandId = '$brandId'";
                        $resultDeleteLinks = mysqli_query($connection, $queryDeleteLink);

                        if($resultDeleteLinks == 1){
                            $queryDeleteBrand = "DELETE FROM brands WHERE id = '$brandId' AND userId = '$userId'";
                            $resultDeleteBrand = mysqli_query($connection, $queryDeleteBrand);

                            if($resultDeleteBrand == 1){
                                for($i = 0; $i < count($targetDeleteAllImage); $i++){
                                    unlink($targetDeleteAllImage[$i]);
                                }
                                echo '<script>
                                    alert("Berhasil menghapus brand");
                                    window.location.href = "../pages/dashboard.php?page=my-link";
                                </script>';
                            }
                        }
                    }else{
                        echo '<script>
                            alert("Gagal menghapus brand");
                        </script>';
                    }
                }
            }
        }
    }
?>