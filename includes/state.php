<?php
if(!empty($_POST["dist"])){
    $dist= $_POST['dist'];
    $date = $_POST['date']; //date format - dd-mm-yyy
    $idpin = date('Y-m-d', strtotime($date));
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByDistrict?district_id=' .$dist. '&date=' .$date,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));
    $response = curl_exec($curl);
    $resultToJson = json_decode($response, true);
    if (!isset($resultToJson['sessions'])) {
      ?>
        <h3>Something went error, Please try again.</h3>
      <?php
      exit;
    }
    if(count($resultToJson['sessions']) > 0){
      ?>
            <h2 class="slot">Slot Details for <?php echo $date ?></h2>
            <div class="scroll">       
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Centre Name</th>
        <th>Minimum Age</th>
        <th>Slots Available</th>
		    <th>Vaccine name</th>
        <th>Fee</th>
		    <th>Timing</th>
		    <th>Address</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $sessions = $resultToJson['sessions'];
    foreach($sessions as $session){
        $cname = $session['center_id'] ."-". $session['name'];
        $Address = $session['address'] ." ". $session['pincode'];
        //echo $session['from'] ."-". $session['to'] ."<br>";
        $Fee = $session['fee_type'];
        $Date = $session['date'];
        $Minimum_Age = $session['min_age_limit'];
        $Slots = $session['available_capacity'];
        if(isset($session['vaccine'])){
        $Vaccine = $session['vaccine'];
        $Timing = '<li>' .$session['slots'][0] ."</li><li>". $session['slots'][1] ."</li><li>". $session['slots'][2] ."</li>";
        }
        ?>
        <tr>
        <td><?php echo $cname ?></td>
        <td><?php echo $Minimum_Age ?></td>
        <?php
        if($Slots > 0){
          ?>
        <td class="success"><?php echo $Slots ?></td>
        <?php
        }else{
          ?>
          <td class="danger"><?php echo $Slots ?></td>
          <?php
        }
        ?>
        <td><?php echo $Vaccine ?></td>
        <td><?php echo $Fee ?></td>
        <td><?php echo $Timing ?></td>
        <td><?php echo $Address ?></td>
        </tr>
        <?php
    }
    ?>
    </tbody>
    </table>
    </div><br>
    <button class="btn btn-info btn-lg nextst" id="<?php echo $idpin ?>" name="nextst">See Next Day Slots <i></i></button>
<?php
  }else{
    ?>
    <h3>Sorry, no slots details are available for this date. Please try with another date.</h3>
    <?php
  }
  }
      ?>
      