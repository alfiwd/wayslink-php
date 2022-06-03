<?php
    include "../db/config.php";

    $userId = $_SESSION["id"];

    if(isset($_GET["template"])){
        $template = $_GET["template"];
    }

    if(isset($_POST["create-brand"])){
        // ========== Untuk cek gambar sudah dipilih atau belum ==========
        $brandImage = $_FILES["upload-brand-image"]["name"];
        $linkImage1 = $_FILES["upload-link-image-0"]["name"];
        $linkImage2 = $_FILES["upload-link-image-1"]["name"];
        
        // ========== Jika semua gambar sudah dipilih ==========
        if($brandImage && $linkImage1 && $linkImage2){
            // ========== Untuk memanggil random string ==========
            $randomString = generateRandomString();
            $count = [];

            // ========== Untuk menangkap data gambar yang dipilih ==========
            $namaBrandImage = $_FILES["upload-brand-image"]["name"];
            $tmpBrandImage = $_FILES["upload-brand-image"]["tmp_name"];
            $onlyFileNameBrand = pathinfo($namaBrandImage, PATHINFO_FILENAME) . "-" . $randomString;
            $onlyExtentionBrand = "." . pathinfo($namaBrandImage, PATHINFO_EXTENSION);
            $fullNameBrand = $onlyFileNameBrand . $onlyExtentionBrand;
            $pathBrand = "../uploads/" . $fullNameBrand;

            // ========== Untuk memindahkan gambar brand dari temporary memory ke folder upload ==========
            if(move_uploaded_file($tmpBrandImage, $pathBrand)){
                array_push($count, "success-upload-brand-image");
            }else{
                array_splice($count, 0, 1);
            }

            $tmpLinkImages = [];
            $pathLinks = [];
            $fullNameLinks = [];

            // ========== Untuk memindahkan gambar link dari temporary memory ke folder upload ==========
            for($i = 0; $i <= 1; $i++){
                $namaLinkImage = $_FILES["upload-link-image-$i"]["name"];
                $tmpLinkImage = $_FILES["upload-link-image-$i"]["tmp_name"];
                $onlyFileNameLink = pathinfo($namaLinkImage, PATHINFO_FILENAME) . "-" . $randomString;
                $onlyExtentionLink = "." . pathinfo($namaLinkImage, PATHINFO_EXTENSION);
                $fullNameLink = $onlyFileNameLink . $onlyExtentionLink;
                $pathLink = "../uploads/" . $fullNameLink;

                if(move_uploaded_file($tmpLinkImage, $pathLink)){
                    array_push($count, "success-upload-link-image-$i");
                }else{
                    array_splice($count, $i, 1);
                }
                array_push($tmpLinkImages, $tmpLinkImage);
                array_push($pathLinks, $pathLink);
                array_push($fullNameLinks, $fullNameLink);
            }

            // ========== Untuk insert data brand ==========
            $titleBrand = $_POST["title-brand"];
            $descriptionBrand = $_POST["description-brand"];
            date_default_timezone_set('Asia/Jakarta');
            $date = date('Y-m-d H:i:s');
            $queryInsertBrand =
                "INSERT INTO brands (
                    title, description, image, uniqueLink, viewCount, userId, template, createdAt, updatedAt
                ) VALUES (
                    '$titleBrand', '$descriptionBrand', '$fullNameBrand', '$randomString', 0, '$userId', '$template', '$date', '$date'
                )";
            $resultInsertBrand = mysqli_query($connection, $queryInsertBrand);

            if($resultInsertBrand == 1){
                array_push($count, "success-upload-brand");

                // ========== Untuk select data brand ==========
                $querySelectBrand = "SELECT * FROM brands WHERE userId = '$userId' AND createdAt = '$date'";
                $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
                $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);

                if($resultSelectBrand > 0){
                    $dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand);
                    $brandId = $dataBrand["id"];
                     
                    // ========== Untuk insert data link ==========
                    for($i = 0; $i <= 1; $i++){
                        $titleLink = $_POST["title-link-$i"];
                        $urlLink = $_POST["url-link-$i"];

                        $queryInsertLink =
                            "INSERT INTO links (
                                title, url, image, brandId, createdAt, updatedAt
                            ) VALUES (
                                '$titleLink', '$urlLink', '$fullNameLinks[$i]', '$brandId', '$date', '$date'
                            )";
                        $resultInsertLink = mysqli_query($connection, $queryInsertLink);
                        
                        if($resultInsertLink == 1){
                            array_push($count, "success-upload-link-$i");
                        }else{
                            array_splice($count, $i, 1);
                            echo '<script>alert("Error insert link");</script>';
                        }
                    }
                }else{
                    echo '<script>alert("Error select brand");</script>';
                }
            }else{
                array_splice($count, 0, 1);
                echo '<script>alert("Error insert brand");</script>';
            }

            if(count($count) == 6){
                echo '<script>
                    alert("Berhasil menambahkan brand");
                    window.location.href = "dashboard.php?page=my-link";
                </script>';
            }
        }else{
            echo '<script>alert("Masukkan gambar terlebih dahulu");</script>';
        }
    }
?>