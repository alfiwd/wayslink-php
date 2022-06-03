<?php
    include "../db/config.php";

    if(isset($_GET["uniqueLink"])){
        $uniqueLink = $_GET["uniqueLink"];

        $querySelectBrand = "SELECT * FROM brands WHERE uniqueLink = '$uniqueLink'";
        $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
        $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);

        if($resultSelectBrand > 0){
            $dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand);
            $brandId = $dataBrand["id"];
            $titleBrand = $dataBrand["title"];
            $descriptionBrand = $dataBrand["description"];
            $brandImage = $dataBrand["image"];
            $viewCount = (int)$dataBrand["viewCount"];
            $viewCountUpdated = $viewCount + 1;

            $querySelectLinks = "SELECT * FROM links WHERE brandId = '$brandId'";
            $executeQuerySelectLinks = mysqli_query($connection, $querySelectLinks);
            $resultSelectLinks = mysqli_num_rows($executeQuerySelectLinks);
            $datalinks = [];

            if($resultSelectLinks > 0){
                while($datalink = mysqli_fetch_assoc($executeQuerySelectLinks)){
                    array_push($datalinks, $datalink);
                }
            }

            date_default_timezone_set('Asia/Jakarta');
            $dateNow = date('Y-m-d H:i:s');
            
            $queryUpdateBrand = "UPDATE brands SET viewCount = '$viewCountUpdated', updatedAt = '$dateNow' WHERE id = '$brandId'";
            $executeQuerySelectBrand = mysqli_query($connection, $queryUpdateBrand);
        }else{
            header("Location: 404-not-found.php");
        }
    }else{
        header("Location: 404-not-found.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="../style.css">
    <title><?php echo $titleBrand ?></title>
</head>
<body>
    <div class="output-container">
        <div class="brand">
            <img src="<?php echo "../uploads/$brandImage" ?>" alt="brand logo">
            <h1><?php echo $titleBrand ?></h1>
            <p><?php echo $descriptionBrand ?></p>
        </div>
        <div class="links">
            <?php for($i = 0; $i < count($datalinks); $i++){ ?>
                <?php
                    $url = $datalinks[$i]["url"];
                    $image = $datalinks[$i]["image"];    
                    $title = $datalinks[$i]["title"];    
                ?>
                <a href="<?php echo $url ?>" target="_blank">
                    <div class="link">
                        <img src="<?php echo "../uploads/$image" ?>" alt="facebook">
                        <p><?php echo $title ?></p>
                    </div>
                </a>
            <?php } ?>    
        </div>
    </div>
</body>
</html>