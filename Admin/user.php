
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

    $statment = $connection->prepare("SELECT * FROM users");
    $statment->execute();
    $usercount = $statment->rowCount();

    $clients = $statment->fetchAll();


?>

<?php if ($page == "all") { ?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
        <h2 class="text-center">USER MANGMENT</h2>
        <a href="?page=adduser" class="btn btn-success mb-3">Add new User</a>
        <div class="card">
            <div class="card-header">
                Featured <span class="badge badge-primary"><?php echo $usercount; ?></span>
            </div>
            <table class="table table-dark table-striped table-hover table-bordered text-center">
                    <thead>
                        <tr>
                        <th scope="col">ID</th>
                        <th scope="col">username</th>
                        <th scope="col">email</th>
                        <th scope="col">role</th>
                        <th scope="col">status</th>
                        <th scope="col">Operation</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
        if ($usercount > 0) {
            foreach ($clients as $client) {
                        ?>
                        <tr>
                        <th scope="row"><?php echo $client['id'] ?></th>
                        <td><?php echo $client['username']; ?></td>
                        <td><?php echo $client['email']; ?></td>
                        <td><?php echo $client['role']; ?></td>
                        <td><?php

                if ($client['status'] == "0") {
                    echo "<p class='badge bg-danger'>Blocked</p>";
                } elseif ($client['status'] == '1') {
                    echo "<p class='badge bg-info'>Approved</p>";
                }
                        ?></td>
                        <td>
                        <a class='btn btn-primary' href='?page=showuser&user_id=<?php echo $client['id']; ?>'>Show</a>
                        <a class='btn btn-success' href='?page=edituser&user_id=<?php echo $client['id']; ?>'>Edit</a>
                        <a class='btn btn-danger' href='?page=deleteuser&user_id=<?php echo $client['id']; ?>'>Delete</a>

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


<?php } elseif ($page == "adduser") { ?>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
            <form method="POST" action="?page=saveuser">
                 <div class="form-group">
                        <label>UserName:</label>
                        <input type="text" class="form-control" name="username">
                    </div>
                    <div class="form-group">
                        <label>Email address</label>
                        <input type="email" class="form-control" name="email">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role">
                        <option selected disabled>--chooseRole </option>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                       
                      
                        </select>
                    </div>
                   
                    <button type="submit" class="btn btn-primary btn-block" name="submitsave">Save New User</button>
            </form>
            </div>
        </div>
    </div>


<?php } elseif ($page == "saveuser") {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['submitsave'])) {

                $userErr = $passErr = $emailErr = $roleErr = '';
                $username = $_POST['username'];
                $password = $_POST['password'];
                $hashPassword = sha1($password);
                $email = $_POST['email'];
                $role = $_POST['role'];

                if (!empty($username)) {
                    $username = filter_var($username, FILTER_SANITIZE_STRING);
                } else {
                    $userErr = "username is Required";
                }

                if (!empty($password)) {
                    $password = filter_var($password, FILTER_SANITIZE_STRING);
                } else {
                    $passErr = "password is Required";
                }

                if (!empty($role)) {
                    $role = filter_var($role, FILTER_SANITIZE_STRING);
                } else {
                    $roleErr = "Role is Required";
                }

                if (!empty($email)) {
                    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
                } else {
                    $emailErr = "email is Required";
                }

                if (empty($userErr) && empty($passErr) && empty($emailErr) && empty($roleErr)) {

                    $state = $connection->prepare("
                INSERT INTO `users`(`username`,`email`,`password`,`role`,`status`,`created_at`)
                VALUES (:zusername,:zemail,:zpassword,:zrole,:zstatus,now())
                
                ");

                    $state->execute(
                        array(
                            'zusername' => $username,
                            'zemail' => $email,
                            'zpassword' => $hashPassword,
                            'zrole' => $role,
                            'zstatus' => '1'

                        )
                    );

                    if ($state->rowCount() > 0) {
                        echo "<h2 class='alert alert-primary text-center'>Saved Successfully in Data Base</h2>";
                        header('refresh:3;url=user.php');
                    } else {
                        echo "No Action In DataBase TryAgin";
                    }

                }


            }

        }
    } elseif ($page == "showuser") {



        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("SELECT * FROM users where id=?");

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
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">status</th>
                    <th scope="col">opearation</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row"><?php echo $x['id'] ?></th>
                    <td><?php echo $x['username'] ?></td>
                    <td><?php echo $x['email'] ?></td>
                    <td><?php echo $x['role'] ?></td>
                    <td><?php echo $x['status'] ?></td>
                    <td><a href="user.php" class="btn btn-success">Back Home</a></td>
                    </tr>
                
                
                </tbody>
                </table>
            </div>
        </div>
       </div>

<?php
        }
?>
   








<?php } elseif ($page == "deleteuser") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];

        } else {
            $user_id = 1;
        }

        $statment = $connection->prepare("DELETE from users where id=?");

        $statment->execute(array($user_id));

        header('Location:user.php');



    } elseif ($page == "edituser") {

        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        } else {
            $user_id = '1';
        }
        $editStatment = $connection->prepare('SELECT * FROM users WHERE id=?');
        $editStatment->execute(array($user_id));
        $editUser = $editStatment->rowCount();
        $resultEdit = $editStatment->fetch();


?>
<h2 class="text-center mt-3">Edit user</h2>
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            <form method="post" action="?page=updateuser">
                <div class="form-group">
                    <label>ID-User</label>
                    <input name="idedit" type="text" class="form-control" value="<?php echo $resultEdit['id'] ?>">
                </div>
                <div class="form-group">
                    <label>UserName</label>
                    <input name="usernameedit" type="text" class="form-control" value="<?php echo $resultEdit['username'] ?>">
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input name="emailedit" type="email" class="form-control" value="<?php echo $resultEdit['email'] ?>">
                </div>

                <div class="form-group">
                 <label >Role</label>
                    <select class="form-control" name="roleedit">
                    <option value="admin"
                    <?php
        if ($resultEdit['role'] == "admin") {
            echo "selected";
        } else {
            echo "";
        }
                    ?>
                    >admin</option>
                    <option value="user"
                    
                    <?php
        if ($resultEdit['role'] == "user") {
            echo "selected";
        } else {
            echo "";
        }
                    ?>
                    
                    >user</option>
                   
                    </select>
                </div>

                <div class="form-group">

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
    




<?php } elseif ($page == "updateuser") {


        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $idUser = $_POST['idedit'];
            $userNameEdit = $_POST['usernameedit'];
            $emailEdit = $_POST['emailedit'];
            $roleEdit = $_POST['roleedit'];
            $statusEdit = $_POST['statusedit'];


            $statmentUpdate = $connection->prepare("UPDATE `users` SET `username`=?,`email`=?,`role`=?,`status`=? ,`updated_at`=now() WHERE id=?");

            $statmentUpdate->execute(array($userNameEdit, $emailEdit, $roleEdit, $statusEdit, $idUser));

            $resultUpdate = $statmentUpdate->rowCount();
            if ($resultUpdate > 0) {
                echo "<h2 class='alert alert-success text-center'>User Has Been Updated Successfully</h2>";
                header('refresh:2;url=user.php');
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

