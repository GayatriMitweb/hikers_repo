<?php
$branch_admin_id = $_SESSION['branch_admin_id'];
$financial_year_id = $_SESSION['financial_year_id'];
$role_id= $_SESSION['role_id'];
?>
<form id="frm_tab1">

<div class="app_panel">
<!--=======Header panel======-->
    <div class="app_panel_head mg_bt_20">
      <div class="container">
          <h2 class="pull-left"></h2>
          <div class="pull-right header_btn">
            <button>
                <a>
                    <i class="fa fa-arrow-right"></i>
                </a>
            </button>
          </div>
      </div>
    </div> 
<!--=======Header panel end======-->

        <div class="container">

            <input type="hidden" id="emp_id" name="emp_id" value="<?= $emp_id ?>">
            <input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
            <input type="hidden" id="financial_year_id" name="financial_year_id" value="<?= $financial_year_id ?>" >            
            <input type="hidden" id="branch_admin_id1" name="branch_admin_id1" value="<?= $branch_admin_id ?>" >
		
        <div class="row mg_tp_10">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<select name="enquiry_id" id="enquiry_id" style="width:100%" onchange="get_hotelenquiry_details()">
					<option value="">*Select Enquiry</option>
					<option value="0"><?= "New Enquiry" ?></option>
					<?php
					if($role=='Admin'){
						$sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Hotel') and status!='Disabled' order by enquiry_id desc");
					}
					if($branch_status=='yes'){
						if($role=='Branch Admin' || $role=='Accountant' || $role_id>'7'){
							$sq_enq = mysql_query("select * from enquiry_master where enquiry_type in('Hotel') and status!='Disabled' and branch_admin_id='$branch_admin_id' order by enquiry_id desc");
						}
						elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
							$q = "select * from enquiry_master where enquiry_type in('Hotel') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
							$sq_enq = mysql_query($q);
						}
					}
					elseif($role!='Admin' && $role!='Branch Admin' && $role_id!='7' && $role_id<'7'){
						$q = "select * from enquiry_master where enquiry_type in('Hotel') and assigned_emp_id='$emp_id' and status!='Disabled' order by enquiry_id desc";
						$sq_enq = mysql_query($q);
					}
					while($row_enq = mysql_fetch_assoc($sq_enq)){
						?>
						<option value="<?= $row_enq['enquiry_id'] ?>">Enq<?= $row_enq['enquiry_id'] ?> : <?= $row_enq['name'] ?></option>
						<?php
					}
					?>
				</select>
			</div>	

			<div class="col-md-4 col-sm-6 col-xs-12">
			    <input type="text" id="customer_name" name="customer_name" placeholder="Customer Name" title="Customer Name">
			</div>

			<div class="col-md-4 col-sm-6 col-xs-12">
                <input type="text" id="email_id" name="email_id" placeholder="Email Id" title="Email Id">
			</div>
		</div>
		<div class="row mg_tp_10">
            <div class="col-md-4 col-sm-6 mg_bt_10">
                <div class="col-md-4" style="padding-left:0px;">
                    <select name="country_code" id="country_code" style="width:100px;">
                        <?= get_country_code(); ?>
                    </select>
                </div>
                <div class="col-md-8" style="padding-left:12px;padding-right:0px;">
                    <input type="text" class="form-control" id="whatsapp_no"  onchange="mobile_validate(this.id);" name="whatsapp_no" placeholder="WhatsApp No" title="WhatsApp No"> 
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" id="total_adult" name="total_adult" placeholder="Total Adult" title="Total Adult" onchange="total_passangers_calculate(); validate_balance(this.id)" required>
			</div>
            <div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" class="form-control" id="children_without_bed" name="children_without_bed" onchange="validate_balance(this.id);total_passangers_calculate();" placeholder="Child Without Bed" title="Child Without Bed" required>
			</div>
		</div>
		<div class="row mg_tp_10">
			<div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" class="form-control" id="children_with_bed" name="children_with_bed" onchange="validate_balance(this.id);total_passangers_calculate();" placeholder="Child With Bed" title="Child With Bed" required> 
			</div>
            <div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" id="total_infant" name="total_infant" placeholder="Total Infant" title="Total Infant" onchange="total_passangers_calculate(); validate_balance(this.id);" required>
			</div>
            <div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" id="total_members" name="total_members" placeholder="Total Members" title="Total Members" readonly>
			</div>
		</div>
		<div class="row mg_tp_10">	
			<div class="col-md-4 col-sm-6 col-xs-12">
				<input type="text" class="form-control" id="quotation_date" name="quotation_date" placeholder="Quotation Date" title="Quotation Date" value="<?= date('d-m-Y')?>" > 
			</div>
	    </div>
		<div class="row  mg_tp_10">
			<div class="col-xs-12">
				<h3 class="editor_title">Hotel Requirements</h3>
				<textarea name="hotel_requirements" id="hotel_requirements" cols="30" rows="10" class="feature_editor"></textarea>
			</div>
		</div>
		<br><br>
	    <div class="row text-center">
            <div class="col-xs-12">
    			<button class="btn btn-info btn-sm ico_right">Next&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></button>
    		</div>
        </div>
		
</form>
<?= end_panel() ?>

<script>
$('#country_code').select2();

$('#frm_tab1').validate({
	rules:{
		enquiry_id : { required : true },
	},
	submitHandler:function(form){
		$('#tab1_head').addClass('done');
		$('#tab2_head').addClass('active');
		$('.bk_tab').removeClass('active');
		$('#tab2').addClass('active');
		$('html, body').animate({scrollTop: $('.bk_tab_head').offset().top}, 200);
	}
});
</script>

