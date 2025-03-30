<?php 
session_start();
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekhak - Home</title>
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
                        <a class="nav-link active" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="stories.php">Stories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="poems.php">Poems</a>
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
                    <i class="fas fa-book me-2"></i>Stories
                </h2>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $servername="localhost";
            $username="root";
            $pass="";

            $con=mysqli_connect($servername,$username,$pass);
            
            $sql="SELECT * FROM `blogger`.`content` WHERE category='story'";
            $res=$con->query($sql);

            if($res->num_rows>0){
                foreach($res as $row){
                    $storytitle=$row['title'];
                    $storydesc=$row['descr'];
                    $uid=$row['userid'];
                    echo '
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card shadow h-100 story-preview">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 class="card-title">'.$storytitle.'</h3>
                                    <span class="badge bg-primary px-2 py-1 rounded-pill">Story</span>
                                </div>
                                <p class="card-subtitle mb-3"><i class="fas fa-user-edit me-1"></i>By '.$uid.'</p>
                                <p class="card-text flex-grow-1">'.$storydesc.'</p>
                                <a href="stories.php" class="btn btn-outline-primary mt-auto">
                                    <i class="fas fa-book-open me-1"></i>Read More
                                </a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-light alert alert-info">No stories found. Be the first to write one!</p></div>';
            }
            ?>
        </div>
        
        <div class="row mt-5 mb-4">
            <div class="col-12">
                <h2 class="section-heading">
                    <i class="fas fa-feather-alt me-2"></i>Poems
                </h2>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $sql="SELECT * FROM `blogger`.`content` WHERE category='poem'";
            $result=$con->query($sql);
            
            if($result->num_rows > 0) {
                foreach($result as $rows) {
                    $title=$rows['title'];
                    $description=$rows['descr'];
                    $uid=$rows['userid'];
                    echo '
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card shadow h-100 poem-preview">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h3 class="card-title">'.$title.'</h3>
                                    <span class="badge bg-danger px-2 py-1 rounded-pill">Poem</span>
                                </div>
                                <p class="card-subtitle mb-3"><i class="fas fa-user-edit me-1"></i>By '.$uid.'</p>
                                <p class="card-text flex-grow-1">'.$description.'</p>
                                <a href="poems.php" class="btn btn-outline-danger mt-auto">
                                    <i class="fas fa-feather me-1"></i>Read More
                                </a>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-light alert alert-info">No poems found. Share your creativity!</p></div>';
            }
            ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center">
                <a href="write1.php" class="btn btn-primary btn-lg shadow">
                    <i class="fas fa-pen-fancy me-2"></i>Start Writing
                </a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>