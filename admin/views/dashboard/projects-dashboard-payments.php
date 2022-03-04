<?php
  session_start();
  include("../../controllers/setup/connect.php");

  if(!$_SERVER['REQUEST_METHOD'] == "POST")
  {
    exit();
  }

 ?>

 <table class="table table-bordered table-striped table-hover" id="dashboard-project-payments-table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Project</th>
      <th scope="col">Funding Agency</th>
      <th scope="col">Contract Price</th>
      <th scope="col">Milestone Payments</th>
    </tr>
  </thead>
  <tbody>
    <?php
        $no =1;

        $projects_sql = mysqli_query($dbc,"SELECT * FROM pm_projects");

        while($projects_row = mysqli_fetch_array($projects_sql))
        {
          ?>
          <tr>
            <th scope="row"><?php echo $no++;?></th>
            <td>
              <span>
                <small>
                  <?php echo $projects_row['project_name'];?>
                </small><br/>
              </span>
            </td>
            <td>
                <?php
                    $funding = mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$projects_row['project_id']."' GROUP BY funding_agency");
                    while($funding_agency = mysqli_fetch_array($funding))
                    {
                        echo $funding_agency['funding_agency'];
                    }
                 ?>

            </td>
            <td>
              <?php
                  $contract_price = mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$projects_row['project_id']."'");
                  $total_rows = mysqli_num_rows($contract_price);
                  $i = 1;
                  while($row_contract_price = mysqli_fetch_array($contract_price))
                  {
                    ?>
                      <span>(<?php echo $row_contract_price['currency_type'];?>) <?php echo number_format($row_contract_price['amount']);?>
                            ~ <small class="text-muted"><?php echo ucwords($row_contract_price['budget_line']);?> Budget</small>
                      </span>
                    <?php
                      if ($i < $total_rows)
                       {
                         ?>
                         <p class="text-center font-weight-bold"> & </p>
                         <?php
                        }
                        $i ++;
                  }
               ?>
            </td>
            <td>
              <?php
                  $paid = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment1 WHERE project_id='".$projects_row['project_id']."'");
                  $total = mysqli_num_rows($paid);
                  $total_no = 1;

                  $n = 1;
                  while($row_paid = mysqli_fetch_array($paid))
                  {
                    $check_milestone_paid = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_milestone_payment0
                                                              WHERE milestone_payment_id='".$row_paid['milestone_payment_id']."'"));


                    $difference = $row_paid['anticipated_cost'] - $row_paid['invoice_paid'];
                    $difference = number_format($difference);
                    ?>
                    <span  tabindex="0" data-html="true" data-toggle="popover" data-trigger="focus" class="text-primary"
                          data-content="<div class='text-primary' style='cursor:pointer;'>
                             Anticipated Cost: (<?php echo $row_paid['currency'];?>) <?php echo number_format($row_paid['anticipated_cost']);?> . <br/>
                             Paid Invoice: (<?php echo $row_paid['currency'];?>) <?php echo number_format($row_paid['invoice_paid']);?> . <br/>
                             Balance: (<?php echo $row_paid['currency'];?>)  <?php echo $difference;?> .<br/>
                             Payment Clause:
                            <?php echo $check_milestone_paid['payment_due'] ;?> % of <?php echo $check_milestone_paid['payment_clause'] ;?> . <br/>
                               Payment Milestone: <?php echo $check_milestone_paid['payment_milestone'] ;?>
                          </div>"
                          style="cursor:pointer;" data-original-title="<b>Paid Milestones</b>"><?php echo $n++ ;?> .
                          (<?php echo $row_paid['currency'];?>) <?php echo number_format($row_paid['invoice_paid']);?>
                          ~ <small><?php echo ucwords($row_paid['budget_line']);?> Budget</small>
                          <br/>
                          <small>(Invoice No:<?php echo $row_paid['invoice_number'];?>)</small>
                    </span>
                    <?php
                    if ($total_no < $total)
                     {
                       ?>
                       <p class="text-center font-weight-bold"> & </p>
                       <?php
                      }
                      $total_no ++;
                  }

               ?>
            </td>
          </tr>
          <?php
        }
     ?>
  </tbody>
</table>
<script>
$("[data-toggle=popover]").popover();
</script>
