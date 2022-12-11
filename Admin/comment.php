
<?php
session_start();
if (isset($_SESSION['admin'])) {
    include('init.php');

    $page = "all";

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = "all";
    }

    $statment = $connection->prepare("SELECT * FROM comments");
    $statment->execute();
    $commentCount = $statment->rowCount();

    $clients = $statment->fetchAll();


?>

<?php if ($page == "all") { ?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
        <h2 class="text-center">Comments MANGMENT</h2>
        <a href="?page=addcomment" class="btn btn-success mb-3">Add new Comment</a>
        <div class="card">
            <div class="card-header">
                Featured <span class="badge badge-primary"><?php echo $commentCount; ?></span>
            </div>
            <table class="table table-dark table-striped table-hover table-bordered text-center">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Comment</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">Post_ID</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
        if ($commentCount > 0) {
            foreach ($clients as $client) {
                        ?>
                        <tr>
                        <th scope="row"><?php echo $client['id'] ?></th>
                        <td><?php echo $client['comment']; ?></td>
                        <td><?php echo $client['user_id']; ?></td>
                        <td><?php echo $client['post_id']; ?></td>
                        <td><?php echo $client['status']; ?></td>    
                        <td><?php

                if ($client['status'] == "0") {
                    echo "<p class='badge bg-danger'>Blocked</p>";
                } elseif ($client['status'] == '1') {
                    echo "<p class='badge bg-info'>Approved</p>";
                }
                        ?></td>

                        <td>
                        <a class='btn btn-primary' href='?page=showcomment&user_id=<?php echo $client['id']; ?>'>Show</a>
                        <a class='btn btn-success' href='?page=editcomment&user_id=<?php echo $client['id']; ?>'>Edit</a>
                        <a class='btn btn-danger' href='?page=deletecomment&user_id=<?php echo $client['id']; ?>'>Delete</a>

                    </td>
                    </tr>
                    <?php
            }
        }
                    ?>
                    </tbody>
            </table>
        </div>
        </div>
    </div>
</div>


<?php } elseif ($page == "addcomment") { ?>


    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
            <form method="POST" action="?page=savecomment">
                 
                    <div class="form-group">
                        <label>iD</label>
                        <input type="text" class="form-control" name="idcomment">
                    </div>
                    <div class="form-group">
                        <label>CommentBody</label>
                        <input type="text" class="form-control" name="commentbody">
                    </div>
                    <div class="form-group">
                        <label>User_ID</label>
                        <select class="form-control" name="userid">
                        <?php
                        $statuser = $connection->prepare("SELECT id FROM users");
                        $statuser->execute();
                        $resultuser = $statuser->fetchAll();
                       
                        foreach ($resultuser as $r) {
                            $countuser= $r['id'];
                            echo "<option value='$countuser'>$countuser</option>";
                        }
                        ?>
                        </select>
                    </div>


                  
                    <div class="form-group">
                        <label>Post_ID</label>
                        <select class="form-control" name="postid">

                        <?php
                        $statPost = $connection->prepare("SELECT id FROM posts");
                        $statPost->execute();
                        $result = $statPost->fetchAll();
                       
                        foreach ($result as $r) {
                            $count= $r['id'];
                            echo "<option value='$count'>$count</option>";
                        }
                        ?>
                    
                      
                        
                        </select>
                    </div>

                 

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="statuscomment">
                        
                        <option value="0">0</option>
                        <option value="1">1</option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" name="submitsave">Save New Comment</button>
            </form>
            </div>
        </div>
    </div>


<?php } elseif ($page == "savecomment") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['submitsave'])) {

                $userErr = $passErr = $emailErr = $roleErr = '';
    
                $idComment = $_POST['idcomment'];
                $commentBody = $_POST['commentbody'];       
                $userId = $_POST['userid'];
                $postId = $_POST['postid'];
                $statusComment = $_POST['statuscomment'];

            

    
                if (empty($userErr) && empty($passErr) && empty($emailErr) && empty($roleErr)) {

                    $state = $connection->prepare("
                INSERT INTO `comments`(`id`,`comment`,`status`,`user_id`,`post_id`,`created_at`)
                VALUES (:zcommentid,:zcomment,:zstatus,:zuser_id,:zpost_id,now())
                
                ");

                    $state->execute(
                        array(
                            'zcommentid' => $idComment,
                            'zcomment' => $commentBody,
                            'zstatus' => $statusComment,
                            'zuser_id' => $userId,
                            'zpost_id' => $postId,
                           

                        )
                    );

                    if ($state->rowCount() > 0) {
                        echo "<h2 class='alert alert-primary text-center'>Saved Successfully in Data Base</h2>";
                        header('refresh:3;url=comment.php');
                    } else {
                        echo "No Action In DataBase TryAgin";
                    }

                }


            }

        }
    } elseif ($page == "showcomment") {



        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("SELECT * FROM comments where id=?");

        $statment->execute(array($user_id));

        $clients = $statment->fetchAll();
?>


   <?php foreach ($clients as $x) { ?>

       <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-md-12">
            <table class="table table-dark">
                <thead>
                    <tr>
                    <th scope="col">ID</th>
                        <th scope="col">Comment</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">Post_ID</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row"><?php echo $x['id'] ?></th>
                    <td><?php echo $x['comment'] ?></td>
                    <td><?php echo $x['user_id'] ?></td>
                    <td><?php echo $x['post_id'] ?></td>
                   
                    
                    <td><?php

                        if ($x['status'] == "0") {
                            echo "<p class='badge bg-danger'>Blocked</p>";
                        } elseif ($x['status'] == '1') {
                            echo "<p class='badge bg-info'>Approved</p>";
                        }
                        ?>
                    </td>
                    <td><a href="comment.php" class="btn btn-success">Back Home</a></td>
                    </tr>
                
                
                </tbody>
                </table>
            </div>
        </div>
       </div>

<?php
        }
?>




<?php } elseif ($page == "deletecomment") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("DELETE from comments where id=?");

        $statment->execute(array($user_id));

        header('Location:comment.php');



    } elseif ($page == "editcomment") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '1';
        }
        $editStatment = $connection->prepare('SELECT * FROM comments WHERE id=?');
        $editStatment->execute(array($user_id));
        $editPost = $editStatment->rowCount();
        $resultEdit = $editStatment->fetch();


?>
<h2 class="text-center mt-3">Edit Comment</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            <form method="post" action="?page=updatecomment">
                <div class="form-group">
                    <label>ID-Comment</label>
                    <input name="idedit" type="text" class="form-control" value="<?php echo $resultEdit['id'] ?>">
                </div>
                <div class="form-group">
                    <label>Comment</label>
                    <input name="coomentedit" type="text" class="form-control" value="<?php echo $resultEdit['comment'] ?>">
                </div>
                <div class="form-group">
                    <label>user_ID</label>
                    <input name="userid" type="text" class="form-control" value="<?php echo $resultEdit['user_id'] ?>">
                </div>

                <div class="form-group">
                    <label>post_id</label>
                    <input name="postid" type="text" class="form-control" value="<?php echo $resultEdit['post_id'] ?>">
                </div>


                <div class="form-group">
                 <label >Status</label>
                    <select class="form-control" name="statusedit">
                    <option value="0"
                    <?php
                            if ($resultEdit['status'] == "0") {
                                echo "selected";
                            } else {
                                echo "";
                            }
                    ?>
                    >0</option>
                    <option value="1"
                    
                    <?php
                        if ($resultEdit['status'] == "1") {
                            echo "selected";
                        } else {
                            echo "";
                        }
                    ?>
                    
                    >1</option>
                   
                    </select>
                </div>

               

           
                  <button type="submit" class="btn btn-primary btn-block">Edit</button>
            </form>

            </div>
        </div>
    </div>
    




<?php } elseif ($page == "updatecomment") {


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $idCate = $_POST['idedit'];
            $commentEdit = $_POST['coomentedit'];
            $userid = $_POST['userid'];
            $postid = $_POST['postid'];
            $statusEdit = $_POST['statusedit'];


            $statmentUpdate = $connection->prepare("UPDATE `comments` SET `id`=?,`comment`=?,`status`=?,`user_id`=?,`post_id`=?,`updated_at`=now() WHERE id=?");

            $statmentUpdate->execute(array($idCate, $commentEdit,$statusEdit,$userid,$postid, $idCate));

            $resultUpdate = $statmentUpdate->rowCount();
            if ($resultUpdate > 0) {
                echo "<h2 class='alert alert-success text-center'>Comment Has Been Updated Successfully</h2>";
                header('refresh:2;url=comment.php');
                exit();
            }
        }
    } ?>




<?php

    include('includes/templete/footer.php');
}else{
    header('Location:login.php');
}
?>

