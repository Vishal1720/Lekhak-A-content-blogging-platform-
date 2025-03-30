<?php
if(isset($_POST['title']))
{
$server="localhost";
$username="root";
$pass="";
$con=mysqli_connect($server,$username,$pass);
if(!$con)
{
    die("Couldn't connect to server: " . $con->error);
}

$title=$_POST['title'];
$content=$_POST['content'];
$description=$_POST['desc'];
$category=$_POST['category'];
}
session_start();
$uid = $_SESSION["user_id"];
 
if(isset($_POST['title']))
{
$sql="INSERT INTO `blogger`.`content`(`userid`, `descr`, `content`, `title`, `category`)
VALUES ('$uid','$description','$content','$title','$category')";

if($con->query($sql))
{
    echo "<script>alert('Published successfully!');</script>";
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekhak - Write</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="main">
        <div class="container">
            <h1 class="display-4 mb-0"><i class="fas fa-pen-fancy"></i> Lekhak</h1><p class="tagline lead mt-0">A creative space for writers and storytellers</p>
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
                        <a class="nav-link" href="poems.php">Poems</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="write1.php">Write</a>
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
            <div class="col-12 text-center">
                <h2 class="section-heading d-inline-block">
                    <i class="fas fa-pencil-alt me-2"></i>Express Yourself
                </h2>
                <p class="text-light">Share your creativity with the world - write a poem, story, or article.</p>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="write card shadow-lg">
                    <div class="card-body p-md-5 p-4">
                        <h2 class="card-title text-center mb-4 position-relative pb-3">
                            Create Your Content
                            <span class="position-absolute start-50 translate-middle-x bottom-0" style="width: 60px; height: 3px; background-color: var(--secondary-color); border-radius: 3px;"></span>
                        </h2>
                        <form action="write1.php" method="post" class="needs-validation" novalidate>
                            <div class="mb-4">
                                <label for="category" class="form-label">
                                    <i class="fas fa-list-alt me-2"></i>Category
                                </label>
                                <select id="category" name="category" class="form-select">
                                    <option>Poem</option>
                                    <option>Story</option>
                                    <option>Article</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading me-2"></i>Title
                                </label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Enter a captivating title" required>
                                <div class="invalid-feedback">
                                    Please provide a title for your work.
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="desc" class="form-label">
                                    <i class="fas fa-align-left me-2"></i>Description
                                </label>
                                <input type="text" name="desc" id="desc" class="form-control" placeholder="Briefly describe your work (will appear in previews)" required>
                                <div class="invalid-feedback">
                                    Please provide a short description.
                                </div>
                            </div>
                            
                            <div class="mb-5">
                                <label for="content" class="form-label">
                                    <i class="fas fa-book-open me-2"></i>Content
                                </label>
                                <textarea name="content" id="content" class="form-control" placeholder="Let your creativity flow..." required></textarea>
                                <div class="invalid-feedback">
                                    Your content cannot be empty.
                                </div>
                            </div>
                            
                            <div class="d-grid gap-3 d-md-flex justify-content-md-between">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="fas fa-paper-plane me-2"></i>Publish
                                </button>
                                <button type="reset" class="btn btn-danger flex-grow-1">
                                    <i class="fas fa-eraser me-2"></i>Clear
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card mt-4 shadow border-0 bg-opacity-75" style="background-color: rgba(255,255,255,0.1);">
                    <div class="card-body p-3 text-light">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-lightbulb text-warning me-3 fa-2x"></i>
                            <div>
                                <h5 class="mb-1">Writing Tips</h5>
                                <p class="mb-0 small">Express yourself freely. Use paragraphs to organize your thoughts. Review your work before publishing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Bootstrap form validation
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>