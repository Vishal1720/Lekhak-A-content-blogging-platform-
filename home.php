<?php 
session_start();
if(!isset($_SESSION["user_id"])) {
    header("Location: index.html");
}
$uid = $_SESSION["user_id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Lekhak - A platform for writers and poets to share their work">
    <meta name="theme-color" content="#2c3e50">
    <title>Lekhak - Home</title>
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
                    <i class="fas fa-book me-2"></i>Latest Stories
                </h2>
                <p class="text-light">Immerse yourself in captivating stories shared by our talented community members.</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            include 'dbconnect.php';
            function get_stories($cat){
                global $con;
                
                $limit = 6; // Limit to 6 items per category
                $sql = "SELECT * FROM `content` WHERE category=? ORDER BY id DESC LIMIT ?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("si", $cat, $limit);
                $stmt->execute();
                $res = $stmt->get_result();
                
                if($res->num_rows > 0){
                    foreach($res as $row){
                        $storytitle = $row['title'];
                        $storydesc = $row['descr'];
                        $uid = $row['userid'];
                        
                        $badge_class = "bg-primary";
                        $outline_class = "btn-outline-primary";
                        $icon_class = "fas fa-book-open";
                        
                        if($cat == "poem") {
                            $badge_class = "bg-danger";
                            $outline_class = "btn-outline-danger";
                            $icon_class = "fas fa-feather-alt";
                        } else if($cat == "article") {
                            $badge_class = "bg-success";
                            $outline_class = "btn-outline-success";
                            $icon_class = "fas fa-newspaper";
                        }
                        
                        echo '
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card shadow h-100 story-preview hover-lift">
                                <div class="card-body d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h3 class="card-title">'.htmlspecialchars($storytitle).'</h3>
                                        <span class="badge '.$badge_class.' px-2 py-1 rounded-pill">'.ucfirst($cat).'</span>
                                    </div>
                                    <p class="card-subtitle mb-3"><i class="fas fa-user-edit me-1"></i>By '.htmlspecialchars($uid).'</p>
                                    <p class="card-text flex-grow-1">'.htmlspecialchars($storydesc).'</p>
                                    <a href="'.strtolower($cat).'s.php" class="btn '.$outline_class.' mt-auto">
                                        <i class="'.$icon_class.' me-1"></i>Read More
                                    </a>
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div class="col-12">
                        <div class="alert alert-info shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <div>
                                    <p class="mb-0">No '.strtolower($cat).'s found. Be the first to write one!</p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            
            get_stories('story');
            ?>
        </div>
        
        <div class="row mt-5 mb-4">
            <div class="col-12">
                <h2 class="section-heading">
                    <i class="fas fa-feather-alt me-2"></i>Latest Poems
                </h2>
                <p class="text-light">Experience the beauty of poetry crafted by our creative community.</p>
            </div>
        </div>
        
        <div class="row">
            <?php get_stories('poem'); ?>
        </div>
        
        <div class="row mt-5 mb-4">
            <div class="col-12">
                <h2 class="section-heading">
                    <i class="fas fa-newspaper me-2"></i>Latest Articles
                </h2>
                <p class="text-light">Discover insightful articles on various topics from our thoughtful writers.</p>
            </div>
        </div>
        
        <div class="row">
            <?php get_stories('article'); ?>
        </div>
        
        <div class="row mt-5">
            <div class="col-12 text-center">
                <div class="card bg-opacity-10 border-0 shadow-lg p-4" style="background-color: rgba(255,255,255,0.05);">
                    <div class="card-body">
                        <h3 class="text-light mb-3">Ready to share your creativity?</h3>
                        <p class="text-light mb-4">Join our community of writers and express yourself through stories, poems, or articles.</p>
                        <a href="write1.php" class="btn btn-primary btn-lg shadow">
                            <i class="fas fa-pen-fancy me-2"></i>Start Writing
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Add hover effect to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.hover-lift');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px)';
                    this.style.boxShadow = '0 15px 25px rgba(0, 0, 0, 0.2)';
                });
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
                });
            });
        });
    </script>
</body>
</html>