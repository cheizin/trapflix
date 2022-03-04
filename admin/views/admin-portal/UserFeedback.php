<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Admin Portal : User Feedback</li>
     </ol>
</nav>

<div class="row">

  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        Submitted Feedback
      </div>
      <div class="card-body table-responsive">
        <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM user_feedback ORDER BY id DESC ");
       $number = 1;
       if($total_rows = mysqli_num_rows($sql_query) > 0)
       {?>
       <table class="table table-striped table-bordered table-hover" id="user-feedback-table" style="width:100%">
         <thead>
           <tr>
             <td>#</td>
             <td>Name</td>
             <td>Feedback Comment</td>
             <td>Date Submitted</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {?>
         <tr style="cursor: pointer;">
           <td> <?php echo $number++;?> </td>
           <td> <?php echo $row['feedback_person'];?> </td>
           <td> <?php echo $row['feedback_message'];?> </td>
           <td> <?php echo $row['date_submitted'];?> | <?php echo $row['time_submitted'];?>  </td>
         </tr>
         <?php
         }
         ?>
       </table>
       <?php
       }
       else {
         echo "No feedback available";
       }
       ?>
      </div>
    </div>
  </div>


  <div class="col-lg-12 col-xs-12">
    <div class="card card-primary card-outline">
      <div class="card-header">
        Feedback Receivers
        <button class="btn btn-link" style="float:right;"
                data-toggle="modal" data-target="#add-feedback-receiver-modal">
                <i class="fa fa-plus-circle"></i> Add Feedback Receiver
        </button>
      </div>
      <div class="card-body table-responsive">
        <?php
         $sql_query = mysqli_query($dbc,"SELECT * FROM feedback_receiver ORDER BY id DESC ");
       $number = 1;
       if($total_rows = mysqli_num_rows($sql_query) > 0)
       {?>
       <table class="table table-striped table-bordered table-hover" id="feedback-receiver-table" style="width:100%">
         <thead>
           <tr>
             <td>#</td>
             <td>Name</td>
             <td>Email</td>
             <td>Date Added</td>
             <td>Delete</td>
           </tr>
         </thead>
         <?php
         while($row = mysqli_fetch_array($sql_query))
         {?>
           <?php
           $name = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM staff_users
                                            WHERE Email='".$row['email']."'"));

            ?>
         <tr style="cursor: pointer;">
           <td> <?php echo $number++;?> </td>
           <td> <?php echo $name['Name'];?> </td>
           <td> <?php echo $name['Email'];?> </td>
           <td> <?php echo $row['date_added'];?> | <?php echo $row['time_added'];?>  </td>
           <td>
             <a href="#" class="btn btn-danger" onclick="DeleteFeedbackReceiver('<?php echo $name['Email'];?>');">
                <i class="fad fa-trash-alt"></i>
             </a>
         </tr>
         <?php
         }
         ?>
       </table>
       <?php
       }
       else {
         echo "No Users Available Set to receive feedback";
       }
       ?>
      </div>
    </div>
  </div>
</div>



<!-- User Feeback Modal -->
<div class="modal fade" id="add-feedback-receiver-modal">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Add Feedback Receiver</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="feedback-receiver-form" class="mt-4">
        <div class="row">
          <div class="col-sm-12">
            <input type="hidden" name="add_feedback_user" value="add_feedback_user">
            <select name="feedback_receiver" class="select2 form-control" required>
                  <option value=""> -- Select User -- </option>

                  <?php
                    $sql_query = mysqli_query($dbc,"SELECT * FROM staff_users WHERE designation!='TEST USER' && status = 'active' ORDER BY Name ASC");
                    while($row = mysqli_fetch_array($sql_query))
                    {
                      ?>
                        <option value="<?php echo $row['Email'];?>"><?php echo $row['Name'];?></option>

                      <?php
                    }
                   ?>
            </select>
          </div>
        </div>
        <br/><br/>
        <div class="row">
              <div class="col-sm-12 text-center">
                  <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
              </div>
        </div>
      </form>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
