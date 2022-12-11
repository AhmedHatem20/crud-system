
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

    $statment = $connection->prepare("SELECT * FROM posts");
    $statment->execute();
    $postCount = $statment->rowCount();

    $clients = $statment->fetchAll();


?>

<?php if ($page == "all") { ?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
        <h2 class="text-center">POSTS MANGMENT</h2>
        <a href="?page=addpost" class="btn btn-success mb-3">Add new Post</a>
        <div class="card">
            <div class="card-header">
                Featured <span class="badge badge-primary"><?php echo $postCount; ?></span>
            </div>
            <table class="table table-dark table-striped table-hover table-bordered text-center">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Categories_ID</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
        if ($postCount > 0) {
            foreach ($clients as $client) {
                        ?>
                        <tr>
                        <th scope="row"><?php echo $client['id'] ?></th>
                        <td><?php echo $client['title']; ?></td>
                        <td><?php echo $client['description']; ?></td>
                        <td><?php echo $client['categery_id']; ?></td>
                        <td><?php echo $client['user_id']; ?></td>    
                        <td><?php

                if ($client['status'] == "0") {
                    echo "<p class='badge bg-danger'>Blocked</p>";
                } elseif ($client['status'] == '1') {
                    echo "<p class='badge bg-info'>Approved</p>";
                }
                        ?></td>

                        <td>
                        <a class='btn btn-primary' href='?page=showpost&user_id=<?php echo $client['id']; ?>'>Show</a>
                        <a class='btn btn-success' href='?page=editpost&user_id=<?php echo $client['id']; ?>'>Edit</a>
                        <a class='btn btn-danger' href='?page=deletepost&user_id=<?php echo $client['id']; ?>'>Delete</a>

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


<?php } elseif ($page == "addpost") { ?>


    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
            <form method="POST" action="?page=savepost">
                 <div class="form-group">
                        <label>iD</label>
                        <input type="text" class="form-control" name="idpost">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="titlepost">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" name="descriptionpost">
                    </div>


                    <div class="form-group">
                        <label>Categories_ID</label>
                        <select class="form-control" name="cateidpost">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>User_ID</label>
                        <select class="form-control" name="useridpost">
                        
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="statuspost">
                        
                        <option value="0">0</option>
                        <option value="1">1</option>

                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block" name="submitsave">Save New post</button>
            </form>
            </div>
        </div>
    </div>


<?php } elseif ($page == "savepost") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['submitsave'])) {

                $userErr = $passErr = $emailErr = $roleErr = '';
    
                $idPost = $_POST['idpost'];
                $titlePost = $_POST['titlepost'];   
                $descriptionPost = $_POST['descriptionpost'];
                $cateIdPost = $_POST['cateidpost'];
                $userIdPost = $_POST['useridpost'];
                $statusPost = $_POST['statuspost'];

                if (!empty($idPost)) {
                    $idPost = filter_var($idPost, FILTER_SANITIZE_STRING);
                } else {
                    $userErr = "Id is Required";
                }

                if (!empty($titlePost)) {
                    $titlePost = filter_var($titlePost, FILTER_SANITIZE_STRING);
                } else {
                    $passErr = "title is Required";
                }

    
                if (empty($userErr) && empty($passErr) && empty($emailErr) && empty($roleErr)) {

                    $state = $connection->prepare("
                INSERT INTO `posts`(`id`,`title`,`description`,`status`,`categery_id`,`user_id`,`created_at`)
                VALUES (:zpostid,:ztitlepost,:zdescriptionpost,:zcate_id_post,:zuser_id_post,:zstatus,now())
                
                ");

                    $state->execute(
                        array(
                            'zpostid' => $idPost,
                            'ztitlepost' => $titlePost,
                            'zdescriptionpost' => $descriptionPost,
                            'zcate_id_post' => $cateIdPost,
                            'zuser_id_post' => $userIdPost,
                            'zstatus' => '1'

                        )
                    );

                    if ($state->rowCount() > 0) {
                        echo "<h2 class='alert alert-primary text-center'>Saved Successfully in Data Base</h2>";
                        header('refresh:3;url=post.php');
                    } else {
                        echo "No Action In DataBase TryAgin";
                    }

                }


            }

        }
    } elseif ($page == "showpost") {



        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("SELECT * FROM posts where id=?");

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
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Categories_ID</th>
                        <th scope="col">User_ID</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row"><?php echo $x['id'] ?></th>
                    <td><?php echo $x['title'] ?></td>
                    <td><?php echo $x['description'] ?></td>
                    <td><?php echo $x['categery_id'] ?></td>
                    <td><?php echo $x['user_id'] ?></td>
                    
                    <td><?php

                        if ($x['status'] == "0") {
                            echo "<p class='badge bg-danger'>Blocked</p>";
                        } elseif ($x['status'] == '1') {
                            echo "<p class='badge bg-info'>Approved</p>";
                        }
                        ?>
                    </td>
                    <td><a href="post.php" class="btn btn-success">Back Home</a></td>
                    </tr>
                
                
                </tbody>
                </table>
            </div>
        </div>
       </div>

<?php
        }
?>




<?php } elseif ($page == "deletepost") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("DELETE from posts where id=?");

        $statment->execute(array($user_id));

        header('Location:post.php');



    } elseif ($page == "editpost") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '1';
        }
        $editStatment = $connection->prepare('SELECT * FROM posts WHERE id=?');
        $editStatment->execute(array($user_id));
        $editPost = $editStatment->rowCount();
        $resultEdit = $editStatment->fetch();


?>
<h2 class="text-center mt-3">Edit Post</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            <form method="post" action="?page=updatepost">
                <div class="form-group">
                    <label>ID-User</label>
                    <input name="idedit" type="text" class="form-control" value="<?php echo $resultEdit['id'] ?>">
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input name="titleedit" type="text" class="form-control" value="<?php echo $resultEdit['title'] ?>">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input name="descriptionedit" type="text" class="form-control" value="<?php echo $resultEdit['description'] ?>">
                </div>

                <div class="form-group">
                    <label>Categories_ID</label>
                    <input name="cateEditPost" type="text" class="form-control" value="<?php echo $resultEdit['categery_id'] ?>">
                </div>

                <div class="form-group">
                    <label>User_ID</label>
                    <input name="useridpost" type="text" class="form-control" value="<?php echo $resultEdit['user_id'] ?>">
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

               

                        <label>Status</label>
                        <input type="radio" value="0" name="statusedit" class="ml-2"
                        <?php
        if ($resultEdit['status'] == "0") {
            echo "checked";
        } else {
            echo "";
        }
                        ?>
                        >Blocked
                        <input type="radio" value="1" name="statusedit" class="ml-2"
                        
                        <?php
        if ($resultEdit['status'] == "1") {
            echo "checked";
        } else {
            echo "";
        }
                        ?>

                        >Approved
                </div>
           
                  <button type="submit" class="btn btn-primary btn-block">Edit</button>
            </form>

            </div>
        </div>
    </div>
    




<?php } elseif ($page == "updatepost") {


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $idCate = $_POST['idedit'];
            $titleEdit = $_POST['titleedit'];
            $descriptionEdit = $_POST['descriptionedit'];
            $cateIdPost = $_POST['cateEditPost'];
            $userIdPost = $_POST['useridpost'];
           
            $statusEdit = $_POST['statusedit'];


            $statmentUpdate = $connection->prepare("UPDATE `posts` SET `id`=?,`title`=?,`description`=?,`categery_id`=?,`user_id`=?,`status`=? ,`upsated_at`=now() WHERE id=?");

            $statmentUpdate->execute(array($idCate, $titleEdit, $descriptionEdit,$cateIdPost,$userIdPost, $statusEdit, $idCate));

            $resultUpdate = $statmentUpdate->rowCount();
            if ($resultUpdate > 0) {
                echo "<h2 class='alert alert-success text-center'>Post Has Been Updated Successfully</h2>";
                header('refresh:2;url=post.php');
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

