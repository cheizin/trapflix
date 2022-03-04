<?php
if(!$_SERVER['REQUEST_METHOD'] == "POST")
{
  exit();
}
session_start();
include("../../controllers/setup/connect.php");
/*
if($_SESSION['access_level']!='admin')
{
    exit("unauthorized");
}
*/

?>
<nav aria-label="breadcrumb">
     <ol class="breadcrumb">
       <li class="breadcrumb-item active" aria-current="page">Resource Mangement for End Product </li>
     </ol>
</nav>


<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">

    <div class="card-body table-responsive">

     <table class="table table-striped table-bordered table-hover" id="end-product-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Product Name</td>
          <td>Start Date</td>
           <td>End Date</td>
           <td>Duration</td>
            <td>Resources</td>
            <td>Project Name</td>

         </tr>
       </thead>
       <?php
          $no = 1;
          $sql= mysqli_query($dbc,"SELECT * FROM end_product ORDER BY id ");
          while($product = mysqli_fetch_array($sql))
          {
            ?>
            <tr style="cursor: pointer;">
              <td width="40px"><?php echo $no++ ;?>.

              </td>

              <td>

                <a class="" href="#" data-toggle="modal" data-target="#resource-plan-modal-<?php echo $product['id'];?>"
                  title="Click on <?php echo $product['product_name'];?> to add a Resource">
                  <span class="text-primary" style="cursor:pointer;"><?php echo $product['product_name'];?></span>
                </a>

                <!-- start resource plan Modal -->
                <div class="modal fade" id="resource-plan-modal-<?php echo $product['id'];?>" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Monitor End Product : <?php echo $product['product_name'];?> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <div id="accordion-<?php echo $product['id'];?>">
                          <div class="card">
                            <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseOne-<?php echo $product['id'];?>">
                              <h5 class="mb-0">
                                <button class="btn btn-link" >
                                  <i class="fal fa-users-medical fa-lg"></i> Add Resources
                                </button>
                              </h5>
                            </div>
                            <div id="collapseOne-<?php echo $product['id'];?>" class="collapse" data-parent="#accordion-<?php echo $product['id'];?>">
                              <div class="card-body">
                              <form id="add-product-resource-form-<?php echo $product['id'];?>" onsubmit="SubmitProductResource('<?php echo $product['id'];?>');">
                                <div class="col-md-12 col-xs-12 form-group">
                                  <label><span class="required">*</span> Resources</label><br/><br/>
                                  <select name="resource_name[]" id="resource_name-<?php echo $product['id'];?>" class="select2 form-control" required multiple="multiple" data-placeholder="Select Resources">
                                      <?php
                                        $sql_query = mysqli_query($dbc,"SELECT * FROM staff_users WHERE designation!='TEST USER' && status = 'active'
                                                                          && Name NOT IN
                                                                          (SELECT resource_name FROM endproductresources WHERE
                                                                            reference_no='".$product['id']."')
                                                                        ORDER BY Name ASC");
                                        while($row = mysqli_fetch_array($sql_query))
                                        {
                                          ?>
                                            <option value="<?php echo $row['Name'];?>"><?php echo $row['Name'];?></option>

                                          <?php
                                        }
                                       ?>
                                  </select>
                                </div>
                                <div class="col-md-12 mt-5">
                                    <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                </div>

                              </form>
                              </div><!--- end of card body -->
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseTwo-<?php echo $product['id'];?>">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed">
                                  <i class="fal fa-tasks fa-lg"></i> Update Task
                                </button>
                              </h5>
                            </div>
                            <div id="collapseTwo-<?php echo $product['id'];?>" class="collapse" data-parent="#accordion-<?php echo $product['id'];?>">
                              <div class="card-body">

                                <form id="add-product-status-form-<?php echo $product['id'];?>" onsubmit="SubmitProductStatus('<?php echo $product['id'];?>');">
                                  <div class="col-md-12 col-xs-12 form-group">
                                    <label><span class="required">*</span>Product Status</label><br/><br/>
                                    <select name="product_status" id="product_status-<?php echo $product['id'];?>" class="form-control" required>
                                          <option selected disabled> --Select Product Status--</option>
                                          <option value="Not Started" class="five">Not Started</option>
                                          <option value="In Progress Behind Schedule" class="four">In Progress Behind Schedule</option>
                                          <option value="In Progress Within Schedule" class="three">In Progress Within Schedule</option>
                                          <option value="Completed" class="two">Completed</option>
                                          <option value="Continous" class="one text-white">Continous</option>
                                          <option value="Repriotised" class="one text-white">Repriotised</option>
                                    </select>
                                  </div>
                                  <div class="col-md-12 col-xs-12 form-group">
                                    <label> Comments</label><br/><br/>
                                    <textarea placeholder="Add Comments" class="form-control" id="add_product_status_comments-<?php echo $product['id'];?>"></textarea>
                                  </div>
                                  <div class="col-md-12 mt-6">
                                      <button type="submit" class="btn btn-primary btn-block">SUBMIT</button>
                                  </div>

                                </form>
                              </div>
                            </div>
                          </div>
                          <div class="card">
                            <div class="card-header bg-light" data-toggle="collapse" data-target="#collapseThree-<?php echo $product['id'];?>">
                              <h5 class="mb-0">
                                <button class="btn btn-link collapsed text-danger">
                                  <i class="far fa-align-slash"></i> Remove Task
                                </button>
                              </h5>
                            </div>
                            <div id="collapseThree-<?php echo $product['id'];?>" class="collapse" data-parent="#accordion-<?php echo $product['id'];?>">
                              <div class="card-body">
                                <div class="col-md-12">
                                  <button class="btn btn-block btn-danger" type="button" onclick="DeleteTask('<?php echo $product['id'];?>');">
                                    <i class="fad fa-trash-alt"></i> DELETE TASK
                                  </button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- end resource plan modal  -->

              </td>

              <td><?php echo $product['start_date'];?></td>
              <td><?php echo $product['end_date'];?></td>
              <td><?php echo $product['duration'];?></td>
              <td>
                  <?php
                  $sql_resources = mysqli_query($dbc,"SELECT * FROM endproductresources WHERE reference_no='".$product['id']."'");
                  while($resources = mysqli_fetch_array($sql_resources))
                  {
                    ?>
                      <small class="border-bottom">
                        <?php echo $resources['resource_name'];?>
                        <a href="#" class="btn btn-link float-right" onclick="DeleteEndResource('<?php echo $resources['resource_id'];?>');"
                            title="Remove <?php echo $resources['resource_name'];?> from <?php echo $resources['resource_id'];?>">
                           <i class="far fa-user-times text-danger"></i>
                        </a><br/>
                      </small><br/>
                    <?php
                  }

                   ?>
              </td>
              <td>
                <?php

                     $result = mysqli_query($dbc, "SELECT * FROM customer WHERE id ='".$product['customer_id']."' ORDER BY id "  );
                     if(mysqli_num_rows($result))
                     {
                       while($project= mysqli_fetch_array($result))
                       {

                          echo $project['customer_name'];

                       }
                     }
                     ?>


                   </td>



            </tr>
            <?php
          }
        ?>
     </table>


    </div>
  </div>
</div>
