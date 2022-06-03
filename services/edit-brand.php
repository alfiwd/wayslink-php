<?php
    include "../db/config.php";

    $userId = $_SESSION["id"];

    if(isset($_GET["brand"])){
        $brandId = $_GET["brand"];
        $querySelectBrand = "SELECT * FROM brands WHERE id = '$brandId'";
        $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
        $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);
        
        if($resultSelectBrand > 0){
            $dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand);
            $brandImage = $dataBrand["image"];
            $brandTitle = $dataBrand["title"];
            $brandDescription = $dataBrand["description"];
            $brandUniqueLink = $dataBrand["uniqueLink"];

            $querySelectLinks = "SELECT * FROM links WHERE brandId = '$brandId'";
            $executeQuerySelectLinks = mysqli_query($connection, $querySelectLinks);
            $resultSelectLinks = mysqli_num_rows($executeQuerySelectLinks);

            if($resultSelectLinks > 0){
                $dataLinks = [];
                while($dataLink = mysqli_fetch_assoc($executeQuerySelectLinks)){
                    array_push($dataLinks, $dataLink);
                }
            }
        }

        if(isset($_POST["edit-brand"])){
            $newBrandImage = $_FILES["upload-brand-image"]["name"];
            $newLinkImage1 = $_FILES["upload-link-image-0"]["name"];
            $newLinkImage2 = $_FILES["upload-link-image-1"]["name"];

            date_default_timezone_set('Asia/Jakarta');
            $dateNow = date('Y-m-d H:i:s');
            $randomString = generateRandomString();

            $brandTitle = $_POST["title-brand"];
            $brandDescription = $_POST["description-brand"];

            function getNewImage($imageName, $imageFile, $brandUniqueLink){
                $newTempImage = $_FILES["$imageName"]["tmp_name"];
                $onlyNewFileName = pathinfo($imageFile, PATHINFO_FILENAME) . "-" . $brandUniqueLink;
                $onlyNewExtention = "." . pathinfo($imageFile, PATHINFO_EXTENSION);
                $newFullName = $onlyNewFileName . $onlyNewExtention;
                $path = "../uploads/" . $newFullName;

                $result = [];

                $result["temp"] = $newTempImage;
                $result["full-name"] = $newFullName;
                $result["path"] = $path;

                return $result;
            }

            if($newBrandImage && !$newLinkImage1 && !$newLinkImage2){                
                $fileBrandImage = getNewImage("upload-brand-image", $newBrandImage, $brandUniqueLink);

                $targetDeleteBrandImage = "../uploads/" . $brandImage;
                
                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', image = '{$fileBrandImage["full-name"]}', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                        $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                    }

                    unlink($targetDeleteBrandImage);
                    move_uploaded_file($fileBrandImage["temp"], $fileBrandImage["path"]);

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if($newBrandImage && $newLinkImage1 && !$newLinkImage2){
                $fileBrandImage = getNewImage("upload-brand-image", $newBrandImage, $brandUniqueLink);
                $fileLinkImage1 = getNewImage("upload-link-image-0", $newLinkImage1, $brandUniqueLink);

                $targetDeleteBrandImage = "../uploads/" . $brandImage;
                $targetDeleteLinkImage1 = "../uploads/" . $dataLinks[0]["image"];

                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', image = '{$fileBrandImage["full-name"]}', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 0){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage1["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage1);
                                move_uploaded_file($fileLinkImage1["temp"], $fileLinkImage1["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                        }
                    }

                    unlink($targetDeleteBrandImage);
                    move_uploaded_file($fileBrandImage["temp"], $fileBrandImage["path"]);

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if($newBrandImage && !$newLinkImage1 && $newLinkImage2){
                $fileBrandImage = getNewImage("upload-brand-image", $newBrandImage, $brandUniqueLink);
                $fileLinkImage2 = getNewImage("upload-link-image-1", $newLinkImage2, $brandUniqueLink);

                $targetDeleteBrandImage = "../uploads/" . $brandImage;
                $targetDeleteLinkImage2 = "../uploads/" . $dataLinks[1]["image"];

                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', image = '{$fileBrandImage["full-name"]}', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 1){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage2["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage2);
                                move_uploaded_file($fileLinkImage2["temp"], $fileLinkImage2["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                        }
                    }

                    unlink($targetDeleteBrandImage);
                    move_uploaded_file($fileBrandImage["temp"], $fileBrandImage["path"]);

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if(!$newBrandImage && $newLinkImage1 && !$newLinkImage2){
                $fileLinkImage1 = getNewImage("upload-link-image-0", $newLinkImage1, $brandUniqueLink);

                $targetDeleteLinkImage1 = "../uploads/" . $dataLinks[0]["image"];
                
                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 0){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage1["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage1);
                                move_uploaded_file($fileLinkImage1["temp"], $fileLinkImage1["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                        }
                    }

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if(!$newBrandImage && $newLinkImage1 && $newLinkImage2){
                $fileLinkImage1 = getNewImage("upload-link-image-0", $newLinkImage1, $brandUniqueLink);
                $fileLinkImage2 = getNewImage("upload-link-image-1", $newLinkImage2, $brandUniqueLink);

                $targetDeleteLinkImage1 = "../uploads/" . $dataLinks[0]["image"];
                $targetDeleteLinkImage2 = "../uploads/" . $dataLinks[1]["image"];
                
                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 0){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage1["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage1);
                                move_uploaded_file($fileLinkImage1["temp"], $fileLinkImage1["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage2["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage2);
                                move_uploaded_file($fileLinkImage2["temp"], $fileLinkImage2["path"]);
                            }
                        }
                    }

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if(!$newBrandImage && !$newLinkImage1 && $newLinkImage2){
                $fileLinkImage2 = getNewImage("upload-link-image-1", $newLinkImage2, $brandUniqueLink);

                $targetDeleteLinkImage2 = "../uploads/" . $dataLinks[1]["image"];
                
                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 1){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage2["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage2);
                                move_uploaded_file($fileLinkImage2["temp"], $fileLinkImage2["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                        }
                    }

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if($newBrandImage && $newLinkImage1 && $newLinkImage2){
                $fileBrandImage = getNewImage("upload-brand-image", $newBrandImage, $brandUniqueLink);
                $fileLinkImage1 = getNewImage("upload-link-image-0", $newLinkImage1, $brandUniqueLink);
                $fileLinkImage2 = getNewImage("upload-link-image-1", $newLinkImage2, $brandUniqueLink);

                $targetDeleteBrandImage = "../uploads/" . $brandImage;
                $targetDeleteLinkImage1 = "../uploads/" . $dataLinks[0]["image"];
                $targetDeleteLinkImage2 = "../uploads/" . $dataLinks[1]["image"];

                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', image = '{$fileBrandImage["full-name"]}', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        if($i == 0){
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage1["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage1);
                                move_uploaded_file($fileLinkImage1["temp"], $fileLinkImage1["path"]);
                            }
                        }else{
                            $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', image = '{$fileLinkImage2["full-name"]}', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                            $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);

                            if($executeUpdateLinks == 1){
                                unlink($targetDeleteLinkImage2);
                                move_uploaded_file($fileLinkImage2["temp"], $fileLinkImage2["path"]);
                            }
                        }
                    }

                    unlink($targetDeleteBrandImage);
                    move_uploaded_file($fileBrandImage["temp"], $fileBrandImage["path"]);

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }else if(!$newBrandImage && !$newLinkImage1 && !$newLinkImage2){
                $queryUpdateBrand = "UPDATE brands SET title = '$brandTitle', description = '$brandDescription', updatedAt = '$dateNow' WHERE id = '$brandId' AND userId = '$userId'";
                $executeUpdateBrand = mysqli_query($connection, $queryUpdateBrand);

                if($executeUpdateBrand == 1){
                    for($i = 0; $i < count($dataLinks); $i++){
                        $linkId = $dataLinks[$i]["id"];
                        $linkBrandId = $dataLinks[$i]["brandId"];
                        $linkTitle = $_POST["title-link-$i"];
                        $linkUrl = $_POST["url-link-$i"];
    
                        $queryUpdateLinks = "UPDATE links SET title = '$linkTitle', url = '$linkUrl', updatedAt = '$dateNow' WHERE id = '$linkId' AND brandId = '$brandId'";
                        $executeUpdateLinks = mysqli_query($connection, $queryUpdateLinks);
                    }

                    echo '<script>
                        alert("Data brand berhasil diubah");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=my-link";
                    </script>';
                }
            }
        }
    }
?>