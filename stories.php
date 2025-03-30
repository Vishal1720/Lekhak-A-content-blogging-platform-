<?php 
session_start();
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekhak - Stories</title>
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
                        <a class="nav-link active" href="stories.php">Stories</a>
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
                    <i class="fas fa-book me-2"></i>Featured Stories
                </h2>
                <p class="text-light">Immerse yourself in these captivating stories shared by our community.</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            include 'dbconnect.php';
          
                
                $sql="SELECT * FROM `content` WHERE category='story'";
                $stmt=$con->prepare($sql);
                $stmt->execute();
                $res=$stmt->get_result();
                if($res->num_rows>0)
                {
                  foreach($res as $row)
                  {
                    $heading=$row['title'];
                    $content=$row['content'];
                    $user=$row['userid'];
                    echo "<div class='col-lg-10 col-md-12 mx-auto mb-4'>
                        <div class='story card shadow'>
                            <div class='card-header bg-transparent border-0 pt-4 pb-0'>
                                <span class='badge bg-primary px-3 py-2 mb-2 float-end'>Story</span>
                                <h1 class='card-title'>{$heading}</h1>
                                <h2 class='card-subtitle mb-4'><i class='fas fa-user-edit me-2'></i>By {$user}</h2>
                            </div>
                            <div class='card-body pt-0'>
                                <p class='card-text'>{$content}</p>
                                <div class='mt-4 text-center'>
                                    <span class='click-hint'><i class='fas fa-expand-alt me-2'></i>Click to expand/collapse</span>
                                </div>
                            </div>
                        </div>
                    </div>";
                  }  
                }
                else {
                    echo "<div class='col-12 text-center'>
                        <div class='alert alert-info py-4'>
                            <i class='fas fa-info-circle fa-2x mb-3'></i>
                            <p class='mb-1'>No stories have been shared yet.</p>
                            <a href='write1.php' class='btn btn-primary mt-3'>
                                <i class='fas fa-pen-fancy me-2'></i>Write Your Story
                            </a>
                        </div>
                    </div>";
                }
            
            ?>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let bool = [];
        var content = document.getElementsByClassName("story");
        
        for(let i=0; i<content.length; i++) {
            bool[i] = true;
        }
        
        for(let i=0; i<content.length; i++) {
            content[i].addEventListener("click", function() {
                if(bool[i]) {
                    content[i].style.height = "fit-content";
                    bool[i] = false;
                } else {
                    content[i].style.height = "600px";
                    bool[i] = true;
                }
            });
        }
    </script>
</body>
</html>