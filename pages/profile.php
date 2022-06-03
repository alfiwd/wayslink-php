<?php
    include "../db/config.php";

    $userId = $_SESSION["id"];
    $querySelectUser = "SELECT * FROM users WHERE id = '$userId'";
    $executeQuerySelectUser = mysqli_query($connection, $querySelectUser);
    $resultSelectUser = mysqli_num_rows($executeQuerySelectUser);

    if($resultSelectUser > 0){
        $dataUser = mysqli_fetch_assoc($executeQuerySelectUser);
        $fullName = $dataUser["fullName"];
        $email = $dataUser["email"];

        if(isset($_POST["btn-save"])){
            $fullNameInput = $_POST["name"];
            $emailInput = $_POST["email"];

            if($fullNameInput != "" && $emailInput != ""){
                date_default_timezone_set('Asia/Jakarta');
                $dateNow = date('Y-m-d H:i:s');
                
                $queryUpdateUser = "UPDATE users SET fullName = '$fullNameInput', email = '$emailInput', updatedAt = '$dateNow' WHERE id = '$userId'";
                $executeQueryUpdateUser = mysqli_query($connection, $queryUpdateUser);
    
                if($executeQueryUpdateUser > 0){
                    echo '<script>
                        alert("Data akun berhasil diubah");
                        window.location.href = "dashboard.php?page=profile";
                    </script>';
                }else{
                    echo '<script>
                        alert("Oops terjadi kesalahan dari server");
                        window.location.href = "dashboard.php?page=profile";
                    </script>';
                }
            }

        }else if(isset($_POST["btn-delete"])){
            
        }
    }
?>

<h1>Profile</h1>
<div class="profile-container">
    <h2>My Information</h2>
    <form action="" method="POST" >
        <div class="form">
            <div class="name">
                <label for="name">Name</label><br>
                <input type="text" name="name" id="name" value="<?php echo $fullName ?>" required>
            </div>
            <div class="email">
                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" value="<?php echo $email ?>" required>
            </div>
        </div>
        <div class="button">
            <button name="btn-save" class="btn btn-warning btn-save me-2">Save Account</button>
            <button name="btn-delete" class="btn btn-danger btn-delete">Delete Account</button>
        </div>
    </form>
</div>

<script>
    const btnDelete = document.querySelector(".btn-delete");

    btnDelete.addEventListener("click", function(){
        alert("Coming soon! :D");
    })
</script>