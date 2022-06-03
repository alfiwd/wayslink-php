<?php
    include "../db/config.php";

    $userId = $_SESSION["id"];
    $querySelectBrand = "SELECT * FROM brands WHERE userId = '$userId'";
    $executeQuerySelectBrand = mysqli_query($connection, $querySelectBrand);
    $resultSelectBrand = mysqli_num_rows($executeQuerySelectBrand);
    $dataBrands = [];

    if($resultSelectBrand > 0){
        while($dataBrand = mysqli_fetch_assoc($executeQuerySelectBrand)){
            array_push($dataBrands, $dataBrand);
        }
    }
?>

<h1>My Link</h1>
<div class="my-link-container">
    <div class="search">
        <div class="left">
            <h2>All Links</h2>
            <div class="total-link">
                <?php
                    $total = count($dataBrands);
                    echo "<span>$total</span>"
                ?>
            </div>
        </div>
        <div class="center">
            <div class="search-area">
                <img src="../assets/search-icon.png" alt="search icon">
                <input type="text" placeholder="Find your link">
            </div>
        </div>
        <div class="right">
            <button type="button" class="btn btn-warning btn-search">Search</button>
        </div>
    </div>
    <?php for($i = 0; $i < count($dataBrands); $i++){ ?>
        <?php
            $brandId = $dataBrands[$i]["id"];
            $image = $dataBrands[$i]["image"];
            $titleBrand = $dataBrands[$i]["title"];
            $uniqueLink = $dataBrands[$i]["uniqueLink"];
            $viewCount = $dataBrands[$i]["viewCount"];   
            $template = $dataBrands[$i]["template"]; 
        ?>
        <div class="brands">
            <div class="left">
                <img src="<?php echo "../uploads/$image" ?>" alt="brand image">
                <div class="brand-name">
                    <?php echo "<h3>$titleBrand</h3>" ?>
                    <?php echo "<p>$uniqueLink</p>" ?>
                </div>
            </div>
            <div class="center">
                <div class="total-visit">
                    <?php echo "<h3>$viewCount</h3>" ?>
                    <p>Visit</p>
                </div>
            </div>
            <div class="right">
                <form action="" method="POST" >
                    <a href="<?php echo "output.php?uniqueLink=$uniqueLink" ?>" class="show-brand" target="_blank">
                        <img src="../assets/view-button.png" alt="view button">
                    </a>
                    <a href="<?php echo "dashboard.php?page=template&template=$template&brand=$brandId" ?>" class="<?php echo "btn-edit" ?>" >
                        <img src="../assets/edit-button.png" class="edit-btn" alt="edit button">
                    </a>
                    <a class="<?php echo "btn-delete" ?>" onclick="<?php echo "handleButtonDelete('$brandId', '$titleBrand')" ?>" name="<?php echo "btn-delete-$i" ?>">
                        <img src="../assets/delete-button.png" alt="delete button">
                    </a>
                </form>
            </div>
        </div>
    <?php } ?>    
</div>

<script>
    const btnSearch = document.querySelector(".btn-search");

    btnSearch.addEventListener("click", function(){
        alert("Coming soon! :D");
    });

    const handleButtonDelete = (id, title) => {
        if(confirm(`Apakah yakin ingin menghapus brand ===> ${title} ?`)){
            window.location.href = `../services/delete-brand.php?delete=${id}`;
        }
    }
</script>