<?php
  date_default_timezone_set("Australia/Melbourne");
  session_start();
  if(!isset($_SESSION["user"])){
    header("location:login.php");
  }
  include 'connection.php';

  if(isset($_POST['submit'])){

    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $unit_number = trim($_POST['unit_number']);
    $street_number = trim($_POST['street_number']);
    $street_name = trim($_POST['street_name']);
    $suburb = trim($_POST['subrub']);
    $destination_suburb = trim($_POST['destination_subrub']);
    $pickup_date = $_REQUEST['pickup_date'];
    $pickup_time_12hr  = $_REQUEST['pickup_time'];
    list($time, $am_pm) = explode(' ', $pickup_time_12hr);
    list($hours, $minutes) = explode(':', $time);

    // Convert to 24-hour format
    if ($am_pm === 'PM' && $hours != 12) {
        $hours = ($hours + 12) % 24;
    } elseif ($am_pm === 'AM' && $hours == 12) {
        $hours = 0;
    }

    // Create the 24-hour format time string
    $pickup_time_24hr = sprintf('%02d:%s:00', $hours, $minutes);

    if (empty($name) || empty($phone) || empty($street_number) || empty($street_name) || empty($suburb) || empty($destination_suburb) || empty($pickup_date) || empty($pickup_time_12hr)) {
      header("Location: booking.php?error=1");
      exit;
      }
      $currentDateTime = new DateTime();
      $selectedDateTime = new DateTime($pickup_date . ' ' . $pickup_time);
      $interval = $selectedDateTime->diff($currentDateTime);
      if ($interval->d == 0 && $interval->h == 0 && $interval->i < 40) {
        header("Location: booking.php?error=2");
        exit;
      }

      function generateBookingReference($conn) {
        $unique = false;
        $bookingReference = '';
    
        while (!$unique) {
            // Generate a random 5-digit number
            $bookingReference = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    
            // Check if the generated number already exists in the database
            $query = "SELECT COUNT(*) AS count FROM Booking WHERE booking_number = '$bookingReference'";
            $result = mysqli_query($conn, $query);
    
            if ($result) {
                $row = mysqli_fetch_assoc($result);
                $count = $row['count'];
                if ($count == 0) {
                    $unique = true;
                }
            }
        }
    
        return $bookingReference;
    }
    
    $bookingReference = generateBookingReference($conn);
    $user = $_SESSION["user"];
        $q = "
        INSERT INTO booking (booking_number, customer_email, passenger_name, contact_phone, unit_number, street_number, street_name, suburb, destination_suburb, pickup_date, pickup_time, booking_datetime)
        VALUES
        ('$bookingReference', '$user', '$name', '$phone', '$unit_number', '$street_number', '$street_name', '$suburb', '$destination_suburb', '$pickup_date', '$pickup_time_24hr', '{$currentDateTime->format('Y-m-d H:i:s')}');
        ";
    mysqli_query($conn, $q) or die(mysqli_error($conn));
     header("location:booking.php?success=1");
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cabs online</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  </head>
  <body>
    <div id="login" class="d-flex justify-content-center shadow-lg p-3 mb-5 bg-body-tertiary rounded mt-5">
      <form action="" method="post">
        <h1 id="heading">Booking a Cab</h1>
        <?php
           if(isset($_REQUEST["error"]) && $_REQUEST["error"] == 1){
           
              echo '<span style="margin: 10px 0;display: block;color: #fff;background: crimson;border-radius: 5px; font-size: 20px;padding: 5px;">Please Fill All Data</span>';
           }else if(isset($_REQUEST["error"])){
           
            echo '<span style="margin: 10px 0;display: block;color: #fff;background: crimson;border-radius: 5px; font-size: 20px;padding: 5px;">Pickup Time Should be 40 Minutes ahead of current time</span>';
         }
         else if(isset($_REQUEST["success"])){
           
          echo '<span style="margin: 10px 0;display: block;color: #fff;background: green;border-radius: 5px; font-size: 20px;padding: 5px;">Booking Created Successfully</span>';
       }
        ?>
        <div class="mb-3">
           <label for="exampleInputtext1" class="form-label">Passenger Name</label>
           <input type="text" name="name" class="form-control"  aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
           <label for="exampleInputphone1" class="form-label">Phone of the Passenger</label>
           <input type="text" name="phone" class="form-control"  aria-describedby="emailHelp">
        </div>
        <hr>
       <center> <strong>  Pickup Address</strong> </center>
        <hr>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Unit Number</label>
            <input type="text" name="unit_number" class="form-control">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Street Number</label>
            <input type="text" name="street_number" class="form-control">
        </div> 
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Street Name</label>
            <input type="text" name="street_name" class="form-control">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Subrub</label>
            <input type="text" name="subrub" class="form-control">
        </div>
        <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Destination Suburb</label>
            <input type="text" name="destination_subrub" class="form-control" >
        </div> 
        <div class="mb-3">
           <label for="exampleInputEmail1" class="form-label">Pick Up Date</label>
           <input type="date" name="pickup_date" class="form-control"  aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
           <label for="exampleInputEmail1" class="form-label">Pick Up Time</label>
           <input type="time" name="pickup_time" class="form-control"  aria-describedby="emailHelp">
        </div>
        <input type="submit" name="submit" value="Book" class="btn btn-primary btn-lg">
       </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
  </body>
</html>