<?php

session_start();
include("../../controllers/setup/connect.php");

if($_SERVER['REQUEST_METHOD'] == 'POST')
{

 if (!isset($_SESSION['email']))
 {
    exit("unauthenticated");
 }


 if($_POST['Email'] == "all")
{
//$product_start_date = mysqli_real_escape_string($dbc,strip_tags($_POST['product_start_date']));

    $sql = mysqli_query($dbc,
                            //"SELECT * FROM answered_response_test WHERE email ='".$row['reference_no']."' ORDER BY id DESC"
                            "SELECT * FROM answered_response_test ORDER BY id DESC"
                            );

                            if($sql)
                            {
                              $total_rows = mysqli_num_rows($sql);
                              if($total_rows > 0)
                              {

                              ?>
                              <div class="card">

                               <!-- /.card-header -->
                               <div class="card-body">
                                  <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="email-list-table" style="width:100%">
                                      <thead>
                                        <tr>
                                          <td>#</td>
                                            <td width"70">Candidate Details</td>
                                          <td>Position Applied</td>
                                          <td>Reviewer Response</td>
                                          <td>Marks</td>
                                          <td>Remarks</td>
                                           <td>Date Reviewed</td>
                                        </tr>
                                      </thead>
                                      <?php
                                      $number = 1;
                                      while($row = mysqli_fetch_array($sql))
                                      {
                                        ?>
                                        <tr style="cursor: pointer;">
                                          <td width="50px"> <?php echo $number++;?>.

                                          </td>
                                            <td>


                                              <!-- Single Verticle job -->
                                              <div class="job-verticle-list">
                                                <div class="vertical-job-card">
                                                  <div class="vertical-job-header" >


                                             <strong>   <span class="pull-left vacancy-no">                            <strong>
                           <?php

                                $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                if(mysqli_num_rows($result))
                                {
                                  while($project= mysqli_fetch_array($result))
                                  {
                                      echo $project['fName'];

                                  }
                                }
                                ?>,                <strong>
                           <?php

                                $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                if(mysqli_num_rows($result))
                                {
                                  while($project= mysqli_fetch_array($result))
                                  {
                                      echo $project['lName'];

                                  }
                                }
                                ?></span> </strong>
                                           </br></span>


                                                     <span class="com-tagline">
                                                       <?php

                 $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                 if(mysqli_num_rows($result))
                 {
                   while($project= mysqli_fetch_array($result))
                   {
                       echo $project['currentPosition'];

                   }
                 }
                 ?></span>
                                                       <span class="com-tagline">
                                                         at
                           <?php

                                $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                if(mysqli_num_rows($result))
                                {
                                  while($project= mysqli_fetch_array($result))
                                  {
                                      echo $project['companyName'];

                                  }
                                }
                                ?>
                                </b> <br/>
                                  Holds a    <?php

                                         $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                         if(mysqli_num_rows($result))
                                         {
                                           while($project= mysqli_fetch_array($result))
                                           {
                                               echo $project['highestQualification'];

                                           }
                                         }
                                         ?>
                                         with
                                         <?php

                                               $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                               if(mysqli_num_rows($result))
                                               {
                                                 while($project= mysqli_fetch_array($result))
                                                 {
                                                     echo $project['experience'];

                                                 }
                                               }
                                               ?>

                                               Experience<br/></span>

                                           </div>

                                                </div>
                                              </div> </td>
                                              <td>
                                                <?php

                                                $get_job = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM answered_test WHERE id ='".$row['reference_no']."'"));
                                                $get_assigned = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM assigned_test WHERE id ='".$get_job['reference_no']."'"));

                                                echo $get_assigned['posted_job'] ;
                                                    ?>
                                            </td>
                                            <td><div class="vertical-job-card">
                                              <?php echo $row['response_name'] ;?>
                                            </div>
                                          </td>
                                              <td><?php echo $row['marks'] ;?></td>
                                                <td><?php echo $row['remarks'] ;?></td>
                                                  <td><?php echo $row['time_recorded'] ;?></td>

                                          </tr>
                                        <?php
                                      }
                                       ?>

                                    </table>
                                  </div>
                               </div>
                               <!-- /.card-body -->
                               <div class="card-footer">

                               </div>
                               <!-- card-footer -->
                             </div>
                             <!-- /.card -->
                               <?php
                             } // end num row
                             else  //no rows
                             {
                               ?>
                               <div class="alert alert-danger alert-dismissible">
                                 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                 <strong>No Records!<br/></strong> No Review Has been done.
                               </div>
                               <?php
                             }
                            }
                            else
                            {
                              exit(mysqli_error($dbc));
                            }
}
else
{


  $selected_email = mysqli_real_escape_string($dbc,strip_tags($_POST['Email']));
  $sql = mysqli_query($dbc,
                          //"SELECT * FROM answered_response_test WHERE email ='".$row['reference_no']."' ORDER BY id DESC"
                          "SELECT * FROM answered_response_test WHERE email = '".$selected_email."' ORDER BY id DESC"
                          );

                          if($sql)
                          {
                            $total_rows = mysqli_num_rows($sql);
                            if($total_rows > 0)
                            {

                            ?>
                            <div class="card">

                             <!-- /.card-header -->
                             <div class="card-body">
                                <div class="table-responsive">
                                  <table class="table table-striped table-bordered table-hover" id="email-list-table" style="width:100%">
                                    <thead>
                                      <tr>
                                        <td>#</td>
                                        <td width"70">Candidate Details</td>
                                        <td>Position Applied</td>
                                        <td>Reviewer Response</td>
                                        <td>Marks</td>
                                        <td>Remarks</td>
                                         <td>Date Reviewed</td>
                                      </tr>
                                    </thead>
                                    <?php
                                    $number = 1;
                                    while($row = mysqli_fetch_array($sql))
                                    {
                                      ?>
                                      <tr style="cursor: pointer;">
                                        <td width="50px"> <?php echo $number++;?>.

                                        </td>
                                        <td>


                                          <!-- Single Verticle job -->
                                          <div class="job-verticle-list">
                                            <div class="vertical-job-card">
                                              <div class="vertical-job-header" >

                                         <strong>   <span class="pull-left vacancy-no">                            <strong>
                       <?php

                            $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                            if(mysqli_num_rows($result))
                            {
                              while($project= mysqli_fetch_array($result))
                              {
                                  echo $project['fName'];

                              }
                            }
                            ?>,                <strong>
                       <?php

                            $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                            if(mysqli_num_rows($result))
                            {
                              while($project= mysqli_fetch_array($result))
                              {
                                  echo $project['lName'];

                              }
                            }
                            ?></span> </strong>
                                       </br></span>


                                                 <span class="com-tagline">
                                                   <?php

             $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
             if(mysqli_num_rows($result))
             {
               while($project= mysqli_fetch_array($result))
               {
                   echo $project['currentPosition'];

               }
             }
             ?></span>
                                                   <span class="com-tagline">
                                                     at
                       <?php

                            $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                            if(mysqli_num_rows($result))
                            {
                              while($project= mysqli_fetch_array($result))
                              {
                                  echo $project['companyName'];

                              }
                            }
                            ?>
                            </b> <br/>
                              Holds a    <?php

                                     $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                     if(mysqli_num_rows($result))
                                     {
                                       while($project= mysqli_fetch_array($result))
                                       {
                                           echo $project['highestQualification'];

                                       }
                                     }
                                     ?>
                                     with
                                     <?php

                                           $result = mysqli_query($dbc, "SELECT * FROM staff_users WHERE Email ='".$row['email']."'"  );
                                           if(mysqli_num_rows($result))
                                           {
                                             while($project= mysqli_fetch_array($result))
                                             {
                                                 echo $project['experience'];

                                             }
                                           }
                                           ?>

                                           Experience<br/></span>

                                       </div>
                                            </div>
                                          </div> </td>
                                            <td>
                                              <?php

                                              $get_job = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM answered_test WHERE id ='".$row['reference_no']."'"));
                                              $get_assigned = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM assigned_test WHERE id ='".$get_job['reference_no']."'"));

                                              echo $get_assigned['posted_job'] ;
                                                  ?>
                                          </td>
                                          <td><?php echo $row['response_name'] ;?></td>
                                            <td><?php echo $row['marks'] ;?></td>
                                              <td><?php echo $row['remarks'] ;?></td>
                                                <td><?php echo $row['time_recorded'] ;?></td>

                                        </tr>
                                      <?php
                                    }
                                     ?>

                                  </table>
                                </div>
                             </div>
                             <!-- /.card-body -->
                             <div class="card-footer">

                             </div>
                             <!-- card-footer -->
                           </div>
                           <!-- /.card -->
                             <?php
                           } // end num row
                           else  //no rows
                           {
                             ?>
                             <div class="alert alert-danger alert-dismissible">
                               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                               <strong>No Records!<br/></strong> No Review Has been done.
                             </div>
                             <?php
                           }
                          }
                          else
                          {
                            exit(mysqli_error($dbc));
                          }
}


}
