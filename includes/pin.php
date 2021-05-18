<?php
if (!empty($_POST["pin"])) {
    $pincode = $_POST['pin'];
    $date = $_POST['date']; //date format - dd-mm-yyy
    $idpin = date('Y-m-d', strtotime($date));
    $url = "https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByPin?pincode=$pincode&date=$date";
    $resultToJson = get_data($url);
    if (!isset($resultToJson['sessions'])) {
      ?>
        <h3>It looks you have entered wrong pincode. Please try again using correct details.</h3>
      <?php
      exit;
    }
        if (count($resultToJson['sessions']) > 0) {
            ?>
<h2 class="slot">Slot Details for <?php echo $date ?></h2>
<span class="agefilter">Minimum Age Limit: </span><br>
<button id="show-all" class="filter-button clicked">Show All</button>
<button id="18plus" class="filter-button">18+</button>
<button id="45plus" class="filter-button">45+</button>
<div class="scroll">
  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Centre Name</th>
        <th scope="col">Minimum Age</th>
        <th scope="col">Slots Available</th>
		    <th scope="col">Vaccine name</th>
        <th scope="col">Fee</th>
		    <th scope="col">Timing</th>
		    <th scope="col">Address</th>
      </tr>
    </thead>
    <tbody>
    <tr>
      <th colspan="8" class="message" id="message" >
    </tr>
    <?php
        $sessions = $resultToJson['sessions'];
        $Fee = 'N/A';
        $Timing = 'N/A';
        $Minimum_Age = 'N/A';
        $count = 0;
        foreach ($sessions as $session) {
            $cname = $session['center_id'] . "-" . $session['name'];
            $Address = $session['address'] . " " . $session['pincode'];
            $count += 1;
            //echo $session['from'] ."-". $session['to'] ."<br>";
            if (isset($session['fee_type'])) {
                if ($session['fee_type'] != 'Free') {
                    $Fee = $session['fee'];
                } else {
                    $Fee = $session['fee_type'];
                }
            }
            $Date = $session['date'];
            if(isset($session['min_age_limit'])){
              $Minimum_Age = $session['min_age_limit'];
              }
            $Slots = $session['available_capacity'];
            if (isset($session['vaccine'])) {
                $Timing = '<ul>';
                $Vaccine = $session['vaccine'];
                if (isset($session['slots'][0])) {
                  foreach($session['slots'] as $slots){
                    $Timing .= '<li>' . $slots . '</li>';
                  }
                }
            }
            ?>
        <tr class="<?php echo $Minimum_Age ?>">
        <th scope="row"><?php echo $count ?></th>
        <td><?php echo $cname ?>
        <?php
if ($Slots > 0) {
                ?>
        <br>
        <a href="https://selfregistration.cowin.gov.in/" target="_blank"><button class="btn btn-primary">Book Your Slot</button></a>
        <?php
}
            ?>
        </td>
        <td><?php echo $Minimum_Age ?></td>
        <?php
if ($Slots > 0) {
                ?>
        <td class="success"><?php echo $Slots ?></td>
        <?php
} else {
                ?>
          <td class="danger">N/A</td>
          <?php
}
            ?>
        <td><?php echo $Vaccine ?></td>
        <td><?php echo $Fee ?></td>
        <td class="timing"><?php echo $Timing ?></ul></td>
        <td><?php echo $Address ?></td>
        </tr>
        <?php
}
        ?>
      </tbody>
  </table>
  </div>
  <button class="btn btn-info btn-lg nextpin" id="<?php echo $idpin ?>" name="nextpin">See Next Day Slots <i></i></button>
  <?php
    } else {
        ?>
  <h3>Sorry, no slots details are available for this date. Please try again with another date.</h3>
  <?php
}
}
function get_data($url) {
  $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 //curl_setopt($ch, CURLOPT_PROXY, $proxy); // $proxy is ip of proxy server
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
 curl_setopt($ch, CURLOPT_TIMEOUT, 10);
 $httpCode = curl_getinfo($ch , CURLINFO_HTTP_CODE); // this results 0 every time
 $response = curl_exec($ch);
 if ($response === false) $response = curl_error($ch);
 curl_close($ch);
return json_decode($response, true);
}
?>
