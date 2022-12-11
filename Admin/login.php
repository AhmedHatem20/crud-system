<?php
session_start();

if(isset($_SESSION['admin'])){

    header("Location:dashboard.php");
    exit();
}
include('includes/db/db.php');
include('includes/templete/header.php');



if($_SERVER['REQUEST_METHOD']=="POST"){
    if(isset($_POST['login-admin'])){

        $email =$_POST['email'];
        $password = $_POST['password'];
        $hashPassword = sha1($password);
      
        $check = $connection->prepare("SELECT * FROM users where email=? and password =?");

        $check -> execute(array($email,$hashPassword));

        $rowCount = $check->rowCount();
        $fetchData = $check->fetch();

        if($rowCount>0){
            
            if($fetchData['role']=='admin'){
                $_SESSION['admin'] = $fetchData['username'];
                header('Location:dashboard.php');


            }else if ($fetchData['role']=='user'){
                echo "You Are User Not Admin";
            }

        }else{
            echo "Donot in DataBase";
        }
 
        

        
    }

}

?>



<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mt-5">Admin Login</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1">
            </div>
           
            <button type="submit" name="login-admin" class="btn btn-primary btn-block">Submit</button>
        </form>
        </div>
    </div>
</div>


<?php

include('includes/templete/footer.php');

?>