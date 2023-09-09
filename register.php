<?php

  @include 'connection.php';

  if(isset($_POST['submit'])){

    $name=$_POST['name'];
    $password=$_POST['password'];
    $cpassword=$_POST['cpassword'];
    $email=$_POST['email'];
    $phone=$_POST['phone'];

    $select = "SELECT * FROM customers WHERE email = '$email' && password = '$password'";

    $result = mysqli_query($conn,$select);

    if(mysqli_num_rows($result)>0){

      $error[] = 'user already exist';

    }else{
      if($password!= $cpassword)
      {
        $error[] = 'password not matched';
      }else{
        $insert = "INSERT INTO customers(Name,Password,email,Phone) VALUES('$name','$password','$email','$phone')";

        mysqli_query($conn,$insert);

        header('location:login.php');
         
      }
    }
  }


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
      <form  action="" method="post">
        <h1 id="heading">Register to Cabs Online</h1>
        <?php
           if(isset($error)){
            foreach($error as $error){
              echo '<span style="margin: 10px 0;display: block;color: #fff;background: crimson;border-radius: 5px; font-size: 20px;padding: 5px;">'.$error.'</span>';
            };
           }
        ?>
        <div class="mb-3">
           <label for="exampleInputtext1" class="form-label">Name</label>
           <input type="text" name="name" class="form-control" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" >
        </div> 
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Confirm Password</label>
            <input type="password" name="cpassword" class="form-control" >
        </div> 
        <div class="mb-3">
           <label for="exampleInputEmail1" class="form-label">Email address</label>
           <input type="email" name="email" class="form-control" aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
           <label for="exampleInputphone1" class="form-label">Phone</label>
           <input type="text" name="phone" class="form-control"  aria-describedby="emailHelp">
        </div>
        <input type="submit" name="submit" value="Register" class="btn btn-primary btn-lg">
         <div class="mt-4">
           <h5>Already Member? <a href="login.php">Login Now</a></h5>
         </div>
       </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>