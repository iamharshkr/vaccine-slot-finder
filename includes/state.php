<?php
if (!empty($_POST["dist"])) {
    $dist = $_POST['dist'];
    $date = $_POST['date']; //date format - dd-mm-yyy
    $idpin = date('Y-m-d', strtotime($date));
    $url = 'https://cdn-api.co-vin.in/api/v2/appointment/sessions/public/findByDistrict?district_id=' . $dist . '&date=' . $date;
    $resultToJson = get_data($url);
    if (!isset($resultToJson['sessions'])) {
        ?>
        <h3>Something went error, Please try again.</h3>
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
  <!-- <div class="table-responsive"> -->
  <table class="table">
    <thead class="thead-light">
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
        $Timing = $Minimum_Age = $Fee = 'N/A';
        $Slots2 = $Slots1 = 'N/A';
        $count = 0;
        $sessions = $resultToJson['sessions'];
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
            if (isset($session['min_age_limit'])) {
                $Minimum_Age = $session['min_age_limit'];
            }
            if (isset($session['available_capacity_dose1'])) {
                $Slots1 = $session['available_capacity_dose1'];
            }
            if (isset($session['available_capacity_dose2'])) {
                $Slots2 = $session['available_capacity_dose2'];
            }
            if (isset($session['vaccine'])) {
                $Timearr = array();
                $Vaccine = $session['vaccine'];
                if (isset($session['slots'][0])) {
                    foreach ($session['slots'] as $slots) {
                        $Timearr[] .= $slots;
                    }
                }
            }
            if (isset($Timearr[0])) {
                $mintime = explode('-', $Timearr[0]);
                $maxtime = explode('-', $Timearr[count($Timearr) - 1]);
                $Timing = $mintime[0] . '-' . $maxtime[1];
            }
            ?>
        <tr class="<?php echo $Minimum_Age ?>">
        <th scope="row"><?php echo $count ?></th>
        <td><?php echo $cname ?>
        <?php
if ($Slots1 > 0 or $Slots2 > 0) {
                ?>
        <br>
        <a href="https://selfregistration.cowin.gov.in/" target="_blank"><button class="btn btn-primary">Book Your Slot</button></a>
        <?php
}
            ?>
        </td>
        <td><?php echo $Minimum_Age ?></td>
        <?php
if ($Slots1 > 0) {
                ?>
<td><ul><li class="slotyes">Dose 1 : <?php echo $Slots1 ?></li>
<?php
} else {
                ?>
<td><ul><li class="slotno">Dose 1 : <?php echo $Slots1 ?></li>
<?php
}
            if ($Slots2 > 0) {
                ?>
<li class="slotyes">Dose 2 : <?php echo $Slots2 ?></li></ul></td>
<?php
} else {
                ?>
<li class="slotno">Dose 2 : <?php echo $Slots2 ?></li></ul></td>
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
    <button class="btn btn-info btn-lg nextst" id="<?php echo $idpin ?>" name="nextst">See Next Day Slots <i></i></button>
<?php
} else {
        ?>
    <h3>Sorry, no slots details are available for this date. Please try with another date.</h3>
    <?php
}
}
function get_data($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // this results 0 every time
    $response = curl_exec($ch);
    if ($response === false) {
        $response = curl_error($ch);
    }

    curl_close($ch);
    return json_decode($response, true);
}
?>
