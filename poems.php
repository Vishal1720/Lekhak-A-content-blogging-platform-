<?php 
session_start();
if(!isset($_SESSION["user_id"]))
{
    header("Location: index.html");
}
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekhak - Poems</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="main">
        <div class="container">
            <h1 class="display-4"><i class="fas fa-pen-fancy"></i> Lekhak</h1>
            <p class="tagline lead">A creative space for writers and storytellers</p>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stories.php">Stories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="poems.php">Poems</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="write1.php">Write</a>
                    </li>
                </ul>
            </div>
            <div class="ms-auto">
                <?php echo "<span id='userpara' class='navbar-text'>$uid</span>"?>
            </div>
        </div>
    </nav>
    
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="section-heading">
                    <i class="fas fa-feather-alt me-2"></i>Featured Poems
                </h2>
                <p class="text-light">Experience the beauty of poetry shared by our talented community.</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <?php
            include 'dbconnect.php';
            $sql="SELECT * FROM `content` WHERE category='poem'";

            $result=$con->query($sql);

            if($result->num_rows>0)
            {
                foreach($result as $row){
                    $head=$row['title'];
                    $content=$row['content'];
                    $writer=$row['userid'];
                    echo '<div class="col-lg-8 col-md-10 mx-auto mb-4">
                        <div class="card story poem shadow">
                            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                                <span class="badge bg-danger px-3 py-2 mb-2 float-end">Poem</span>
                                <h1 class="card-title">'.$head.'</h1>
                                <h2 class="card-subtitle mb-4"><i class="fas fa-user-edit me-2"></i>By '.$writer.'</h2>
                            </div>
                            <div class="card-body pt-0">
                                <p class="card-text poem-content">'.$content.'</p>
                                <div class="mt-4 text-center">
                                    <span class="click-hint"><i class="fas fa-expand-alt me-2"></i>Click to expand/collapse</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            else {
                echo '<div class="col-12 text-center">
                    <div class="alert alert-info py-4">
                        <i class="fas fa-info-circle fa-2x mb-3 d-block"></i>
                        <p class="mb-1">No poems have been shared yet.</p>
                        <a href="write1.php" class="btn btn-primary mt-3">
                            <i class="fas fa-pen-fancy me-2"></i>Write Your Poem
                        </a>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add expandable functionality to poems
        let bool = [];
        var content = document.getElementsByClassName("poem");
        
        for(let i=0; i<content.length; i++) {
            bool[i] = true;
        }
        
        for(let i=0; i<content.length; i++) {
            content[i].addEventListener("click", function() {
                if(bool[i]) {
                    content[i].style.height = "fit-content";
                    bool[i] = false;
                } else {
                    content[i].style.height = "500px";
                    bool[i] = true;
                }
            });
        }
    </script>
</body>
</html>