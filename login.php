
<?php

  include "connection.php";
  if(isset($_POST['submit'])){

    $email=$_POST['email'];
    $password=$_POST['password'];

    $select = "SELECT * FROM customers WHERE email = '$email' && password = '$password'";

    $result = mysqli_query($conn,$select);

    if(mysqli_num_rows($result)>0){
      $row = mysqli_fetch_array($result);

      if($row['type']=='admin'){

        header('location:admin.php');

      }elseif($row['type']=='user'){
	session_start();
        $_SESSION["user"] = $row["email"];
        header('location:booking.php');

      }
    }else{
      $error[] = 'incorrect email or password';
    }
  };


?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caps online</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>
    <div id="login" class="d-flex justify-content-center shadow-lg p-3 mb-5 bg-body-tertiary rounded">
      <form action=""  method="post">
        <h1 id="heading">Login to Cabs Online</h1>
        <?php
           if(isset($error)){
            foreach($error as $error){
              echo '<span style="margin: 10px 0;display: block;color: #fff;background: crimson;border-radius: 5px; font-size: 20px;padding: 5px;">'.$error.'</span>';
            };
           }
        ?>
        <div class="mb-3">
           <label for="exampleInputEmail1" class="form-label">Email address</label>
           <input type="email" name="email" class="form-control"  aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
           <label for="exampleInputPassword1" class="form-label">Password</label>
           <input type="password" name="password" class="form-control" >
        </div> 
        <input type="submit" name="submit" value="login" class="btn btn-primary btn-lg">
         <div class="mt-4">
           <h5>New Member? <a href="register.php">Register Now</a></h5>
         </div>
       </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>