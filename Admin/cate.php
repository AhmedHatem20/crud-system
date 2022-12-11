
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

    $statment = $connection->prepare("SELECT * FROM categories");
    $statment->execute();
    $catecount = $statment->rowCount();

    $clients = $statment->fetchAll();


?>

<?php if ($page == "all") { ?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
        <h2 class="text-center">Categories MANGMENT</h2>
        <a href="?page=addcate" class="btn btn-success mb-3">Add new Categories</a>
        <div class="card">
            <div class="card-header">
                Featured <span class="badge badge-primary"><?php echo $catecount; ?></span>
            </div>
            <table class="table table-dark table-striped table-hover table-bordered text-center">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
        if ($catecount > 0) {
            foreach ($clients as $client) {
                        ?>
                        <tr>
                        <th scope="row"><?php echo $client['id'] ?></th>
                        <td><?php echo $client['title']; ?></td>
                        <td><?php echo $client['description']; ?></td>
                       
                        <td><?php

                if ($client['status'] == "0") {
                    echo "<p class='badge bg-danger'>Blocked</p>";
                } elseif ($client['status'] == '1') {
                    echo "<p class='badge bg-info'>Approved</p>";
                }
                        ?></td>
                        <td>
                        <a class='btn btn-primary' href='?page=showcate&user_id=<?php echo $client['id']; ?>'>Show</a>
                        <a class='btn btn-success' href='?page=editcate&user_id=<?php echo $client['id']; ?>'>Edit</a>
                        <a class='btn btn-danger' href='?page=deletecate&user_id=<?php echo $client['id']; ?>'>Delete</a>

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


<?php } elseif ($page == "addcate") { ?>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
            <form method="POST" action="?page=savecate">
                 <div class="form-group">
                        <label>iD</label>
                        <input type="text" class="form-control" name="idcate">
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control" name="titlecate">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" class="form-control" name="descriptioncate">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="statuscate">
                        
                        <option value="0">0</option>
                        <option value="1">1</option>
                       
                      
                        </select>
                    </div>
                   
                    <button type="submit" class="btn btn-primary btn-block" name="submitsave">Save New Categories</button>
            </form>
            </div>
        </div>
    </div>


<?php } elseif ($page == "savecate") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['submitsave'])) {

                $userErr = $passErr = $emailErr = $roleErr = '';
                
                $idCate = $_POST['idcate'];
                $titleCate = $_POST['titlecate'];   
                $descriptionCate = $_POST['descriptioncate'];
                $statusCate = $_POST['statuscate'];

                if (!empty($idCate)) {
                    $idCate = filter_var($idCate, FILTER_SANITIZE_STRING);
                } else {
                    $userErr = "Id is Required";
                }

                if (!empty($titleCate)) {
                    $titleCate = filter_var($titleCate, FILTER_SANITIZE_STRING);
                } else {
                    $passErr = "title is Required";
                }

             

             

                if (empty($userErr) && empty($passErr) && empty($emailErr) && empty($roleErr)) {

                    $state = $connection->prepare("
                INSERT INTO `categories`(`id`,`title`,`description`,`status`,`created_at`)
                VALUES (:zcateid,:ztitlecate,:zdescriptioncate,:zstatus,now())
                
                ");

                    $state->execute(
                        array(
                            'zcateid' => $idCate,
                            'ztitlecate' => $titleCate,
                            'zdescriptioncate' => $descriptionCate,
                            'zstatus' => '1'

                        )
                    );

                    if ($state->rowCount() > 0) {
                        echo "<h2 class='alert alert-primary text-center'>Saved Successfully in Data Base</h2>";
                        header('refresh:3;url=cate.php');
                    } else {
                        echo "No Action In DataBase TryAgin";
                    }

                }


            }

        }
    } elseif ($page == "showcate") {



        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("SELECT * FROM categories where id=?");

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
                    <th scope="col">status</th>
                    <th scope="col">opearation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row"><?php echo $x['id'] ?></th>
                    <td><?php echo $x['title'] ?></td>
                    <td><?php echo $x['description'] ?></td>
                    
                    <td><?php

                        if ($x['status'] == "0") {
                            echo "<p class='badge bg-danger'>Blocked</p>";
                        } elseif ($x['status'] == '1') {
                            echo "<p class='badge bg-info'>Approved</p>";
                        }
                        ?>
                    </td>
                    <td><a href="cate.php" class="btn btn-success">Back Home</a></td>
                    </tr>
                
                
                </tbody>
                </table>
            </div>
        </div>
       </div>

<?php
        }
?>
   








<?php } elseif ($page == "deletecate") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("DELETE from categories where id=?");

        $statment->execute(array($user_id));

        header('Location:cate.php');



    } elseif ($page == "editcate") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '1';
        }
        $editStatment = $connection->prepare('SELECT * FROM categories WHERE id=?');
        $editStatment->execute(array($user_id));
        $editCate = $editStatment->rowCount();
        $resultEdit = $editStatment->fetch();


?>
<h2 class="text-center mt-3">Edit user</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            <form method="post" action="?page=updatecate">
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
    




<?php } elseif ($page == "updatecate") {


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $idCate = $_POST['idedit'];
            $titleEdit = $_POST['titleedit'];
            $descriptionEdit = $_POST['descriptionedit'];
           
            $statusEdit = $_POST['statusedit'];


            $statmentUpdate = $connection->prepare("UPDATE `categories` SET `id`=?,`title`=?,`description`=?,`status`=? ,`updated_at`=now() WHERE id=?");

            $statmentUpdate->execute(array($idCate, $titleEdit, $descriptionEdit, $statusEdit, $idCate));

            $resultUpdate = $statmentUpdate->rowCount();
            if ($resultUpdate > 0) {
                echo "<h2 class='alert alert-success text-center'>Categerios Has Been Updated Successfully</h2>";
                header('refresh:2;url=cate.php');
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

