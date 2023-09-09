<!doctype html>
<?php
  @include 'connection.php';

  if(isset($_POST["assign_taxi"])){
    $ref_no = $_REQUEST["ref_no"];
    $checkQ = "select * from booking where booking_number = '$ref_no'";
    $rescheck = mysqli_query($conn, $checkQ);
    if(mysqli_num_rows($rescheck)>0){
      $q = "update booking set status='assigned' where booking_number='$ref_no'";
      mysqli_query($conn, $q) or die(mysqli_error($conn));
      header("location:admin.php?success=1");
    }else{
      header("location:admin.php?error=1");
    }
  }
?>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Caps online</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <?php
      if(isset($_REQUEST["success"])){
        echo '<center> <span style="padding:9px; padding-left:30px;padding-right:30px; background-color:black; color:white"> Taxi Assigned to Booking </span> </center>';
      }elseif(isset($_REQUEST["error"])){
        echo '<center> <span style="padding:9px; padding-left:30px;padding-right:30px; background-color:black; color:white"> Invalid Refrence Number </span> </center>';
      }
      ?>
     
        <h1 class="mt-4">Admin Page of Cabs Online</h1>
        <strong>1) Click below button to search for all unassigned requests with a pick up time within 3 hours.</strong>
        <form action="admin.php" method="post">
        <button type="submit" class="btn btn-secondary" name="list">List All</button>
        </form>
    <table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">reference #</th>
      <th scope="col">Passenger Name</th>
      <th scope="col">Passenger Phone No.</th>
      <th scope="col">Pick Up Address</th>
      <th scope="col">Destination Suburb</th>
      <th scope="col">Pick Up Date</th>
    </tr>
  </thead>
  <tbody>
   <?php
    $data = [];
    @include 'connection.php';
     if(isset($_POST["list"])){
       $q = "
       SELECT *
FROM booking
WHERE
    pickup_date = CURDATE() AND
    TIMESTAMP(pickup_date, pickup_time) BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 HOUR) AND
    status = 'unassigned';
       ";
       $res = mysqli_query($conn, $q);
       if(mysqli_num_rows($res)>0){
        while($row = mysqli_fetch_array($res)){
    ?>
      <tr>
        <td><?php echo $row["booking_number"];  ?></td>
        <td><?php echo $row["passenger_name"];  ?></td>
        <td><?php echo $row["contact_phone"];  ?></td>
        <td><?php echo $row["unit_number"];  ?>, <?php echo $row["street_number"];  ?> ,<?php echo $row["street_number"];  ?>, <?php echo $row["street_name"]; ?> </td>
        <td><?php echo $row["destination_suburb"];  ?></td>
        <?php
        $date = $row["pickup_date"]; 
        $humanReadableDate = date("d F", strtotime($date));
        $time = $row["pickup_time"];
        $humanReadableTime = date("h:i A", strtotime($time));
        ?>
        <td><?php echo $humanReadableDate;  ?> , <?php echo $humanReadableTime; ?> </td>
      </tr>
    <?php
        }
      }
    }
    ?>
  </tbody>
</table>
<br><br>

<form action="admin.php" method="post">
  <br>
Referance No : <input type="text" name="ref_no"> <input type="submit" value="Update" name="assign_taxi">
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>