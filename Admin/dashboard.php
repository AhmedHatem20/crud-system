
    


<?php
session_start();
if (isset($_SESSION['admin'])) {
    include('init.php');

    $q1 = $connection->prepare("SELECT * FROM users");
    $q1->execute();
    $usercount = $q1->rowCount();


    $q2 = $connection->prepare("SELECT * FROM categories");
    $q2->execute();
    $catecount = $q2->rowCount();


    $q3 = $connection->prepare("SELECT * FROM posts");
    $q3->execute();
    $postcount = $q3->rowCount();

    $q4 = $connection->prepare("SELECT * FROM comments");
    $q4->execute();
    $commentcount = $q4->rowCount();

?>


<div class="static mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="box">
                <i class="fa-solid fa-user"></i>
                <h3>users</h3>
                <span><?php echo $usercount ?></span>
                <br>
                <a href="user.php" class="btn btn-danger">Show</a>
                </div>
            </div>

            <div class="col-md-3">
            <div class="box">
            <i class="fa-solid fa-shapes"></i>
                <h3>Categrios</h3>
                <span><?php echo $catecount ?></span>
                <br>
                <a href="cate.php" class="btn btn-primary">Show</a>
                </div>
            </div>

            <div class="col-md-3">
            <div class="box">
            <i class="fa-solid fa-address-card"></i>
                <h3>Posts</h3>
                <span><?php echo $postcount ?></span>
                <br>
                <a href="post.php" class="btn btn-success">Show</a>    
            </div>
            </div>

            <div class="col-md-3">
            <div class="box">
            <i class="fa-solid fa-comment"></i>
                <h3>Comments</h3>
                <span><?php echo $commentcount ?></span>
                <br>
                <a href="comment.php" class="btn btn-warning">Show</a>    
            </div>
            </div>
        </div>
    </div>
</div>


<?php
    include('includes/templete/footer.php');
}else{
    header('Location:login.php');
}
?>

