<?php
	$financial_year_id = $_SESSION['financial_year_id'];
	$role = $_SESSION['role'];
	$role_id = $_SESSION['role_id'];
	$emp_id = $_SESSION['emp_id'];
	$branch_admin_id = $_SESSION['branch_admin_id'];
	$branch_status = 'yes';
	$enquiry_count = mysql_num_rows(mysql_query("select * from enquiry_master where status!='Disabled' and financial_year_id='$financial_year_id'"));
	$converted_count = 0;
	$closed_count = 0;
	$infollowup_count = 0;
	$followup_count = 0;

	$sq_enquiry = mysql_query("select * from enquiry_master where status!='Disabled' and financial_year_id='$financial_year_id'");
	while($row_enq = mysql_fetch_assoc($sq_enquiry)){
		$sq_enquiry_entry = mysql_fetch_assoc(mysql_query("select followup_status from enquiry_master_entries where entry_id=(select max(entry_id) as entry_id from enquiry_master_entries where enquiry_id='$row_enq[enquiry_id]')"));
		if($sq_enquiry_entry['followup_status']=="Dropped"){
			$closed_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Converted"){
			$converted_count++;
		}
		if($sq_enquiry_entry['followup_status']=="Active"){
			$followup_count++;
		}
		if($sq_enquiry_entry['followup_status']=="In-Followup"){
			$infollowup_count++;
		}
	}
	?>
	<!-- Followup History Div -->
	<div id="id_proof1"></div>
	<div id="id_proof2"></div>
	<div class="app_panel"> 
	       <div class="dashboard_panel panel-body">

	          <!-- Enquiry widgets -->

	          <div class="dashboard_enqury_widget_panel main_block mg_bt_25">
	            <div class="row">
	                <div class="col-sm-3 col-xs-6">
	                  <div class="single_enquiry_widget main_block blue_enquiry_widget mg_bt_10_sm_xs" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');" >
	                    <div class="col-xs-3 text-left">
	                      <i class="fa fa-cubes"></i>
	                    </div>
	                    <div class="col-xs-9 text-right">
	                      <span class="single_enquiry_widget_amount"><?php echo $enquiry_count; ?></span>
	                    </div>
	                    <div class="col-sm-12 single_enquiry_widget_amount">
	                    Total Enquiries 
	                    </div>
	                  </div>
	                </div>
	                <div class="col-sm-2 col-xs-6">
	                  <div class="single_enquiry_widget main_block yellow_enquiry_widget mg_bt_10_sm_xs" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
	                    <div class="col-xs-3 text-left">
	                      <i class="fa fa-folder-o"></i>
	                    </div>
	                    <div class="col-xs-9 text-right">
	                      <span class="single_enquiry_widget_amount"><?php echo $followup_count; ?></span>
	                    </div>
	                    <div class="col-sm-12 single_enquiry_widget_amount">
	                      Active
	                    </div>
	                  </div>
	                </div>
					<div class="col-sm-2 col-xs-6">
	                  <div class="single_enquiry_widget main_block gray_enquiry_widget mg_bt_10_sm_xs" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');" >
	                    <div class="col-xs-3 text-left">
	                      <i class="fa fa-folder-open-o"></i>
	                    </div>
	                    <div class="col-xs-9 text-right">
	                      <span class="single_enquiry_widget_amount"><?php echo $infollowup_count; ?></span>
	                    </div>
	                    <div class="col-sm-12 single_enquiry_widget_amount">
	                    In-Followup 
	                    </div>
	                  </div>
	                </div>
	                <div class="col-sm-2 col-xs-6">
	                  <div class="single_enquiry_widget main_block green_enquiry_widget" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
	                    <div class="col-xs-3 text-left">
	                      <i class="fa fa-check-square-o"></i>
	                    </div>
	                    <div class="col-xs-9 text-right">
	                      <span class="single_enquiry_widget_amount"><?php echo $converted_count; ?></span>
	                    </div>
	                    <div class="col-sm-12 single_enquiry_widget_amount">
	                      Converted
	                    </div>
	                  </div>
	                </div>
	                <div class="col-sm-3 col-xs-6">
	                  <div class="single_enquiry_widget main_block red_enquiry_widget" onclick="window.open('<?= BASE_URL ?>view/attractions_offers_enquiry/enquiry/index.php', 'My Window');">
	                    <div class="col-xs-3 text-left">
						<i class="fa fa-trash-o" aria-hidden="true"></i>
	                    </div>
	                    <div class="col-xs-9 text-right">
	                      <span class="single_enquiry_widget_amount"><?php echo $closed_count; ?></span>
	                    </div>
	                    <div class="col-sm-12 single_enquiry_widget_amount">
	                      Dropped Enquiries
	                    </div>
	                  </div>
	                </div>
	            </div>
	          </div>

	          <!-- Enquiry widgets End -->

	          <!-- Sale and Purchase Summary -->
	          <?php 
	          	//Sale
	          	$total_sale = 0;
	            $sq_query = mysql_query("select * from ledger_master where group_sub_id in('87','99','17','3','5','93')");
	            while($row_query = mysql_fetch_assoc($sq_query)){
					$sq_trans_credit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Credit' and gl_id='$row_query[ledger_id]' and row_specification='sales' and financial_year_id='$financial_year_id'"));
					$credit += ($sq_trans_credit['sum']=="") ? 0 : $sq_trans_credit['sum'];			

					$sq_trans_debit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Debit' and gl_id='$row_query[ledger_id]'  and row_specification='sales' and financial_year_id='$financial_year_id'"));
					$debit += ($sq_trans_debit['sum']=="") ? 0 : $sq_trans_debit['sum'];
				}
				if($debit>$credit){
					$balance1 =  $debit - $credit;
				}
				else{
					$balance1 =  $credit - $debit;	
				}
				$total_sale = $balance1;
	            

	            //Sale Return
	            $total_sale_return = 0; $credit =0; $debit = 0;
	            $sq_query = mysql_query("select * from ledger_master where group_sub_id='89'");
	            while($row_query = mysql_fetch_assoc($sq_query)){
					$sq_trans_credit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Credit' and gl_id='$row_query[ledger_id]' and financial_year_id='$financial_year_id'"));
					$credit += ($sq_trans_credit['sum']=="") ? 0 : $sq_trans_credit['sum'];

					$sq_trans_debit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Debit' and gl_id='$row_query[ledger_id]' and financial_year_id='$financial_year_id'"));
					$debit += ($sq_trans_debit['sum']=="") ? 0 : $sq_trans_debit['sum'];			
				}
				if($debit>$credit){
					$balance =  $debit - $credit;
				}
				else{
					$balance =  $credit - $debit;	
				}
	          	$total_sale_return = $balance;
	            //sale - sale return(cancel)
	            $actual_sale = $total_sale - $total_sale_return; 

	            //Receipts
	          	$total_rec = 0; $credit1 =0; $debit1 = 0;
	            $sq_query = mysql_query("select * from ledger_master where group_sub_id='24'");
	            while($row_query = mysql_fetch_assoc($sq_query)){
	            	//bank amount
					$sq_trans_credit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Credit' and gl_id='$row_query[ledger_id]' and row_specification='sales' and financial_year_id='$financial_year_id' and clearance_status!='Cancelled'"));
					$credit1 += ($sq_trans_credit['sum']=="") ? 0 : $sq_trans_credit['sum'];

					$sq_trans_debit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Debit' and gl_id='$row_query[ledger_id]' and row_specification='sales' and financial_year_id='$financial_year_id' and clearance_status!='Cancelled'"));
					$debit1 += ($sq_trans_debit['sum']=="") ? 0 : $sq_trans_debit['sum'];
				}
				if($debit1>$credit1){
					$balance =  $debit1 - $credit1;
					$total_rec += $balance;
				}
				else{
					$balance =  $credit1 - $debit1;
					$total_rec -= $balance;
				}
	            
	            //cash amount
	            $row_query = mysql_fetch_assoc(mysql_query("select * from ledger_master where ledger_id='20' and row_specification='sales'"));
				$sq_trans_credit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Credit' and gl_id='20' and row_specification='sales' and financial_year_id='$financial_year_id'"));
				$credit = ($sq_trans_credit['sum']=="") ? 0 : $sq_trans_credit['sum'];

				$sq_trans_debit = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as sum from finance_transaction_master where payment_side='Debit' and gl_id='20' and row_specification='sales' and financial_year_id='$financial_year_id'"));
				$debit = ($sq_trans_debit['sum']=="") ? 0 : $sq_trans_debit['sum'];			
				
				if($debit>$credit){
					$balance =  $debit - $credit;
					$total_rec += $balance;
				}
				else{
					$balance =  $credit - $debit;
					$total_rec -= $balance;
				}
			?>
	          <div class="dashboard_widget_panel main_block mg_bt_25">
	            <div class="row">

	              <div class="col-md-6">
	                <div class="dashboard_widget main_block mg_bt_10_xs">
	                  <div class="dashboard_widget_title_panel main_block widget_red_title">
	                    <div class="dashboard_widget_icon">
	                      <i class="fa fa-tag" aria-hidden="true"></i>
	                    </div>
	                    <div class="dashboard_widget_title_text" onclick="window.open('<?= BASE_URL ?>view/finance_master/reports/index.php', 'My Window');">
	                      <h3> Sale Summary</a></h3>
	                    </div>
	                  </div>
	                  <div class="dashboard_widget_conetent_panel main_block">
	                    <div class="col-sm-6" style="border-right: 1px solid #e6e4e5">
	                      <div class="dashboard_widget_single_conetent">
	                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($actual_sale, 2); ?></span>
	                        <span class="dashboard_widget_conetent_text widget_blue_text">Sale</span>
	                      </div>
	                    </div>
	                    <div class="col-sm-6">
	                      <div class="dashboard_widget_single_conetent">
	                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($total_rec, 2); ?></span>
	                        <span class="dashboard_widget_conetent_text widget_green_text">Received</span>
	                      </div>
	                    </div>
	                  </div>  
	                </div>
	              </div>

	              <?php 
			        $sq_puchase = mysql_fetch_assoc(mysql_query("select sum(net_total) as net_total from vendor_estimate where status != 'cancel' and financial_year_id='$financial_year_id' "));
		          	$actual_purchase = $sq_puchase['net_total'];
		          	$sq_puchase_p = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from vendor_payment_master where financial_year_id='$financial_year_id' and clearance_status!='Cancelled'"));
		          	$sq_puchase_r = mysql_fetch_assoc(mysql_query("select sum(payment_amount) as payment_amount from vendor_refund_master where financial_year_id='$financial_year_id' and clearance_status!='Cancelled'"));
		          	$total_rec = $sq_puchase_p['payment_amount'] - $sq_puchase_r['payment_amount'];
				  ?>
	              <div class="col-md-6">
	                <div class="dashboard_widget main_block">
	                  <div class="dashboard_widget_title_panel main_block widget_purp_title">
	                    <div class="dashboard_widget_icon">
	                      <i class="fa fa-shopping-cart" aria-hidden="true"></i>
	                    </div>
	                    <div class="dashboard_widget_title_text" onclick="window.open('<?= BASE_URL ?>view/finance_master/reports/index.php', 'My Window');">
	                      <h3>Purchase Summary</a></h3>
	                    </div>
	                  </div>
	                  <div class="dashboard_widget_conetent_panel main_block">
	                    <div class="col-sm-6" style="border-right: 1px solid #e6e4e5">
	                      <div class="dashboard_widget_single_conetent">
	                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($actual_purchase, 2); ?></span>
	                        <span class="dashboard_widget_conetent_text widget_blue_text">Purchase</span>
	                      </div>
	                    </div>
	                    <div class="col-sm-6">
	                      <div class="dashboard_widget_single_conetent">
	                        <span class="dashboard_widget_conetent_amount"><?php echo number_format($total_rec, 2); ?></span>
	                        <span class="dashboard_widget_conetent_text widget_green_text">Paid</span>
	                      </div>
	                    </div>
	                  </div>  
	                </div>
	              </div>
	            </div>
	          </div>
	          <!-- Sale and Purchase Summary End-->
	          <!-- dashboard_tab -->
		       <div class="row">
		       	<div class="col-md-12">
		       		<div class="dashboard_tab text-center main_block">
			          <!-- Nav tabs -->
			          <ul class="nav nav-tabs responsive" role="tablist">
					    <li role="presentation" class="active"><a href="#enquiry_tab" aria-controls="enquiry_tab" role="tab" data-toggle="tab">Todays Followups</a></li>
			          	<li role="presentation" ><a href="#oncoming_tab" aria-controls="oncoming_tab" role="tab" data-toggle="tab">Ongoing Tours</a></li>
			            <li role="presentation"><a href="#upcoming_tab" aria-controls="upcoming_tab" role="tab" data-toggle="tab">Upcoming Tours</a></li>
			            <li role="presentation"><a href="#fit_tab" aria-controls="fit_tab" role="tab" data-toggle="tab">Package Tours</a></li>
			            <li role="presentation"><a href="#git_tab" aria-controls="git_tab" role="tab" data-toggle="tab">Group Tours</a></li>
			          </ul>

			          <!-- Tab panes -->
			          <div class="tab-content responsive main_block">

	           			<!-- Ongoing  -->
			            <div role="tabpanel" class="tab-pane" id="oncoming_tab">
				          <?php
						  global $encrypt_decrypt,$secret_key;
				         	$count = 1;
							$today = date('Y-m-d');
							$today1 = date('Y-m-d H:i:s');
				          ?>
				          <div class="dashboard_table dashboard_table_panel main_block">
				            <div class="row text-left">
				              <div class="col-md-12">
				                <div class="dashboard_table_body main_block">
				                  <div class="col-md-12 no-pad table_verflow"> 
				                    <div class="table-responsive">
				                      <table class="table table-hover" style="margin: 0 !important;border: 0;">
				                        <thead>
				                          <tr class="table-heading-row">
				                            <th>S_No.</th>
											<th>Tour_Type</th>
				                         	<th>Tour_Name</th>
				                         	<th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				                            <th>Customer_Name</th>
				                            <th>Mobile</th>
				                            <th>Owned&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>Client_Feedback</th>
				                          </tr>
				                        </thead>
				                        <tbody>
									 <?php
										$query1 = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and tour_from_date <= '$today' and tour_to_date >= '$today'";
				          				$sq_query = mysql_query($query1);
				          				while($row_query=mysql_fetch_assoc($sq_query)){
											$sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
											$sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));
											if($sq_cancel_count != $sq_count){
				          					$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
											  $sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
											  $name = ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'];
										    ?>
				                            <tr>
				                              <td><?php echo $count++; ?></td>
											  <td>Package Booking</td>
				                              <td><?php echo $row_query['tour_name']; ?></td>
				                              <td><?= get_date_user($row_query['tour_from_date']).' To '.get_date_user($row_query['tour_to_date']); ?></td>
				                              <td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
				                              <td><?php echo $row_query['mobile_no']; ?></td>
				                              <td><?= ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
											  <td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $row_query['booking_id'] ?>,'Package Booking',<?= $row_query['emp_id']?>,'<?= $contact_no ?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
				                            </tr>
				                     	<?php } } ?> 
										<!-- Hotel Booking -->
										<?php
										
										$query1 = "select *	from  hotel_booking_entries where status!='Cancel' and check_in<= '$today1' and check_out >= '$today1'
										group by booking_id";
				          				$sq_query = mysql_query($query1);
				          				while($row_query=mysql_fetch_assoc($sq_query)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from hotel_booking_master where booking_id = '$row_query[booking_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Hotel Booking</td>
												<td><?= ($row_query['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query['check_in']).' To '.get_date_user($row_query['check_out']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_hotel['booking_id'] ?>,'Hotel Booking',<?= $sq_hotel['emp_id']?>,'<?= $contact_no?>', ,'<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>	
												</tr>
											<?php } ?>

                                       <!-- flight Booking -->
										<?php
										$query_train = "select *	from  ticket_trip_entries where departure_datetime<= '$today1' and arrival_datetime>= '$today1'
										group by ticket_id";
				          				$sq_query1 = mysql_query($query_train);
				          				while($row_query1=mysql_fetch_assoc($sq_query1)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id = '$row_query1[ticket_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Flight Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['departure_datetime']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_hotel['ticket_id'] ?>,'Flight Booking',<?= $sq_hotel['emp_id']?>,'<?= $contact_no?>', '<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
										<!-- Train Booking -->
										<?php
										$query_train = "select *	from  train_ticket_master_trip_entries where DATE(travel_datetime)<= '$today1' and DATE(arriving_datetime)>= '$today1'
										group by train_ticket_id";
				          				$sq_query_train = mysql_query($query_train);
				          				while($row_query1=mysql_fetch_assoc($sq_query_train)){
											
											$sq_train = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id = '$row_query1[train_ticket_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_train[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_train[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Train Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['travel_datetime'])?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_train['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_train['train_ticket_id'] ?>,'Train Booking',<?= $sq_train['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>' )" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
										
										<!-- Bus Booking -->
										<?php
										
										$query_bus = "select *	from  bus_booking_entries where DATE(date_of_journey)	= '$today'	group by booking_id";
									
				          				$sq_query_bus = mysql_query($query_bus);
				          				while($row_query1=mysql_fetch_assoc($sq_query_bus)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id = '$row_query1[booking_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Bus Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['date_of_journey']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_hotel['booking_id'] ?>,'Bus Booking',<?= $sq_hotel['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
										<!-- Excursion Booking -->
										<?php
										
										$add7days1 = date('Y-m-d', strtotime('+7 days'));
									
										$query_exc = "select *	from  excursion_master_entries where DATE(exc_date) ='$today'	group by exc_id";
									
				          				$sq_query_exc = mysql_query($query_exc);
				          				while($row_query1=mysql_fetch_assoc($sq_query_exc)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from 	excursion_master where exc_id = '$row_query1[exc_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Excursion Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['exc_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_hotel['exc_id'] ?>,'Excursion Booking',<?= $sq_hotel['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
										<!-- Car Rental Booking -->
										<?php
										$query_car = "select * from car_rental_booking  where traveling_date='$today' and travel_type ='Local'";
										
				          				$sq_query_car = mysql_query($query_car);
				          				while($row_query1=mysql_fetch_assoc($sq_query_car)){
											
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query1[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query1[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Car Rental Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['from_date']).' To '.get_date_user($row_query1['to_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query1['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $row_query1['booking_id'] ?>,'Car Rental Booking',<?= $row_query1['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
										<?php } ?>
										<!-- Car Rental Booking -->
										<?php
										$query_car = "select * from car_rental_booking  where traveling_date='$today' and travel_type ='Outstation'";
										
				          				$sq_query_car = mysql_query($query_car);
				          				while($row_query1=mysql_fetch_assoc($sq_query_car)){
											
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query1[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query1[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Car Rental Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['traveling_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query1['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $row_query1['booking_id'] ?>,'Car Rental Booking',<?= $row_query1['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
										<?php } ?>
										<!-- Group Booking -->
										<?php
										$query_grp = "select * from tour_groups where from_date<='$today' and to_date>='$today'";

				          				$sq_query_grp = mysql_query($query_grp);
				          				while($row_query1=mysql_fetch_assoc($sq_query_grp)){

											$sq = mysql_query("select * from tourwise_traveler_details where tour_id='$row_query1[tour_id]' ");
                                
                                			while($row_query = mysql_fetch_assoc($sq)){
											
											$sq_booking = mysql_fetch_assoc(mysql_query("select * from tour_groups where tour_id = '$row_query[tour_id]'"));
											$sq_booking1 = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id = '$row_query[tour_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Group Booking</td>
												<td><?php echo $sq_booking1['tour_name']; ?></td>
												<td><?= get_date_user($sq_booking['from_date']).' To '.get_date_user($sq_booking['to_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query1['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $row_query1['id'] ?>,'Group Booking',<?= $row_query1['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
										<?php } }?>
										<!-- Visa Booking -->
										<?php
										
										$query_visa = "select *	from  visa_master_entries where appointment_date='$today'	group by visa_id";
				          				$sq_query_visa = mysql_query($query_visa);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_visa)){
											$sq_visa = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id = '$row_query_visa[visa_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_visa[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Visa Booking</td>
												<td><?php echo $row_query_visa['visa_country_name']; ?></td>
												<td><?= get_date_user($row_query_visa['appointment_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_visa['visa_id'] ?>,'Visa Booking',<?= $sq_visa['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
											<!-- Passport Booking -->
										<?php


										$query_pass = "select *	from  passport_master_entries where appointment_date='$today'	group by passport_id";
				          				$sq_query_pass = mysql_query($query_pass);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_pass)){
											$sq_visa = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id = '$row_query_visa[passport_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_visa[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Passport Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query_visa['appointment_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td><button class="btn btn-info btn-sm" onclick="send_sms(<?= $sq_visa['passport_id'] ?>,'Passport Booking',<?= $sq_visa['emp_id']?>,'<?= $contact_no?>','<?= $sq_cust['first_name'] ?>')" title="Send SMS"><i class="fa fa-paper-plane-o"></i></button></td>
												</tr>
											<?php } ?>
				                        </tbody>
				                      </table>
				                    </div> 
				                  </div>
				                </div>
				              </div>
				            </div>
				          </div>
			            </div>
				        <!-- Ongoing FIT Tour summary End -->

						<!-- Upcoming FIT Tours -->
			            <div role="tabpanel" class="tab-pane" id="upcoming_tab">
				          <?php 
				         	$count = 1;
				         	$today = date('Y-m-d-h-i-s');
							$add7days = date('Y-m-d-h-i-s', strtotime('+7 days'));
							$today1 = date('Y-m-d');
							$add7days = date('Y-m-d', strtotime('+7 days'));
				          ?>
				          <div class="dashboard_table dashboard_table_panel main_block">
				            <div class="row text-left">
				              
				              <div class="col-md-12">
				                <div class="dashboard_table_body main_block">
				                  <div class="col-md-12 no-pad table_verflow"> 
				                    <div class="table-responsive">
				                      <table class="table table-hover" style="margin: 0 !important;border: 0;">
				                        <thead>
				                          <tr class="table-heading-row">
				                            <th>S_No.</th>
											<th>Tour_Type</th>
				                         	<th>Tour_Name</th>
				                         	<th>Tour_Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				                            <th>Customer_Name</th>
				                            <th>Mobile</th>
				                            <th>Owned&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
											<th>Checklist</th>
											<th>Checklist_Status</th>
											<th>Send_Wishes</th>
				                          </tr>
				                        </thead>
				                        <tbody>
										<!-- //package Booking -->
										<?php
										$query = "select * from package_tour_booking_master where tour_status!='Disabled' and financial_year_id='$financial_year_id' and tour_from_date >= '$today1'";
										
				          				$sq_query = mysql_query($query);
				          				while($row_query=mysql_fetch_assoc($sq_query)){
											$sq_cancel_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]' and status='Cancel'"));
											$sq_count = mysql_num_rows(mysql_query("select * from package_travelers_details where booking_id='$row_query[booking_id]'"));

											if($row_query['dest_id']=='0'){
												$sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id='$row_query[package_id]'"));
												$dest_id = $sq_package['dest_id'];
											}else{
												$dest_id = $row_query['dest_id'];
											}
											$sq_check = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Package Tour' and destination_name='$dest_id'"));

											$sq_task = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check[entity_id]'"));
																						
											$sq_complete_task = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query[booking_id]' and tour_type='Package Tour' and status='Completed'"));
											if($sq_task==$sq_complete_task && $sq_task!=0 && $sq_complete_task!=0 ){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task==0 && $sq_complete_task==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
												
											if($sq_cancel_count != $sq_count){
												$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
												$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Package Tour</td>
												<td><?= ($row_query['tour_name']=='')?'NA':$row_query['tour_name'] ?></td>
												<td><?= get_date_user($row_query['tour_from_date']).' To '.get_date_user($row_query['tour_to_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $row_query['mobile_no']; ?></td>
												<td><?= ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query['booking_id']; ?>','Package Tour');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>
												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $row_query[mobile_no] ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } } ?>
										<!-- Hotel Booking -->
										<?php
										$query1 = "select * from  hotel_booking_entries where status!='Cancel' and check_in >= '$today' group by booking_id";
										$sq_query = mysql_query($query1);
										while($row_query=mysql_fetch_assoc($sq_query)){
											
											$sq = mysql_query("select * from hotel_booking_master where booking_id = '$row_query[booking_id]'");
											while($sq_hotel = mysql_fetch_assoc($sq)){
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));

											$sq_check_hotel = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Hotel Booking' "));
											
											$sq_task_hotel = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_hotel[entity_id]'"));
																						
											$sq_complete_task_hotel = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query[booking_id]' and tour_type='Hotel Booking' and status='Completed'"));
											if($sq_task_hotel==$sq_complete_task_hotel && $sq_task_hotel!=0 && $sq_complete_task_hotel!=0 ){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task_hotel==0 && $sq_complete_task_hotel==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task_hotel>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Hotel Booking</td>
												<td><?= ($row_query['tour_name']=='')?'NA':$row_query['tour_name'] ?></td>
												<td><?= get_date_user($row_query['check_in']).' To '.get_date_user($row_query['check_out']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query['booking_id']; ?>','Hotel Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></i></button></td>
												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
											</tr>
											<?php } } ?>
										<!-- Flight Booking -->
										<?php
										$query_flight = "select *
										from  ticket_trip_entries where departure_datetime >= '$today'
										group by ticket_id";
				          				$sq_query1 = mysql_query($query_flight);
				          				while($row_query1=mysql_fetch_assoc($sq_query1)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from ticket_master where ticket_id = '$row_query1[ticket_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));

											$sq_check_flight = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Flight Booking' "));
											
											$sq_task_flight = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_flight[entity_id]'"));
																						
											$sq_complete_task_flight = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[ticket_id]' and tour_type='Flight Booking' and status='Completed'"));
											if($sq_task_flight==$sq_complete_task_flight && $sq_task_flight!=0 && $sq_complete_task_flight!=0 ){
												 $bg_color = 'rgba(52,195,143,.18);';
												 $status = 'Completed';
												 $text_color = '#34c38f;';
												}else if($sq_task_flight==0 && $sq_complete_task_flight==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_flight>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Flight Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['departure_datetime']).' To '.get_date_user($row_query1['arrival_datetime']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['ticket_id']; ?>','Flight Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>
												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
										<!-- Train Booking -->
										<?php
										$sq_query_train = mysql_query("select * from  train_ticket_master_trip_entries where DATE(travel_datetime) >= '$today' group by train_ticket_id");
										while($row_query1=mysql_fetch_assoc($sq_query_train)){

											$pass_count = mysql_num_rows(mysql_query("select * from  train_ticket_master_entries where train_ticket_id='$row_query1[train_ticket_id]'"));
											$cancel_count = mysql_num_rows(mysql_query("select * from  train_ticket_master_entries where train_ticket_id='$row_query1[train_ticket_id]' and status='Cancel'"));
											if($pass_count!=$cancel_count){
												$sq_train = mysql_fetch_assoc(mysql_query("select * from train_ticket_master where train_ticket_id = '$row_query1[train_ticket_id]'"));
												$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_train[customer_id]'"));
												$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
												$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_train[emp_id]'"));

												$sq_check_train = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Train Booking'"));

												$sq_task_train = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_train[entity_id]'"));
																							
												$sq_complete_task_train = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[train_ticket_id]' and tour_type='Train Booking' and status='Completed'"));
												if($sq_task_train==$sq_complete_task_train && $sq_task_train!=0 && $sq_complete_task_train!=0 ){
														$bg_color = 'rgba(52,195,143,.18);';
														$status = 'Completed';
														$text_color = '#34c38f;';
												}else if($sq_task_train==0 && $sq_complete_task_train==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_train>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
												?>
													<tr class="<?= $bg ?>">
													<td><?php echo $count++; ?></td>
													<td>Train Booking</td>
													<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
													<td><?= get_date_user($row_query1['travel_datetime'])?></td>
													<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
													<td><?php echo $contact_no; ?></td>
													<td><?= ($sq_train['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
													
													<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['train_ticket_id']; ?>','Train Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>
													
													<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

													<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
													</tr>
											<?php
											}
										} ?>
										<!-- Bus Booking -->
										<?php										
										$query_bus = "select * from bus_booking_entries where DATE(date_of_journey) >= '$today' and status!='Cancel' group by booking_id";
										$sq_query_bus = mysql_query($query_bus);
										while($row_query1=mysql_fetch_assoc($sq_query_bus)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from bus_booking_master where booking_id = '$row_query1[booking_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));


											$sq_check_bus = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Bus Booking'"));

											$sq_task_bus = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus[entity_id]'"));
																						
											$sq_complete_task_bus = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[booking_id]' and tour_type='Bus Booking' and status='Completed'"));
											if($sq_task_bus==$sq_complete_task_bus && $sq_task_bus!=0 && $sq_complete_task_bus!=0 ){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task_bus==0 && $sq_complete_task_bus==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task_bus>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Bus Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['date_of_journey']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['booking_id']; ?>','Bus Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
										<!-- Excursion Booking -->
										<?php
										$today1 = date('Y-m-d');
										$add7days1 = date('Y-m-d', strtotime('+7 days'));

										$query_exc = "select *
										from  excursion_master_entries where DATE(exc_date) >= '$today1' and status!='Cancel'
										group by exc_id";
				          				$sq_query_exc = mysql_query($query_exc);
				          				while($row_query1=mysql_fetch_assoc($sq_query_exc)){
											
											$sq_hotel = mysql_fetch_assoc(mysql_query("select * from 	excursion_master where exc_id = '$row_query1[exc_id]'"));
											$sq_city = mysql_fetch_assoc(mysql_query("select * from 	city_master where city_id = '$row_query1[city_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_hotel[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_hotel[emp_id]'"));


											$sq_check_bus1 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Excursion Booking'"));

											$sq_task_bus1 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus1[entity_id]'"));
																						
											$sq_complete_task_bus1 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[exc_id]' and tour_type='Excursion Booking' and status='Completed'"));
											if($sq_task_bus1==$sq_complete_task_bus1 && $sq_task_bus1!=0 && $sq_complete_task_bus1!=0 ){
												 $bg_color = 'rgba(52,195,143,.18);';
												 $status = 'Completed';
												 $text_color = '#34c38f;';
												}else if($sq_task_bus1==0 && $sq_complete_task_bus1==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_bus1>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Excursion Booking</td>
												<td><?php echo $sq_city['city_name']; ?></td>
												<td><?= get_date_user($row_query1['exc_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_hotel['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['exc_id']; ?>','Excursion Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
											<!-- Car Rental Booking -->
										<?php
										$query_car = "select * from car_rental_booking where DATE(from_date)  >= '$today1' and travel_type ='Local'";

				          				$sq_query_car = mysql_query($query_car);
				          				while($row_query1=mysql_fetch_assoc($sq_query_car)){
											
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query1[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query1[emp_id]'"));

											$sq_check_bus2 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Car Rental Booking'"));

											$sq_task_bus2 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus2[entity_id]'"));
																						
											$sq_complete_task_bus2 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[booking_id]' and tour_type='Car Rental Booking' and status='Completed'"));
											if($sq_task_bus2==$sq_complete_task_bus2 && $sq_task_bus2!=0 && $sq_complete_task_bus2!=0 ){
												 $bg_color = 'rgba(52,195,143,.18);';
												 $status = 'Completed';
												 $text_color = '#34c38f;';
												}else if($sq_task_bus2==0 && $sq_complete_task_bus2==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_bus2>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Car Rental Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['from_date']).' To '.get_date_user($row_query1['to_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query1['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['booking_id']; ?>','Car Rental Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
										<?php } ?>
										<?php
										$query_car = "select * from car_rental_booking where traveling_date  >= '$today1'  and travel_type ='Outstation'";

				          				$sq_query_car = mysql_query($query_car);
				          				while($row_query1=mysql_fetch_assoc($sq_query_car)){
											
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query1[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query1[emp_id]'"));

											$sq_check_bus2 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Car Rental Booking'"));

											$sq_task_bus2 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus2[entity_id]'"));
																						
											$sq_complete_task_bus2 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query1[booking_id]' and tour_type='Car Rental Booking' and status='Completed'"));
											if($sq_task_bus2==$sq_complete_task_bus2 && $sq_task_bus2!=0 && $sq_complete_task_bus2!=0 ){
												 $bg_color = 'rgba(52,195,143,.18);';
												 $status = 'Completed';
												 $text_color = '#34c38f;';
												}else if($sq_task_bus2==0 && $sq_complete_task_bus2==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_bus2>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Car Rental Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query1['traveling_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query1['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query1['booking_id']; ?>','Car Rental Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
										<?php } ?>
										<!-- Group Booking -->
										<?php
											$sq = mysql_query("select * from tourwise_traveler_details where 1 ");
											while($row_query = mysql_fetch_assoc($sq)){
											
											$sq_booking1 = mysql_fetch_assoc(mysql_query("select * from tour_master where tour_id = '$row_query[tour_id]'"));
											$row_grps_count = mysql_num_rows(mysql_query("select * from tour_groups where tour_id = '$row_query[tour_id]' and from_date >= '$today'"));
											if($row_grps_count > 0){
												$row_grps = mysql_fetch_assoc(mysql_query("select * from tour_groups where tour_id = '$row_query[tour_id]' and from_date >= '$today'"));

												$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query[customer_id]'"));
												$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
												$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query[emp_id]'"));
												
												$tour_id = $row_query['tour_id'];
												$dest_id = $sq_booking1['dest_id'];
												$sq_check_bus2 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Group Tour' and destination_name = '$dest_id'"));

												$sq_task_bus2 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus2[entity_id]'"));
																							
												$sq_complete_task_bus2 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query[id]' and tour_type='Group Tour' and status='Completed'"));
												if($sq_task_bus2==$sq_complete_task_bus2 && $sq_task_bus2!=0 && $sq_complete_task_bus2!=0 ){
													$bg_color = 'rgba(52,195,143,.18);';
													$status = 'Completed';
													$text_color = '#34c38f;';
													}else if($sq_task_bus2==0 && $sq_complete_task_bus2==0){
														$bg_color = '';
														$status = '';
														$text_color = '';
													}
													else if($sq_complete_task_bus2>=1){
														$bg_color = 'rgba(241,180,76,.18)';
														$status = 'Ongoing';
														$text_color = '#f1b44c';
													}else{
														$bg_color = 'rgba(244,106,106,.18)';
														$status = 'Not Updated';
														$text_color = '#f46a6a';
													}
												?>
													<tr class="<?= $bg ?>">
													<td><?php echo $count++; ?></td>
													<td>Group Booking</td>
													<td><?php echo $sq_booking1['tour_name']; ?></td>
													<td><?= get_date_user($row_grps['from_date']).' To '.get_date_user($row_grps['to_date']) ?></td>
													<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
													<td><?php echo $contact_no; ?></td>
													<td><?= ($row_query['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
													<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query['id']; ?>','Group Tour');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

													<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

													<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
													</tr>
											<?php 
											}
									}
									 //}?>
										<!-- Visa Booking -->
										<?php
										
										$query_visa = "select * from  visa_master_entries where appointment_date >= '$today1' and status!='Cancel'	group by visa_id";
				          				$sq_query_visa = mysql_query($query_visa);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_visa)){
											$sq_visa = mysql_fetch_assoc(mysql_query("select * from visa_master where visa_id = '$row_query_visa[visa_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_visa[emp_id]'"));

											$sq_check_bus3 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Visa Booking'"));

											$sq_task_bus3 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus3[entity_id]'"));
																						
											$sq_complete_task_bus3 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query_visa[visa_id]' and tour_type='Visa Booking' and status='Completed'"));
											if($sq_task_bus3==$sq_complete_task_bus3 && $sq_task_bus3!=0 && $sq_complete_task_bus3!=0 ){
												 $bg_color = 'rgba(52,195,143,.18);';
												 $status = 'Completed';
												 $text_color = '#34c38f;';
												}else if($sq_task_bus3==0 && $sq_complete_task_bus3==0){
													$bg_color = '';
													$status = '';
													$text_color = '';
												}
												else if($sq_complete_task_bus3>=1){
													$bg_color = 'rgba(241,180,76,.18)';
													$status = 'Ongoing';
													$text_color = '#f1b44c';
												}else{
													$bg_color = 'rgba(244,106,106,.18)';
													$status = 'Not Updated';
													$text_color = '#f46a6a';
												}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Visa Booking</td>
												<td><?php echo $row_query_visa['visa_country_name']; ?></td>
												<td><?= get_date_user($row_query_visa['appointment_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query_visa['visa_id']; ?>','Visa Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
											<!-- Passport Booking -->
										<?php
										$query_pass = "select * from  passport_master_entries where appointment_date >= '$today1'
										group by passport_id";
				          				$sq_query_pass = mysql_query($query_pass);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_pass)){
											$sq_visa = mysql_fetch_assoc(mysql_query("select * from passport_master where passport_id = '$row_query_visa[passport_id]'"));
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$sq_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sq_visa[emp_id]'"));

											$sq_check_bus4 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Passport Booking'"));

											$sq_task_bus4 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus4[entity_id]'"));
																						
											$sq_complete_task_bus4 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query_visa[passport_id]' and tour_type='Passport Booking' and status='Completed'"));
											if($sq_task_bus4==$sq_complete_task_bus4 && $sq_task_bus4!=0 && $sq_complete_task_bus4!=0 ){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task_bus4==0 && $sq_complete_task_bus4==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task_bus4>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Passport Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query_visa['appointment_date']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($sq_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query_visa['passport_id']; ?>','Passport Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
											<!-- Miscellaneous Booking -->
										<?php
										$query_pass = "select * from  miscellaneous_master where created_at >= '$today1'
										order by misc_id desc";
				          				$sq_query_pass = mysql_query($query_pass);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_pass)){
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query_visa[emp_id]'"));

											$sq_check_bus4 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Miscellaneous Booking'"));

											$sq_task_bus4 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus4[entity_id]'"));
																						
											$sq_complete_task_bus4 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query_visa[misc_id]' and tour_type='Miscellaneous Booking' and status='Completed'"));
											if($sq_task_bus4==$sq_complete_task_bus4 && $sq_task_bus4!=0 && $sq_complete_task_bus4!=0){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task_bus4==0 && $sq_complete_task_bus4==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task_bus4>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Miscellaneous Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query_visa['created_at']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query_visa['misc_id']; ?>','Miscellaneous Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
											<!-- Forex Booking -->
										<?php
										$query_pass = "select * from forex_booking_master where DATE(created_at) >= '$today1'
										order by booking_id desc";
				          				$sq_query_pass = mysql_query($query_pass);
				          				while($row_query_visa=mysql_fetch_assoc($sq_query_pass)){
											$sq_cust = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id = '$row_query_visa[customer_id]'"));
											$contact_no = $encrypt_decrypt->fnDecrypt($sq_cust['contact_no'], $secret_key);
											$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$row_query_visa[emp_id]'"));

											$sq_check_bus4 = mysql_fetch_assoc(mysql_query("select * from checklist_entities where entity_for='Forex Booking'"));

											$sq_task_bus4 = mysql_num_rows(mysql_query("select * from  to_do_entries where entity_id='$sq_check_bus4[entity_id]'"));
																						
											$sq_complete_task_bus4 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$row_query_visa[booking_id]' and tour_type='Forex Booking' and status='Completed'"));
											if($sq_task_bus4==$sq_complete_task_bus4 && $sq_task_bus4!=0 && $sq_complete_task_bus4!=0){
												$bg_color = 'rgba(52,195,143,.18);';
												$status = 'Completed';
												$text_color = '#34c38f;';
											}else if($sq_task_bus4==0 && $sq_complete_task_bus4==0){
												$bg_color = '';
												$status = '';
												$text_color = '';
											}
											else if($sq_complete_task_bus4>=1){
												$bg_color = 'rgba(241,180,76,.18)';
												$status = 'Ongoing';
												$text_color = '#f1b44c';
											}else{
												$bg_color = 'rgba(244,106,106,.18)';
												$status = 'Not Updated';
												$text_color = '#f46a6a';
											}
											?>
												<tr class="<?= $bg ?>">
												<td><?php echo $count++; ?></td>
												<td>Forex Booking</td>
												<td><?= ($row_query1['tour_name']=='')?'NA':$row_query1['tour_name'] ?></td>
												<td><?= get_date_user($row_query_visa['created_at']) ?></td>
												<td><?php echo $sq_cust['first_name'].' '.$sq_cust['last_name']; ?></td>
												<td><?php echo $contact_no; ?></td>
												<td><?= ($row_query_visa['emp_id']=='0') ? "Admin" : $sq_emp['first_name'].' '.$sq_emp['last_name'] ?></td>
												<td class="text-center"><button class="btn btn-info btn-sm" onclick="checklist_update('<?php echo $row_query_visa['booking_id']; ?>','Forex Booking');" data-toggle="tooltip" title="Update Checklist" target="_blank"><i class="fa fa-plus"></i></button></td>

												<td class="text-center"><h6 style="width: 90px;height: 30px;border-radius: 20px;font-size: 12px;line-height: 21px;text-align: center;background:<?= $bg_color ?>;padding:5px;color:<?= $text_color?>"><?= $status ?></h6></td>

												<td class="text-center"><button class="btn btn-info btn-sm" onclick="whatsapp_wishes('<?= $contact_no ?>','<?= $sq_cust['first_name']?>')" title="" data-toggle="tooltip" data-original-title="WhatsApp wishes to customer"><i class="fa fa-whatsapp"></i></button></td>
												</tr>
											<?php } ?>
				                        </tbody>
				                      </table>
				                    </div> 
				                  </div>
				                </div>
				              </div>
				            </div>
				          </div>
			            </div>
				        <!-- Upcoming FIT Tour summary End -->
	          			<!--  FIT Summary -->
			            <div role="tabpanel" class="tab-pane" id="fit_tab">
			              <?php 
						  $count = 0; $bg='';
						  $query = mysql_fetch_assoc(mysql_query("select max(booking_id) as booking_id from package_tour_booking_master where financial_year_id='$financial_year_id'"));
				          $sq_entry = mysql_query("select * from package_travelers_details where booking_id='$query[booking_id]'");
				          ?> 
				          <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
				            <div class="row text-left">
				                <div class="">
				                  <div class="dashboard_table_heading main_block">
				                    <div class="col-md-2">
				                      <h3>Package Tours</h3>
				                    </div>
				                    <div class="col-md-3 col-sm-4 col-md-push-7">
				                      <select style="border-color: #009898; width: 100%;" id="package_booking_id" onchange="package_list_reflect(this.id)">
										<?php
										$query = "select * from package_tour_booking_master where 1 and financial_year_id='$financial_year_id'";
										include "../../../model/app_settings/branchwise_filteration.php";
										$query .= " order by booking_id desc";
										$sq_booking = mysql_query($query);
										while($row_booking = mysql_fetch_assoc($sq_booking)){
											$date = $row_booking['booking_date'];
											$yr = explode("-", $date);
											$year =$yr[0];
											$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
											if($sq_customer['type'] == 'Corporate'){
											?>
											<option value="<?php echo $row_booking['booking_id'] ?>"><?php echo get_package_booking_id($row_booking['booking_id'],$year)."-"." ".$sq_customer['company_name']; ?></option>
											<?php }
											else{ ?> 
											<option value="<?php echo $row_booking['booking_id'] ?>"><?php echo get_package_booking_id($row_booking['booking_id'],$year)."-"." ".$sq_customer['first_name']." ".$sq_customer['last_name']; ?></option>
											<?php    
											}
										} ?>
				                      </select>
				                    </div>
				                    <div id="package_div_list">
				                    </div>
				                  </div>
				                </div>
				                
				            </div>
				          </div>
			            </div>
					   <!--  FIT Summary End -->
	          			<!--  GIT Summary -->
			            <div role="tabpanel" class="tab-pane" id="git_tab">
			              <?php
				          $count = 0; $bg=''; 
				          $query = mysql_fetch_assoc(mysql_query("select max(id) as booking_id from tourwise_traveler_details"));
				          $sq_package = mysql_fetch_assoc(mysql_query("select * from tourwise_traveler_details where id='$query[booking_id]' and financial_year_id='$financial_year_id'"));
				          $sq_tour_name = mysql_fetch_assoc(mysql_query("select  * from tour_master where tour_id = '$sq_package[tour_id]'"));
				          $sq_traveler_personal_info = mysql_fetch_assoc(mysql_query("select * from traveler_personal_info where tourwise_traveler_id='$query[booking_id]'"));
				          ?>
				          <div class="dashboard_table dashboard_table_panel main_block mg_bt_25">
				            <div class="row text-left">
				                <div class="">
				                  <div class="dashboard_table_heading main_block">
				                    <div class="col-md-2">
				                      <h3>Group Tours</h3>
				                    </div>
				                 	<div class="col-md-3 col-sm-4 col-md-push-7">
				                      <select style="border-color: #009898; width: 100%;" id="group_booking_id" onchange="group_list_reflect(this.id)">
									  <?php
										    $query = "select * from tourwise_traveler_details where 1 and financial_year_id='$financial_year_id'";
											include "../../../model/app_settings/branchwise_filteration.php";
											$query .= " and tour_group_status != 'Cancel'";
											$query .= " order by id desc";
											$sq_booking = mysql_query($query);
											while($row_booking = mysql_fetch_assoc($sq_booking)){
										
											$date = $row_booking['form_date'];
											$yr = explode("-", $date);
											$year =$yr[0];
										
											$sq_customer = mysql_fetch_assoc(mysql_query("select * from customer_master where customer_id='$row_booking[customer_id]'"));
											 if($sq_customer['type'] == 'Corporate'){
												 ?>
												 <option value="<?php echo $row_booking['id'] ?>"><?php echo get_group_booking_id($row_booking['id'],$year)."-"." ".$sq_customer['company_name']; ?></option>
												 <?php }
												 else{ ?> 
										
												<option value="<?= $row_booking['id'] ?>"><?= get_group_booking_id($row_booking['id'],$year) ?> : <?= $sq_customer['first_name'].' '.$sq_customer['last_name'] ?></option>
												<?php
											}
										  }?>                     
				                      </select>
				                    </div>				                
					                <div id="group_div_list">
					                </div>
				                  </div>
				                </div>
				            </div>
				          </div>
			            </div>
					    <!--  GIT Summary End -->
	          			<!-- Enquiry & Followup summary -->
			            <div role="tabpanel" class="tab-pane active" id="enquiry_tab">
				          <div class="dashboard_table dashboard_table_panel main_block">
				            <div class="row text-right">
								<div class="col-md-6 text-left">
									<div class="dashboard_table_heading main_block">
									<div class="col-md-10 no-pad">
										<h3>Followup Reminders</h3>
									</div>
									</div>
								</div>
								<div class="col-md-2 col-sm-6 mg_bt_10">
									<input type="text" id="followup_from_date_filter" name="followup_from_date_filter" placeholder="Followup From D/T" title="Followup From D/T">
								</div>
								<div class="col-md-2 col-sm-6 mg_bt_10">
									<input type="text" id="followup_to_date_filter" name="followup_to_date_filter" placeholder="Followup To D/T" title="Followup To D/T">
								</div>
								<div class="col-md-1 text-left col-sm-6 mg_bt_10">
									<button class="btn btn-excel btn-sm" id="followup_reflect1" onclick="followup_reflect()" data-toggle="tooltip" title="" data-original-title="Proceed"><i class="fa fa-arrow-right"></i></button>
								</div>
								<div id='followup_data'></div>
				              </div>
				            </div>
				          </div>
			            </div>
				        <!-- Enquiry & Followup summary End -->
			          </div>
			        </div>
		       	</div>
		       </div>		        
	       </div>
	     </div>
<!-- ====================================================== Row 6 end ============================================================= -->

<script type="text/javascript">
$('#group_booking_id,#package_booking_id').select2();
$('#followup_from_date_filter, #followup_to_date_filter').datetimepicker({ format:'d-m-Y H:i' });
followup_reflect();
function followup_reflect(){
	var from_date = $('#followup_from_date_filter').val();
	var to_date = $('#followup_to_date_filter').val();
	$.post('admin/followup_list_reflect.php', { from_date : from_date,to_date:to_date }, function(data){
		$('#followup_data').html(data);
	});
}
function send_sms(id,tour_type,emp_id,contact_no, name){
	$('#send_btn').button('loading')
	$.post('admin/send_sms.php', { id:id,tour_type:tour_type,emp_id:emp_id,contact_no:contact_no,name:name}, function(data){
		$('#id_proof2').html(data);
	});
}
function whatsapp_wishes(number,name){
	var msg = encodeURI("Hello Dear "+ name +",\nMay this trip turns out to be a wonderful treat for you and may you create beautiful memories throughout this trip to cherish forever. Wish you a very happy and safe journey!!\nThank you.");
	window.open('https://web.whatsapp.com/send?phone='+number+'&text='+msg);
}
function checklist_update(booking_id,tour_type){
	
	$.post('admin/update_checklist.php', { booking_id:booking_id,tour_type:tour_type}, function(data){
		$('#id_proof2').html(data);
	});
}
function package_list_reflect(){
	var booking_id = $('#package_booking_id').val();
	$.post('admin/package_list_reflect.php', { booking_id : booking_id }, function(data){
		$('#package_div_list').html(data);
	});
}
package_list_reflect();

function group_list_reflect(){
	var booking_id = $('#group_booking_id').val();
	$.post('admin/group_list_reflect.php', { booking_id : booking_id }, function(data){
		$('#group_div_list').html(data);
	});

}
group_list_reflect();

function display_history(enquiry_id){
	$.post('admin/followup_history.php', { enquiry_id : enquiry_id }, function(data){
		$('#id_proof1').html(data);
	});
}

function Followup_update(enquiry_id)
{
  $.post('admin/followup_update.php', { enquiry_id : enquiry_id }, function(data){
    $('#id_proof2').html(data);
  });
}
function followup_type_reflect(followup_status){
	$.post('admin/followup_type_reflect.php', {followup_status : followup_status}, function(data){
		$('#followup_type').html(data);
	}); 
}
</script>

<script type="text/javascript">
	(function($) {
		fakewaffle.responsiveTabs(['xs', 'sm']);
	})(jQuery);
</script>

<script src="js/admin_dashboard.js"></script>