<div class="row">
  <div class="card card-body">
        <div class="row">
          <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                  <?php
                  //select projects
                  $prrojects_sql = mysqli_query($dbc,"SELECT * FROM pm_projects");
                  $total_projects = mysqli_num_rows($prrojects_sql);
                   ?>
                    <h3><?php echo $total_projects;?></h3>
                    <p>Projects</p>
                </div>
                <div class="icon">
                        <img src="https://img.icons8.com/material/48/000000/ms-project.png"/>
                 </div>
                 <a href="#" class="small-box-footer risk-management-module">
                     <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                 </a>
              </div>
           </div>

           <div class="col-lg-2 col-xs-6">
                     <!-- small box -->
             <div class="small-box bg-blue">
                 <div class="inner">
                   <?php
                   //select resources
                   $resources_sql = mysqli_query($dbc,"SELECT DISTINCT resource_name FROM pm_resources");

                   $total_resources = mysqli_num_rows($resources_sql);
                    ?>
                     <h3><?php echo $total_resources;?></h3>
                     <p>Resources</p>
                 </div>
                 <div class="icon">
                        <img src="https://img.icons8.com/ios-filled/48/000000/human-resources.png"/>
                  </div>
                  <a href="#" class="small-box-footer risk-management-module">
                      <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                  </a>
               </div>
            </div>

              <div class="col-lg-2 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-red">
                      <div class="inner">
                        <?php
                        //Not started Task
                        $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='Not Started' ");
                        $total_not_started = mysqli_num_rows($not_started_sql);
                         ?>

                          <h3><?php echo $total_not_started;?></h3>
                          <p>Not started Task</p>

                      </div>
                    <div class="icon">
                            <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                     </div>
                     <a href="#" class="small-box-footer risk-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>

              <div class="col-lg-2 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-orange">
                      <div class="inner">

                        <?php
                        //Not started Task
                        $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='In Progress Behind Schedule' ");
                        $total_not_started = mysqli_num_rows($not_started_sql);
                         ?>

                          <h3><?php echo $total_not_started;?></h3>
                          <p>In progress Behind Schedule</p>

                      </div>
                    <div class="icon">
                            <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                     </div>
                     <a href="#" class="small-box-footer risk-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>



          <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                  <?php
                  //Not started Task
                  $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='In Progress Within Schedule' ");
                  $total_not_started = mysqli_num_rows($not_started_sql);
                   ?>

                    <h3><?php echo $total_not_started;?></h3>
                    <p>In progress Within Schedule</p>
                </div>
                <div class="icon">
                        <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                 </div>
                 <a href="#" class="small-box-footer performance-management-module">
                     <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                 </a>
              </div>
           </div>


              <div class="col-lg-2 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-green">
                      <div class="inner">

                        <?php
                        //Not started Task
                        $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='Not Started' ");
                        $total_not_started = mysqli_num_rows($not_started_sql);
                         ?>

                          <h3><?php echo $total_not_started;?></h3>
                          <p>Completed Tasks</p>
                      </div>
                    <div class="icon">
                            <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                     </div>
                     <a href="#" class="small-box-footer performance-management-module">
                         <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                     </a>
                  </div>
               </div>

              <div class="col-lg-2 col-xs-6">
                        <!-- small box -->
                <div class="small-box bg-green">
                      <div class="inner">

                        <?php
                        //Not started Task
                        $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='Continous' ");
                        $total_not_started = mysqli_num_rows($not_started_sql);
                         ?>

                          <h3><?php echo $total_not_started;?></h3>
                          <p>Continuous Task</p>
                      </div>
                    <div class="icon">
                            <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                     </div>
                      <a href="#" class="small-box-footer performance-management-module">
                          <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                      </a>
                  </div>
               </div>

               <div class="col-lg-2 col-xs-6">
                         <!-- small box -->
                 <div class="small-box bg-dark green">
                       <div class="inner">

                         <?php
                         //Not started Task
                         $not_started_sql = mysqli_query($dbc,"SELECT status FROM pm_activity_updates WHERE status ='Not Started' ");
                         $total_not_started = mysqli_num_rows($not_started_sql);
                          ?>

                           <h3><?php echo $total_not_started;?></h3>
                           <p>Repriotised Tasks</p>
                       </div>
                     <div class="icon">
                             <img src="https://img.icons8.com/wired/48/000000/tasks.png"/>
                      </div>
                      <a href="#" class="small-box-footer performance-management-module">
                          <button type="button" class="btn btn-link" style="color:white;">More Info <i class="fa fa-arrow-circle-right"></i></button>
                      </a>
                   </div>
                </div>





  </div>
  </div>
</div>
