<?php 
include "../../../model/model.php";
$login_id = $_SESSION['login_id'];
$role = $_SESSION['role'];
$financial_year_id = $_SESSION['financial_year_id'];
$branch_admin_id = $_SESSION['branch_admin_id'];
$emp_id = $_SESSION['emp_id'];
$branch_status = $_POST['branch_status'];

$booking_id = $_POST['booking_id'];
$tour_type = $_POST['tour_type'];
$new_array = array();
$sq_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id'"));
if($tour_type=="Package Tour"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from package_tour_booking_master where booking_id='$booking_id'"));
    
    if($sq_booking['dest_id']=='0'){
        $sq_package = mysql_fetch_assoc(mysql_query("select * from custom_package_master where package_id='$sq_booking[package_id]'"));
        $dest_id = $sq_package['dest_id'];
    }else{
        $dest_id = $sq_booking['dest_id'];
    }
    
}
if($tour_type=="Group Tour"){
    $sq_booking = mysql_fetch_assoc(mysql_query("select * from  tourwise_traveler_details where id='$booking_id'"));
    $tour_id = $sq_booking['tour_id'];
    $sq_tour = mysql_fetch_assoc(mysql_query("select * from  tour_master where tour_id='$tour_id'"));        $dest_id = $sq_tour['dest_id'];
}
if($tour_type=="Package Tour" || $tour_type=="Group Tour"){
    $sq_entitiesc = mysql_num_rows(mysql_query("select * from checklist_entities where entity_for='$tour_type' and destination_name = '$dest_id'"));
}else{
    $sq_entitiesc = mysql_num_rows(mysql_query("select * from checklist_entities where entity_for='$tour_type'"));
}
?>

<div class="modal fade profile_box_modal" id="view_modal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?= $tour_type.' Checklist' ?></h4>
        </div>
        <div class="modal-body profile_box_padding">
        <?php if($sq_entitiesc != 0){ ?>
        <form id="frm_emquiry_save">
        <input type="hidden" id="booking_id" name="booking_id" value="<?= $booking_id ?>">
        <input type="hidden" id="tour_type" name="tour_type" value="<?= $tour_type ?>">
            <div class="row mg_tp_10"><div class="table-responsive">
             <table class="table table-bordered" style="margin: 44px;width: 800px;">
                <thead>
                    <tr class="active table-heading-row">
                        <th></th>
                        <th>S_No.</th>
                        <th>To_Do</th>
                        <th>Assigned_To</th>
                    </tr>
                </thead>
                <tbody> 
                 <?php
                    if($tour_type=="Package Tour" || $tour_type=="Group Tour"){
                        $sq_entities = mysql_query("select * from checklist_entities where entity_for='$tour_type' and destination_name = '$dest_id'");
                    }else{
                        $sq_entities = mysql_query("select * from checklist_entities where entity_for='$tour_type'");
                    }
                    while($row_entity = mysql_fetch_assoc($sq_entities))
                    { 
                        $sql=mysql_query("select * from to_do_entries where entity_id='$row_entity[entity_id]'");
                        $count = 0;
                            while($sq_todo_list=mysql_fetch_assoc($sql))
                            {
                                $count++;

                                $sql_entry = mysql_fetch_assoc(mysql_query("select * from checklist_package_tour where booking_id='$booking_id' and tour_type='$tour_type' and entity_id='$sq_todo_list[id]'"));
                            
                                $sq_chk_count = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$booking_id' and entity_id='$sq_todo_list[id]' "));
                                
                                $chk_status = ($sq_chk_count==1) ? "checked" : "";
                                
                                $sq_chk_count1 = mysql_num_rows(mysql_query("select * from checklist_package_tour where booking_id='$booking_id' and entity_id='$sq_todo_list[id]' and status='Completed'"));
                                $bg = ($sq_chk_count1==1) ? "success" : "";
                               
                                ?>
                                <tr class="<?= $bg ?>">
                                    <td>
                                        <input type="checkbox" id="chk_package_tour_checklist_<?= $count ?>" name="chk_package_tour_checklist" <?= $chk_status ?> data-entity-id="<?= $sq_todo_list['id'] ?>" >
                                    </td>
                                    <td><?= $count ?></td>
                                    <td><?= $sq_todo_list['entity_name'] ?></td>
                                    <td><select name="assigned_emp_id<?= $sq_todo_list['id'] ?>" id="assigned_emp_id<?= $sq_todo_list['id'] ?>" title="Allocate To" style="width:100%">
                                    <?php if($sql_entry['assigned_emp_id']!=""){ 
                                    $sql_emp = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id = '$sql_entry[assigned_emp_id]'"));     
                                    ?>
                                        <option value="<?= $sql_entry['assigned_emp_id'] ?>"><?= $sql_emp['first_name'] ?></option>
                                            <?php  
                                            if($login_id == "1"){
                                            ?>
                                            <option value="0">Admin</option>
                                            <?php
                                            }
                                            if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){
                                            $query = "select * from emp_master where active_flag='Active' order by first_name desc";
                                            $sq_emp = mysql_query($query);
                                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                            }
                                        elseif($branch_status=='yes' && $role=='Branch Admin'){
                                            $query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
                                            $sq_emp = mysql_query($query);
                                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                        }else{ 
                                            $query1 = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id' and active_flag='Active'")); ?>
                                                <option value="<?= $query1['emp_id'] ?>"><?= $query1['first_name'].' '.$query1['last_name'] ?>
                                                </option>
                                            <script>
                                                $('#assigned_emp_id<?= $sq_todo_list['id']  ?>').select2();
                                            </script>
                                        <?php } ?>
                                        <?php }else{ ?> 
                                        <option value="">*Allocate To</option>
                                            <?php  
                                            if($login_id == "1"){
                                            ?>
                                            <option value="0">Admin</option>
                                            <?php
                                            }
                                            if($role=='Admin' || ($branch_status!='yes' && $role=='Branch Admin')){
                                            $query = "select * from emp_master where active_flag='Active' order by first_name desc";
                                            $sq_emp = mysql_query($query);
                                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                            }
                                        elseif($branch_status=='yes' && $role=='Branch Admin'){
                                            $query = "select * from emp_master where active_flag='Active' and branch_id='$branch_admin_id' order by first_name asc";
                                            $sq_emp = mysql_query($query);
                                            while($row_emp = mysql_fetch_assoc($sq_emp)){
                                                ?>
                                                <option value="<?= $row_emp['emp_id'] ?>"><?= $row_emp['first_name'].' '.$row_emp['last_name'] ?></option>
                                                <?php
                                            }
                                        }else{ 
                                            $query1 = mysql_fetch_assoc(mysql_query("select * from emp_master where emp_id='$emp_id' and active_flag='Active'")); ?>
                                                <option value="<?= $query1['emp_id'] ?>"><?= $query1['first_name'].' '.$query1['last_name'] ?>
                                                </option>
                                            <script>
                                                $('#assigned_emp_id<?= $sq_todo_list['id'] ?>').select2();
                                            </script>
                                        <?php } ?>
                                    <?php } ?>
                                            
                                        </select></td>
                                        <td class="hidden"><select name="status<?= $sq_todo_list['id'] ?>" id="status<?= $sq_todo_list['id'] ?>" title="Status" style="width:100%">
                                    <?php if($sql_entry['status']!=''){ ?>
                                        <option value="<?= $sql_entry['status'] ?>"><?= $sql_entry['status'] ?></option>
                                        <option value="Not Updated">Not Updated</option>
                                        <option value="Completed">Completed</option>
                                    <?php }else{ ?>
                                        <option value="Not Updated">Not Updated</option>
                                        <option value="Completed">Completed</option>
                                    <?php } ?>
                                    </select></td>
                                        <td class="hidden"><input type="hidden" id="entry_id<?= $sq_todo_list['id'] ?>" name="entry_id<?= $sq_todo_list['id'] ?>" value="<?= $sql_entry['id'] ?>"></td>
                                </tr>
                                <?php 
                            }
                        }
                        ?> 
                </tbody>		
            </table>
            </div></div>
            <div class="row text-center mg_tp_20">
                <div class="col-md-12">
                    <button class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i>&nbsp;&nbsp;Update</button>
                </div>
            </div>
        </form>
        <?php }else{
            echo '<h4>No checklist added!</h4>';
        } ?>
        <div>
    </div>
  </div>
</div>

<script src="<?php echo BASE_URL ?>js/app/footer_scripts.js"></script>
<script>
$('#view_modal').modal('show');

$(function(){
$('#frm_emquiry_save').validate({
  rules:{
    draft : { draft : true },
  },
  submitHandler:function(form){
   
    var booking_id = $('#booking_id').val();
    var branch_admin_id = $('#branch_admin_id1').val();
    var tour_type = $('#tour_type').val();
    var entity_id_arr = new Array();
    var assigned_emp_arr = new Array();
    var ass_emp_id_arr = new Array();
    var entry_id_arr = new Array();
    var status_arr = new Array();
    var count=1;
    $('input[name="chk_package_tour_checklist"]:checked').each(function(){
        var entity_id = $(this).attr('data-entity-id');
        var emp_id = $('#assigned_emp_id'+entity_id).val();
        
        var entry_id = $('#entry_id'+entity_id).val();
        var status = $('#status'+entity_id).val();
        status_arr.push(status);
        ass_emp_id_arr.push(emp_id);
        entry_id_arr.push(entry_id);
        entity_id_arr.push(entity_id);
    });
    
    if(entity_id_arr.length == 0){ error_msg_alert('Atleast select one entity'); return false; }
    var base_url = $('#base_url').val();
  
//    var entity_id_arr = JSON.stringify(entity_id_arr);
    $.ajax({
        type:'post',
        url: base_url+'controller/checklist/tour_checklist_save.php', 
        data:{ booking_id:booking_id,branch_admin_id:branch_admin_id,entity_id_arr:entity_id_arr,tour_type:tour_type,ass_emp_id_arr:ass_emp_id_arr,entry_id_arr:entry_id_arr,status_arr:status_arr },
        success:function(result){
            msg_alert(result);
            $('#btn_form_send').button('reset'); 
          $('#view_modal').modal('hide'); 
          followup_reflect(); 
        }
    });
    }
});

});

</script>