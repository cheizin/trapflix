<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Channel Management</li>
     </ol>
</nav>

<div class="row">
  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
      My Channels

        <button class="btn btn-link" style="float:right;"
                data-toggle="modal" data-target="#add-channel-modal">
                <i class="fa fa-plus-circle"></i> Add Channel
        </button>
      </div>
      <div class="card-body table-responsive">

        <?php
          $sql = mysqli_query($dbc,"SELECT * FROM main_categories WHERE category_name !='Popular On Trapflix' && user_id='".$_SESSION['id']."'  ORDER BY category_name ");
          
          if(mysqli_num_rows($sql) == 0)
          {
              ?>
                <div class="alert alert-danger" role="alert">
                  <h4 class="alert-heading">No Channels!</h4>
                  <p>No Personal channel. Start a new channel by clicking<a href="#" class="btn btn-link" data-toggle="modal" data-target="#add-channel-modal"> Add Channel</a>NB:- The Channel undergoes approval before go-live. </p>
                  <hr>
                </div>
              <?php
          }
          else
          {
               while($row = mysqli_fetch_array($sql)){
              ?>
              <div class="card col-4">
                  <h5 class="card-header"><?php echo $row['category_name'] ;?> </h5>
                  <div class="card-body">
                    
                                                      <?php
                    
                    
                                                      if($row['approved']== 'yes')
                    {
                    
                                                            ?>
                    
                                                     <a href="#" class="btn btn-link text-success"></a>          <i class="fas fa-check text-success"> Approved</i>
                    
                    
                                                             <?php
                    }
                    else
                    {
                      ?>
                 <a href="#" class="btn btn-link text-danger" data-toggle="tooltip" title="Wait for the chhannel to be approved" >
                 </a>        <i class="fas fa-times text-danger"> Pending Approval</i>
                    
                    
                      <?php
                    }
                    
                                                             ?>
    
                    <a href="#" class="btn btn-link text-danger" data-toggle="tooltip" title="Congratulation, Your Channel has been approved" onclick="closeChannel('<?php echo $row['id'];?>');"> Delete </a>
                  </div>
              </div>
    
             <?php
                } 
            }

          ?>
      </div>
    </div>
  </div>
</div>


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

<input type="hidden" name="order_id" value="<?php echo $reference_row;?>">

            

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
<!-- end of add project modal -->