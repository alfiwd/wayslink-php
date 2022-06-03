<?php
    include "../services/create-brand.php";
    include "../services/edit-brand.php";

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
?>

<h1>Template</h1>
<div class="manage-brand-container">
    <form action="" method="POST" id="foo" enctype="multipart/form-data">
        <div class="top">
            <h2><?php
                if(isset($_GET["brand"])){
                    echo "Edit Brand";
                }else{
                    echo "Create Brand";
                }
            ?></h2>
            <button type="submit" name="<?php
                if(isset($_GET["brand"])){
                    echo "edit-brand";
                }else{
                    echo "create-brand";
                }
            ?>" class="btn btn-warning btn-publish">
                <?php
                    if(isset($_GET["brand"])){
                        echo "Edit";
                    }else{
                        echo "Publish";
                    }
                ?>
            </button>
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