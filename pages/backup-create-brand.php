<?php
    include "../db/config.php";

    $userId = $_SESSION["id"];

    if(isset($_GET["template"])){
        $template = $_GET["template"];
    }

    if(isset($_GET["brand"])){
        $brand = true;
        $brandId = $_GET["brand"];
        $querySelectBrand = "SELECT * FROM brands WHERE userId = '$userId'";
        $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
        $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);
        
        if($resultSelectBrand > 0){
            $dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand);
            $brandImage = $dataBrand["image"];
            $brandTitle = $dataBrand["title"];
            $brandDescription = $dataBrand["description"];

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
    }

    if(isset($_POST["submit"])){
        // ========== Untuk cek gambar sudah dipilih atau belum ==========
        $brandImage = $_FILES["upload-brand-image"]["name"];
        $linkImage1 = $_FILES["upload-link-image-0"]["name"];
        $linkImage2 = $_FILES["upload-link-image-1"]["name"];
        
        // ========== Jika semua gambar sudah dipilih ==========
        if($brandImage && $linkImage1 && $linkImage2){
            // ========== Untuk membuat random string ==========
            function generateRandomString($length = 10) {
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                return $randomString;
            }
            $randomString = generateRandomString();
            $count = [];

            // ========== Untuk menangkap data gambar yang dipilih ==========
            $namaBrandImage = $_FILES["upload-brand-image"]["name"];
            $tmpBrandImage = $_FILES["upload-brand-image"]["tmp_name"];
            $onlyFileNameBrand = pathinfo($namaBrandImage, PATHINFO_FILENAME) . "-" . $randomString;
            $onlyExtentionBrand = "." . pathinfo($namaBrandImage, PATHINFO_EXTENSION);
            $fullNameBrand = $onlyFileNameBrand . $onlyExtentionBrand;
            $pathBrand = "./uploads/" . $fullNameBrand;

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
                $pathLink = "./uploads/" . $fullNameLink;

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
                echo '<script>alert("Berhasil menambahkan brand");</script>';
            }
        }else{
            echo '<script>alert("Masukkan gambar terlebih dahulu");</script>';
        }
    }
?>
<h1>Template</h1>
<div class="create-brand-container">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="top">
            <h2><?php
                if(isset($_GET["brand"])){
                    echo "Edit Brand";
                }else{
                    echo "Create Brand";
                }
            ?></h2>
            <button type="submit" name="submit" class="btn btn-warning btn-publish">Publish</button>
        </div>
        <div class="bottom">
            <div class="left">
                <img src="<?php
                    if(isset($_GET["brand"])){
                        echo "../uploads/" . $brandImage;
                    }else{
                        echo "../assets/before-upload.png";
                    }
                ?>" alt="before upload" class="before-upload-brand">
                <label for="upload-brand-image" class="btn-upload-brand-image">
                    <span>Upload</span>
                    <input type="file" name="upload-brand-image" onchange="displayBrandImage(this)" id="upload-brand-image" style="display: none">
                </label>
                <div class="brand">
                    <div class="title-brand">
                        <label for="title-brand">Title Brand</label><br>
                        <input type="text" name="title-brand" placeholder="ex. Your Title" <?php
                            if(isset($_GET["brand"])){
                                echo "value='$brandTitle'";
                            }
                        ?> id="title-brand" required>
                    </div>
                    <div class="description-brand">
                        <label for="description-brand">Description</label><br>
                        <input type="text" name="description-brand" placeholder="ex. Your Description Here" <?php
                            if(isset($_GET["brand"])){
                                echo "value='$brandDescription'";
                            }
                        ?> id="description-brand" required>
                    </div>
                </div>
                <?php for($x = 0; $x <= 1; $x++){ ?>
                    <div class="link">
                        <div class="before-upload-container">
                            <img src="<?php
                                if(isset($_GET["brand"])){
                                    echo "../uploads/" . $dataLinks[$x]["image"];
                                }else{
                                    echo "../assets/before-upload.png";
                                }
                            ?>" alt="before upload" class="<?php echo "before-upload-link-$x" ?>">
                            <label for="<?php echo "upload-link-image-$x" ?>" class="btn-upload-link-image">
                                <span>Upload</span>
                                <input type="file" name="<?php echo "upload-link-image-$x" ?>" onchange="<?php echo "displayLinkImage(this, $x)" ?>" id="<?php echo "upload-link-image-$x" ?>" style="display: none">
                            </label>
                        </div>
                        <div class="input-area">
                            <div class="title-link">
                                <div class="input-area">
                                    <label for="<?php echo "title-link-$x" ?>">Title Link</label><br>
                                    <input type="text" <?php
                                        if(isset($_GET["brand"])){
                                            $linkTitle = $dataLinks[$x]["title"];
                                            echo "value='$linkTitle'";
                                        }
                                    ?> placeholder="ex. Your Title" name="<?php echo "title-link-$x" ?>" id="<?php echo "title-link-$x" ?>" required>
                                </div>
                            </div>
                            <div class="url-link">
                                <div class="input-area">
                                    <label for="<?php echo "url-link-$x" ?>">Link</label><br>
                                    <input type="text" <?php
                                        if(isset($_GET["brand"])){
                                            $linkUrl = $dataLinks[$x]["url"];
                                            echo "value='$linkUrl'";
                                        }
                                    ?> placeholder="ex. Your Description Here" name="<?php echo "url-link-$x" ?>" id="<?php echo "url-link-$x" ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="right">
                <?php
                    if(isset($_GET["template"])){
                        $template = $_GET["template"];
                        echo "<img src='../assets/phone-$template.png' />";
                    }
                ?>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    function displayBrandImage(e){
        const filesType = e.files[0].type;
        if(filesType.includes("image")){
            let reader = new FileReader();

            reader.onload = function(e){
                document.querySelector(".before-upload-brand").setAttribute("src", e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }else{
            alert("Masukkan file gambar!");
        }
    }

    function displayLinkImage(e, link){
        const filesType = e.files[0].type;
        if(filesType.includes("image")){
            let reader = new FileReader();

            reader.onload = function(e){
                document.querySelector(`.before-upload-link-${link}`).setAttribute("src", e.target.result);
            }
            reader.readAsDataURL(e.files[0]);
        }else{
            alert("Masukkan file gambar!");
        }
    }
    // const btnPublish = document.getElementById("button-publish");
    // btnPublish.addEventListener("click", function(e){
    //     e.preventDefault();
    //     $("#exampleModal").modal('show');
    // });
    // function OpenBootstrapPopup() {
    //     $("#exampleModal").modal('show');
    // }
</script>