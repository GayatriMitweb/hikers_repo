<?php 
//Generic Files
include "../../../../model.php";
include "printFunction.php";
global $app_quot_img,$similar_text,$quot_note;

$quotation_id = $_GET['quotation_id'];
$sq_terms_cond = mysql_fetch_assoc(mysql_query("select * from terms_and_conditions where type='Package Quotation' and active_flag ='Active'")); 

$sq_quotation = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_master where quotation_id='$quotation_id'"));
$transport_agency_id = $sq_quotation['transport_agency_id'];
$sq_transport1 = mysql_fetch_assoc(mysql_query("select * from transport_agency_master where transport_agency_id='$transport_agency_id'"));
$sq_package_name = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id = '$sq_quotation[package_id]'"));
$sq_dest = mysql_fetch_assoc(mysql_query("select link from video_itinerary_master where dest_id = '$sq_package_name[dest_id]'"));

$sq_transport = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
$sq_costing = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_costing_entries where quotation_id='$quotation_id'"));
$sq_package_program = mysql_query("select * from  package_quotation_program where quotation_id='$quotation_id'");

$sq_login = mysql_fetch_assoc(mysql_query("select * from roles where id='$sq_quotation[login_id]'"));
$sq_emp_info = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$sq_login[emp_id]'"));

$quotation_date = $sq_quotation['quotation_date'];
$yr = explode("-", $quotation_date);
$year =$yr[0];

$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$customer_id'"));

if($sq_emp_info['first_name']==''){
  $emp_name = 'Admin';
}
else{
  $emp_name = $sq_emp_info['first_name'].' '.$sq_emp_info['last_name'];
}

$basic_cost = $sq_costing['basic_amount'];
$service_charge = $sq_costing['service_charge'];
$tour_cost= $basic_cost +$service_charge;
$service_tax_amount = 0;
$tax_show = '';
$bsmValues = json_decode($sq_costing['bsmValues']);

if($sq_costing['service_tax_subtotal'] !== 0.00 && ($sq_costing['service_tax_subtotal']) !== ''){
  $service_tax_subtotal1 = explode(',',$sq_costing['service_tax_subtotal']);
  for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
    $service_tax = explode(':',$service_tax_subtotal1[$i]);
    $service_tax_amount +=  $service_tax[2];
    $name .= $service_tax[0]  . $service_tax[1] .', ';
  }
}
if($bsmValues[0]->service != ''){   //inclusive service charge
  $newBasic = $tour_cost + $service_tax_amount;
  $tax_show = '';
}
else{
  // $tax_show = $service_tax_amount;
  $name .= $service_tax[0]  . $service_tax[1] .', ';
  $newBasic = $tour_cost;
}

////////////Basic Amount Rules
if($bsmValues[0]->basic != ''){ //inclusive markup
  $newBasic = $tour_cost + $service_tax_amount;
  $tax_show = '';
}

$quotation_cost = $basic_cost +$service_charge+ $service_tax_amount+ $sq_quotation['train_cost'] + $sq_quotation['cruise_cost']+ $sq_quotation['flight_cost'] + $sq_quotation['visa_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost'];
?>

    <!-- landingPage -->
    <section class="landingSec main_block">
      <div class="landingPageTop main_block">
        <img src="<?= $app_quot_img?>" class="img-responsive">
        <h1 class="landingpageTitle"><?= $sq_package_name['package_name']?> <em><?='('.$sq_package_name['package_code'].')' ?></em></h1>
        <span class="landingPageId"><?= get_quotation_id($quotation_id,$year) ?></span>
      </div>

      <div class="ladingPageBottom main_block side_pad">

        <div class="row">
          <div class="col-md-4">
            <div class="landigPageCustomer mg_tp_20">
              <h3 class="customerFrom">Prepare for</h3>
              <span class="customerName mg_tp_10"><i class="fa fa-user"></i> : <?= $sq_quotation['customer_name'] ?></span><br>
              <span class="customerMail mg_tp_10"><i class="fa fa-envelope"></i> : <?= $sq_quotation['email_id'] ?></span><br>
              <span class="customerMobile mg_tp_10"><i class="fa fa-phone"></i> : <?= $sq_quotation['mobile_no'] ?></span><br>
              <span class="generatorName mg_tp_10">Prepare By <?= $emp_name?></span><br>
            </div>
          </div>
          <div class="col-md-8 text-right">
          
          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockBlue">
              <i class="fa fa-calendar"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= get_date_user($sq_quotation['quotation_date']) ?></h3>
              <span class="contentLabel">QUOTATION DATE</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockGreen">
              <i class="fa fa-hourglass-half"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?php echo ($sq_quotation['total_days']-1).'N/'.$sq_quotation['total_days'].'D' ?></h3>
              <span class="contentLabel">DURATION</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockYellow">
              <i class="fa fa-users"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= $sq_quotation['total_passangers'] ?></h3>
              <span class="contentLabel">TOTAL GUEST</span>
            </div>
          </div>

          <div class="detailBlock text-center">
            <div class="detailBlockIcon detailBlockRed">
              <i class="fa fa-tag"></i>
            </div>
            <div class="detailBlockContent">
              <h3 class="contentValue"><?= number_format($quotation_cost,2) ?></h3>
              <span class="contentLabel">PRICE</span>
            </div>
          </div>
          </div>
        </div>

      </div>
    </section>



  <!-- Count queries -->
  <?php
  $sq_package_count = mysql_num_rows(mysql_query("select * from  package_quotation_program where quotation_id='$quotation_id'"));
  $sq_train_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'"));
  $sq_plane_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'"));
  $sq_cruise_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'"));
  $sq_hotel_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'"));
  $sq_transport_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'"));
  $sq_exc_count = mysql_num_rows(mysql_query("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'"));
  ?>

    <!-- Itinerary -->
    <?php if($sq_package_count != 0){ ?>    
    <section class="itinerarySec main_block side_pad mg_tp_30">
      <div class="row">
        <div class="col-md-12">
          <div class="print_info_block">
              <div class="print_quo_detail_block">
                <img src="<?php echo BASE_URL.'images/quotation/youtube-icon.png'; ?>" class="itinerary-img img-responsive">
                &nbsp;VIDEO ITINERARY :&nbsp;<a href="<?=$sq_dest['link']?>" class="no-marg itinerary-link" target="_blank"> </a>
              </div>
          </div>
        </div>
      </div>    
      <ul class="print_itinenary main_block no-pad no-marg">
        <?php
          $count = 1;
          while($row_itinarary = mysql_fetch_assoc($sq_package_program)){
            $daywise_image = 'http://itourscloud.com/quotation_format_images/dummy-image.jpg';
            $sq_day_image = mysql_fetch_assoc(mysql_query("select * from package_tour_quotation_images where quotation_id='$row_itinarary[quotation_id]' "));
            $day_url1 = explode(',',$sq_day_image['image_url']);
            for($count1 = 0; $count1<sizeof($day_url1);$count1++){
                $day_url2 = explode('=',$day_url1[$count1]);
                if($day_url2[1]==$row_itinarary['day_count'] && $day_url2[0]==$row_itinarary['package_id']){
                  $daywise_image = $day_url2[2];
                }
            }
            if($count%2!=0){
        ?>
        <li class="singleItinenrary leftItinerary col-md-12 no-pad">
          <div class="itneraryContent col-md-6 no-pad text-right mg_tp_20 mg_bt_20">
            <div class="itneraryText col-md-8 no-pad">
              <h5 class="specialAttraction no-marg"><?= $row_itinarary['attraction'] ?></h5>
              <p><?= $row_itinarary['day_wise_program'] ?></p>
            </div>
            <div class="itneraryImg col-md-4 no-pad">
              <img src="<?= $daywise_image ?>" class="img-responsive">
            </div>
          </div>
          <div class="itineraryDetail">
            <ul class="no-marg no-pad">
              <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
              <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
            </ul>
          </div>
          <div class="dayCount">
            <span>Day-<?= $count ?></span>
          </div>
          <div class="col-md-6 no-pad"></div>
        </li>

        <?php }else{ ?>

        <li class="singleItinenrary rightItinerary col-md-12 no-pad">
          <div class="col-md-6 no-pad"></div>
          <div class="itneraryContent col-md-6 no-pad text-left mg_tp_20 mg_bt_20">
            <div class="itneraryImg col-md-4 no-pad">
              <img src="<?= $daywise_image ?>" class="img-responsive">
            </div>
            <div class="itneraryText col-md-8 no-pad">
              <h5 class="specialAttraction no-marg"><?= $row_itinarary['attraction'] ?></h5>
              <p><?= $row_itinarary['day_wise_program'] ?></p>
            </div>
          </div>
          <div class="itineraryDetail">
            <ul class="no-marg no-pad">
              <li><span><i class="fa fa-bed"></i> : </span><?=  $row_itinarary['stay'] ?></li>
              <li><span><i class="fa fa-cutlery"></i> : </span><?= $row_itinarary['meal_plan'] ?></li>
            </ul>
          </div>
          <div class="dayCount">
            <span>Day-<?= $count ?></span>
          </div>
        </li>
            
        <?php } $count++; } ?>
      </ul>

    </section>
    <?php } ?>

    <!-- traveling Information -->
  <?php if($sq_transport_count != 0 || $sq_train_count != 0 || $sq_plane_count != 0 || $sq_train_count != 0 || $sq_exc_count != 0){?>
    <section class="travelingDetails main_block mg_tp_30">
         
          <!-- train -->
          <?php if($sq_train_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/train.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>From</th>
                        <th>To</th>
                        <th>Class</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $sq_train = mysql_query("select * from package_tour_quotation_train_entries where quotation_id='$quotation_id'");
                      while($row_train = mysql_fetch_assoc($sq_train)){  
                        ?>
                        <tr>
                          <td><?= $row_train['from_location'] ?></td>
                          <td><?= $row_train['to_location'] ?></td>
                          <td><?= $row_train['class'] ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_train['departure_date'])) ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_train['arrival_date'])) ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
            </div>
          </section>
          <?php } ?>
          <?php
          if($sq_plane_count>0){ ?>
          <!-- flight -->
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
            <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/flight.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>From</th>
                        <th>To</th>
                        <th>Airline</th>
                        <th>Class</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $sq_plane = mysql_query("select * from package_tour_quotation_plane_entries where quotation_id='$quotation_id'");
                        while($row_plane = mysql_fetch_assoc($sq_plane)){
                        $sq_airline = mysql_fetch_assoc(mysql_query("select * from airline_master where airline_id='$row_plane[airline_name]'"));
                      ?>   
                      <tr>
                        <td><?= $row_plane['from_location'] ?></td>
                        <td><?= $row_plane['to_location'] ?></td>
                        <td><?= $sq_airline['airline_name'].' ('.$sq_airline['airline_code'].')' ?></td>
                        <td><?= $row_plane['class'] ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($row_plane['dapart_time'])) ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($row_plane['arraval_time'])) ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
              
            </div>
          </section>
          <?php }?>

          <!-- cruise -->
          <?php
          if($sq_cruise_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/cruise.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Route</th>
                        <th>Cabin</th>
                        <th>Sharing</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                      $sq_cruise = mysql_query("select * from package_tour_quotation_cruise_entries where quotation_id='$quotation_id'");
                      while($row_cruise = mysql_fetch_assoc($sq_cruise)){  
                        ?>
                        <tr>
                          <td><?= date('d-m-Y H:i', strtotime($row_cruise['dept_datetime'])) ?></td>
                          <td><?= date('d-m-Y H:i', strtotime($row_cruise['arrival_datetime'])) ?></td>
                          <td><?= $row_cruise['route'] ?></td>
                          <td><?= $row_cruise['cabin'] ?></td>
                          <td><?= $row_cruise['sharing'] ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <?php } ?>
          <!-- Hotel -->
          <?php if($sq_hotel_count != 0){?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
            <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/hotel.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                      <th>City</th>
                      <th>Hotel Name</th>
                      <th>Check_IN</th>
                      <th>Check_OUT</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php $sq_hotel = mysql_query("select * from package_tour_quotation_hotel_entries where quotation_id='$quotation_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel)){
                        $hotel_name = mysql_fetch_assoc(mysql_query("select * from hotel_master where hotel_id='$row_hotel[hotel_name]'"));
                        $city_name = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_hotel[city_name]'"));
                      ?>
                      <tr>
                          <?php
                          $sq_count_h = mysql_num_rows(mysql_query("select * from hotel_vendor_images_entries where hotel_id='$row_hotel[hotel_name]' "));
                          if($sq_count_h ==0){
                            $download_url =  BASE_URL.'images/dummy-image.jpg';
                          }
                          else{
                            $sq_hotel_image = mysql_query("select * from hotel_vendor_images_entries where hotel_id = '$row_hotel[hotel_name]'");
                            while($row_hotel_image = mysql_fetch_assoc($sq_hotel_image)){      
                                $image = $row_hotel_image['hotel_pic_url']; 
                                $newUrl = preg_replace('/(\/+)/','/',$image);
                                $newUrl = explode('uploads', $newUrl);
                                $download_url = BASE_URL.'uploads'.$newUrl[1];
                              }
                          }
                        ?>
                          <td><?php echo $city_name['city_name']; ?></td>
                          <td><?php echo $hotel_name['hotel_name'].$similar_text; ?></td>
                          <td><?= get_date_user($row_hotel['check_in']) ?></td>
                          <td><?= get_date_user($row_hotel['check_out']) ?></td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>  
              
            </div>
          </section>
          <?php }?>
           <!-- transport -->
           <?php
          if($sq_transport_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">  
              <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/car.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>VEHICLE</th>
                        <th>START DATE</th>
                        <th>PICKUP LOCATION</th>
                        <th>DROP LOCATION</th>
                        <th>TOTAL VEHICLES</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $count = 0;
                      $sq_hotel = mysql_query("select * from package_tour_quotation_transport_entries2 where quotation_id='$quotation_id'");
                      while($row_hotel = mysql_fetch_assoc($sq_hotel)){
                        
                      $transport_name = mysql_fetch_assoc(mysql_query("select * from b2b_transfer_master where entry_id='$row_hotel[vehicle_name]'"));
                      // Pickup
                      if($row_hotel['pickup_type'] == 'city'){
                        $row = mysql_fetch_assoc(mysql_query("select city_id,city_name from city_master where city_id='$row_hotel[pickup]'"));
                        $pickup = $row['city_name'];
                      }
                      else if($row_hotel['pickup_type'] == 'hotel'){
                        $row = mysql_fetch_assoc(mysql_query("select hotel_id,hotel_name from hotel_master where hotel_id='$row_hotel[pickup]'"));
                        $pickup = $row['hotel_name'];
                      }
                      else{
                        $row = mysql_fetch_assoc(mysql_query("select airport_name, airport_code, airport_id from airport_master where airport_id='$row_hotel[pickup]'"));
                        $airport_nam = clean($row['airport_name']);
                        $airport_code = clean($row['airport_code']);
                        $pickup = $airport_nam." (".$airport_code.")";
                      }
                      //Drop-off
                      if($row_hotel['drop_type'] == 'city'){
                        $row = mysql_fetch_assoc(mysql_query("select city_id,city_name from city_master where city_id='$row_hotel[drop]'"));
                        $drop = $row['city_name'];
                      }
                      else if($row_hotel['drop_type'] == 'hotel'){
                        $row = mysql_fetch_assoc(mysql_query("select hotel_id,hotel_name from hotel_master where hotel_id='$row_hotel[drop]'"));
                        $drop = $row['hotel_name'];
                      }
                      else{
                        $row = mysql_fetch_assoc(mysql_query("select airport_name, airport_code, airport_id from airport_master where airport_id='$row_hotel[drop]'"));
                        $airport_nam = clean($row['airport_name']);
                        $airport_code = clean($row['airport_code']);
                        $drop = $airport_nam." (".$airport_code.")";
                      }
                      ?>
                      <tr>
                        <td><?= $transport_name['vehicle_name'].$similar_text ?></td>
                        <td><?= date('d-m-Y', strtotime($row_hotel['start_date'])) ?></td>
                        <td><?= $pickup ?></td>
                        <td><?= $drop ?></td>
                        <td><?= $row_hotel['vehicle_count'] ?></td>
                      </tr>
                    <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <?php }?>
          <!-- Excursion -->
          <?php
          if($sq_exc_count>0){ ?>
          <section class="transportDetails main_block side_pad mg_tp_30">
            <div class="row">
            <div class="col-md-4">
                <div class="transportImg">
                  <img src="<?= BASE_URL ?>images/quotation/excursion.png" class="img-responsive">
                </div>
              </div>
              <div class="col-md-8">
                <div class="table-responsive mg_tp_30">
                  <table class="table table-bordered no-marg" id="tbl_emp_list">
                    <thead>
                      <tr class="table-heading-row">
                        <th>City </th>
                        <th>Activity Date</th>
                        <th>Activity Name</th>
                        <th>Transfer Option</th>
                        <th>Cost</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $count = 0;
                      $sq_ex = mysql_query("select * from package_tour_quotation_excursion_entries where quotation_id='$quotation_id'");
                      while($row_ex = mysql_fetch_assoc($sq_ex)){
                        $sq_city = mysql_fetch_assoc(mysql_query("select * from city_master where city_id='$row_ex[city_name]'"));
                        $sq_ex_name = mysql_fetch_assoc(mysql_query("select * from excursion_master_tariff where entry_id='$row_ex[excursion_name]'"));
                        ?>
                        <tr>
                          <td><?= $sq_city['city_name'] ?></td>
                          <td><?= get_datetime_user($row_ex['exc_date']) ?></td>
                          <td><?= $sq_ex_name['excursion_name'] ?></td>
                          <td><?= $row_ex['transfer_option'] ?></td>
                          <td><?= $row_ex['excursion_amount'] ?></td>
                        </tr>
                        <?php }	?>
                    </tbody>
                  </table>
                </div>
              </div>  
              
            </div>
          </section>
          <?php }?>

    </section>
  <?php } ?>

  <?php if($sq_quotation['inclusions'] != '' || $sq_quotation['exclusions'] != ''){ ?>
    <section class="incluExcluTerms main_block">
      <!-- Inclusion Exclusion -->
      <div class="incluExclu main_block">
          <div class="imgPanel">
            <img src="<?= BASE_URL ?>images/quotation/inexBg.jpg" class="img-responsive">
            <div class="imgPanelOvelay"></div>
          </div>
          <div class="contenPanel main_block side_pad mg_tp_30">
            <div class="row">
              <?php if($sq_quotation['inclusions'] != ''){ ?>
                <div class="col-md-12">
                  <h3 class="lgTitle">Inclusions</h3>
                  <pre class="real_text"><?= $sq_quotation['inclusions'] ?></pre>
                </div>
              <?php } ?>
            </div>
            <div class="row mg_tp_10">
              <?php if($sq_quotation['exclusions'] != ''){ ?>
              <div class="col-md-12">
                <h3 class="lgTitle">Exclusions</h3>
                <pre class="real_text"><?= $sq_quotation['exclusions'] ?></pre>
              </div>
              <?php } ?>
            </div>
          </div>
      </div>
    </section>
    <?php } ?>
    <?php if($sq_terms_cond['terms_and_conditions'] != ''){ ?>
      <section class="incluExcluTerms main_block">
        <!-- Terms and Conditions -->
        <div class="termsCondition main_block side_pad mg_tp_30">
            <h3 class="nrmTitle">Terms and Conditions</h3>
            <pre class="real_text"><?php echo $sq_terms_cond['terms_and_conditions']; ?></pre>
        </div>
        <div class="termsCondition main_block side_pad mg_tp_10">
            <pre class="real_text"><?php echo $quot_note; ?></pre>
        </div>
      </section>
    <?php } ?>

    <!-- Ending Page -->
    <section class="incluExcluTerms main_block mg_tp_20">
      
      <!-- Guest Detail -->
      <div class="guestDetail main_block text-center">
            <h3 class="costBankTitle">Total Guest</h3>
            <img src="<?= BASE_URL ?>images/quotation/guestCount.png" class="img-responsive">
            <span class="guestCount adultCount">Adult : <?= $sq_quotation['total_adult'] ?></span>
            <span class="guestCount infantCount">CWB : <?= $sq_quotation['children_with_bed'] ?></span>
            <span class="guestCount infantCount">CWOB : <?= $sq_quotation['children_without_bed'] ?></span>
            <span class="guestCount infantCount">Infant : <?= $sq_quotation['total_infant'] ?></span>
      </div>
      <?php
      $basic_cost = $sq_costing['basic_amount'];
      $service_charge = $sq_costing['service_charge'];
      $tour_cost= $basic_cost +$service_charge;
      $service_tax_amount = 0;
      $tax_show = '';
      $bsmValues = json_decode($sq_costing['bsmValues']);
      
      if($sq_costing['service_tax_subtotal'] !== 0.00 && ($sq_costing['service_tax_subtotal']) !== ''){
        $service_tax_subtotal1 = explode(',',$sq_costing['service_tax_subtotal']);
        for($i=0;$i<sizeof($service_tax_subtotal1);$i++){
          $service_tax = explode(':',$service_tax_subtotal1[$i]);
          $service_tax_amount +=  $service_tax[2];
          $name .= $service_tax[0] . ' ';
          $percent = $service_tax[1];
        }
      }
      if($bsmValues[0]->service != ''){   //inclusive service charge
        $newBasic = $tour_cost + $service_tax_amount;
        $tax_show = '';
      }
      else{
        // $tax_show = $service_tax_amount;
        $tax_show =  $name . $percent. ($service_tax_amount);
        $newBasic = $tour_cost;
      }

      ////////////Basic Amount Rules
      if($bsmValues[0]->basic != ''){ //inclusive markup
        $newBasic = $tour_cost + $service_tax_amount;
        $tax_show = '';
      }

      $quotation_cost = $basic_cost +$service_charge+ $service_tax_amount+ $sq_quotation['train_cost'] + $sq_quotation['cruise_cost']+ $sq_quotation['flight_cost'] + $sq_quotation['visa_cost'] + $sq_quotation['guide_cost'] + $sq_quotation['misc_cost'];
      ?>
      <!-- Costing & Bank Detail -->
      <div class="costBankSec main_block mg_tp_20">
        <div class="costBankInner main_block side_pad mg_tp_20 mg_bt_20">
          <div class="row">
            <!-- Costing -->
            <div class="col-md-6">
              <h3 class="costBankTitle text-center">COSTING DETAILS</h3>
              <!-- Group Costing -->
              <?php if($sq_quotation['costing_type'] == 1){ ?>
              <div class="row mg_bt_20">
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/tourCost.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= round($newBasic) ?></h4>
                  <p>TOUR COST</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/tax.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $tax_show ?></h4>
                  <p>TAX</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/travelCost.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= number_format($sq_quotation['train_cost'] + $sq_quotation['flight_cost'] + $sq_quotation['cruise_cost'] + $sq_quotation['visa_cost']+ $sq_quotation['guide_cost']+ $sq_quotation['misc_cost'],2) ?></h4>
                  <p>TRAVEL COST</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 text-center mg_bt_30"></div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/quotationCost.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= round($quotation_cost) ?></h4>
                  <p>QUOTATION COST</p>
                </div>
              </div>
              <?php }
              else{ ?>
              <!-- Group Costing End -->
              <!-- Per Person Costing -->
              <div class="row mg_bt_20">
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/adultCost.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $sq_costing['adult_cost'] ?></h4>
                  <p>ADULT COST</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/child-with-bed.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?php echo $sq_costing['child_with']; ?></h4>
                  <p>CWB</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/child-with-out-bed.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $sq_costing['child_without'] ?></h4>
                  <p>CWOB</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/infantCost.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?php echo $sq_costing['infant_cost']; ?></h4>
                  <p>INFANT COST</p>
                </div>
              </div>
              <?php } ?>
              <!-- Per Person Costing End -->
            </div>
            <!-- Bank Detail -->
            <div class="col-md-6" style="border-left:1px solid #dddddd;">
              <h3 class="costBankTitle text-center">BANK DETAILS</h3>
              <div class="row mg_bt_20">
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/bankName.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $bank_name_setting ?></h4>
                  <p>BANK NAME</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/branchName.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $bank_branch_name ?></h4>
                  <p>BRANCH</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accName.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $acc_name ?></h4>
                  <p>A/C NAME</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/accNumber.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $bank_acc_no ?></h4>
                  <p>A/C NO</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $bank_ifsc_code ?></h4>
                  <p>IFSC</p>
                </div>
                <div class="col-md-4 text-center">
                  <div class="icon"><img src="<?= BASE_URL ?>images/quotation/p4/code.png" class="img-responsive"></div>
                  <h4 class="no-marg"><?= $bank_swift_code ?></h4>
                  <p>SWIFT CODE</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- contact-detail -->
      <!-- contact-detail -->
  <section class="contactsec main_block">
    <div class="row">
      <div class="col-md-7">
        <div class="contactTitlePanel text-center">
          <!-- <h3>Contact Us</h3> -->
          <img src="<?= BASE_URL ?>images/quotation/contactImg.jpg" class="img-responsive">
          <p class="no-marg"><?php echo $app_website; ?></p>
        </div>
      </div>
      <div class="col-md-5">
        <?php if($app_address != ''){?>
        <div class="contactBlock main_block side_pad mg_tp_20">
          <div class="cBlockIcon"> <i class="fa fa-map-marker"></i> </div>
          <div class="cBlockContent">
            <h5 class="cTitle">Corporate Office</h5>
            <p class="cBlockData"><?php echo $app_address; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if($app_contact_no != ''){?>
        <div class="contactBlock main_block side_pad mg_tp_20">
          <div class="cBlockIcon"> <i class="fa fa-phone"></i> </div>
          <div class="cBlockContent">
            <h5 class="cTitle">Contact</h5>
            <p class="cBlockData"><?php echo $app_contact_no; ?></p>
          </div>
        </div>
        <?php } ?>
        <?php if($app_email_id != ''){?>
        <div class="contactBlock main_block side_pad mg_tp_20">
          <div class="cBlockIcon"> <i class="fa fa-envelope"></i> </div>
          <div class="cBlockContent">
            <h5 class="cTitle">Email Id</h5>
            <p class="cBlockData"><?php echo $app_email_id; ?></p>
          </div>
        </div>
        <?php } ?>

      </div>
    </div>
  </section>

  </section>

  </body>
</html>