<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
//  $token = bin2hex(random_bytes(20));

 $token = rand(20);
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Channel Rearrangement Management</li>
     </ol>
</nav>


<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
      Channel Rearrangement

      <button class="btn btn-link" style="float:right;"
              data-toggle="modal" data-target="#add-channel-modal">
              <i class="fa fa-plus-circle"></i> Add Channel
      </button>

      </div>
      <div class="card-body table-responsive">

        <?php
        $sql_query1 =  mysqli_query($dbc,"SELECT * FROM main_categories WHERE category_name !='Popular On Trapflix' ORDER BY order_id ASC ");

        $number = 1;
        if($total_rows1 = mysqli_num_rows($sql_query1) > 0)
        {?>

          <table class="table table-striped table-hover" id="staff-users-table">
         <thead>
           <tr>
             <td>#</td>
             <td>Channel Name</td>
              <td>Total Subscribers</td>
                <td>Total Videos</td>
                  <td>Owner</td>

                    <td>Order Id</td>
                    
                    <td>Order</td>
        

          <!--   <td>Status</td> -->
           </tr>
         </thead>
         <?php
         $no = 1;
          $sql = mysqli_query($dbc,"SELECT * FROM main_categories WHERE category_name !='Popular On Trapflix'  ORDER BY order_id ASC ");
          while($row = mysqli_fetch_array($sql)){
          ?>
         <tr style="cursor: pointer;">
           <td width="50px"> <?php echo $no++;?>.

           </td>

           <td><?php echo $row['category_name'] ;?></td>

           <td>
             <?php

               $total_subscribers = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM channel_subscription WHERE channel_name  ='".$row['category_name']."' ORDER BY id"));


echo $total_subscribers;

                  ?>
                </td>

           <td>
             <?php

               $total_videos = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM videos WHERE title  ='".$row['category_name']."' ORDER BY id"));


echo $total_videos;

                  ?>
                </td>


 <td><?php echo $row['recorded_by'] ;?></td>
 
 <td><?php echo $row['order_id'] ;?></td>
 
               <td>
  <button type="button" class="btn btn-link" data-toggle="modal" data-target="#reorder-channel-modal-<?php echo $row['id'];?>" data-toggle="tooltip" title="Click here to order channel">
Order
 </button>


                                                      <!-- edit project modal -->
                                                      <div class="modal fade" id="reorder-channel-modal-<?php echo $row['id'];?>">
                                                      <div class="modal-dialog modal-dialog-scrollable" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title">Reordering <strong><?php echo $row['category_name'];?> </strong> Channel Position  </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          
                                                          
                                                          <div class="modal-body">
                                                            <form id="reorder-channel-form-<?php echo $row['id'];?>" class="mt-4">
                                                              <input type="hidden" value="reorder_channel" id="reorder_channel-<?php echo $row['id'];?>">
                                                              <input type="hidden" value="<?php echo $row['id'];?>" id="id-<?php echo $row['id'];?>">
                                    
                                                            <div class="col-lg-12 col-xs-12 form-group">
                                                              <label for="channel"><span class="required">*</span>Channel Order Number <?php echo $row['id'];?></label>
                                                              
                                                              <select id="order_id-<?php echo $row['id'];?>" class="form-control" 
                                                                      onchange="reorderChannel('reorder_channel','<?php echo $row['id'];?>');">
                                                                  
                                                                  <?php
                                                                    foreach(range(1,20)as $number){
                                                                        
                                                                    ?>
                                                                        <option value="<?php echo $number;?>"><?php echo $number;?></option>
                                                                    <?php
                                                                    }
                                                                  ?>
                                                                  
                                                              </select>
                                                            
                                                             </div>
                                                                

                                                              <!-- end row project owenerships -->
                                                              <div class="row">
                                                                <small class="status-project-user text-success"></small><br/>
                                                              </div>

                                                              <!-- start row related activity -->
                                                              <div class="row  mb-4">


                                                              </div>
                                                              <!-- end row related activity -->

                                                              <div class="pull-left mt-4">
                                                                <small class="text-muted">Reordered by:- <?php echo $_SESSION['name'];?></small>
                                                              </div>

                                                                    <!-- start row button -->
                                                              <div class="row">
                                                                <div class="col-md-12 text-center">
                                                                    <button type="button" id="reorder-channel-edit-button-<?php echo $row['id'];?>" onclick="reorderChannel('<?php echo $row['id'];?>');" 
                                                                    class="btn btn-primary btn-block font-weight-bold submitting">Modify
                                                                    </button>
                                                                </div>
                                                              </div>

                                                                    <!-- end row button -->
                                                            </form>
                                                          </div>
                                                          
                                                          
                                                          <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                          </div>
                                                        </div>
                                                      </div>
                                                      </div>
 </td>

         </tr>
         <?php
            }
          ?>

          <tfoot style="background:silver;">
                  <tr>
                      <th></th>
                      <th></th>
                      <th></th>
                          <th></th>
                      <th></th>
                            <th></th>
                               <th></th>
                    

                  </tr>
              </tfoot>
       </table>
      </div>
    </div>
  </div>
</div>

<?php
}
else
{
      ?>
    <br/>
<div class="alert alert-info">
<strong><i class="fa fa-info-circle"></i> No Youtube videos</strong>
</div>

  <?php
}
?>

<?php
//fetch last reference number from database and auto increment it
$reference_row = mysqli_fetch_array(mysqli_query($dbc,"SELECT MAX(id) AS ref_no FROM main_categories "));
//auto increment the fetched record
$reference_row = $reference_row['ref_no'];
//add programm name prefix, plus the auto incremented value
$reference_row = $reference_row+1;
?>

<!-- add stock modal -->

<div class="modal fade" id="add-channel-modal">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">

      <h5 class="modal-title">Add New Channel</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="add-channel-form" class="mt-4" enctype="multipart/form-data">
        <input type="hidden" value="add-channel" name="add-channel">

        <input type="hidden" name="name" value="<?php echo $_SESSION['name'];?>">

          <input type="text" name="name" value="<?php echo $reference_row;?>">

        <div class="row border-bottom mx-3">


            <div class="col-lg-12 col-xs-12 form-group">
                <label for="textname"><span class="required">*</span>Channel Name</label>

                  <textarea name="category_name" class="form-control" required></textarea>

            </div>



        </div>
        <!-- start row project timelines -->


              <div class="row border-bottom mx-2">


                  <div class="pull-left mt-4">
                    <small class="text-muted">Recorded by:- <?php echo $_SESSION['name'];?></small>
                  </div>
                    </div>


        <!-- end row project timelines -->





              <!-- start row button -->
        <div class="row">
          <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold submitting">Create Channel</button>
          </div>
        </div>

              <!-- end row button -->
      </form>
    </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
  </div>
  </div>

<script>
function triggerClick(e) {
  document.querySelector('#profileImage').click();
}
function displayImage(e) {
  if (e.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e){
      document.querySelector('#profileDisplay').setAttribute('src', e.target.result);
    }
    reader.readAsDataURL(e.files[0]);
  }
}


function reorderChannel(action,str)
{
   var form_data = {
        order_id : $('#order_id-'+str).val(),
        id: $('#id-'+str).val(),
        reorder_channel: action
    }




    var form_id = $('#reorder-channel-form-'+str);
    
    var button = $('#reorder-channel-edit-button-'+str);

    //var form_data = $(form_id).serializeArray();
    

    
    console.log(form_data);
    var form_url = 'controllers/channel-management/orderChannel.php';;
    var form_method = 'POST';

    $.ajax({
        data : form_data,
        url  : form_url,
        method : form_method,
        beforeSend:function()
        {
          $.blockUI({ message: blockui_spinner });
        },
        success:function(data){
            $.unblockUI();
            if(data == 'success')
            {
              $('body').removeClass('modal-open');
              $('.modal-backdrop').remove();

              Toast.fire({
                   icon: 'success',
                   title: 'Channel Reordered Successfully'
                 });

            $('.channel-reordering-link').click();
            }
            else if(data == 'failed')
            {
             Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please try again'
                  });
            }
            else
            {
                 Toast.fire({
                    icon: 'error',
                    title: 'Failed. Please contact System Administrator'
                  });
                    console.log(data);
            }
            console.log(data);
        },
        error: function(xhr)
        {
          $.unblockUI();
            Toast.fire({
              icon: 'error',
              title: 'Your request could not be completed. Please try again: '+xhr.status
            });
        }

    });


}
</script>
<!-- end of add project modal -->
