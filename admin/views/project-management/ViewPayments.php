<?php
session_start();
include("../../controllers/setup/connect.php");
$project = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_projects WHERE id='".$_POST['project_id']."'"));
//fetch the budjet for the project to display on the milestone
$project_id = $project['project_id'];

$pm_budjet_internal_query = mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$project_id."' && budget_line='internal'");
$pm_budjet_external_query = mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$project_id."' && budget_line='external'");

$pm_budjet_internal = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$project_id."' && budget_line='internal'"));
$pm_budjet_external = mysqli_fetch_array(mysqli_query($dbc,"SELECT * FROM pm_budget WHERE project_id='".$project_id."' && budget_line='external'"));

?>

<div class="col-lg-12 col-xs-12">
  <div class="card card-primary card-outline">
    <div class="card-header">
      Payments
      <center>Contract Price :
     <?php
           //check if the project contains internal or external currency
           $internal = mysqli_num_rows($pm_budjet_internal_query);
           $external =  mysqli_num_rows($pm_budjet_external_query);



           if( $internal == 1 && $external ==1)
           {
             //both internal and external budget line
             ?>
               <span>
                 (<?php echo $pm_budjet_internal['currency_type'];?>) <?php echo $pm_budjet_internal['amount'];?> <strong> + </strong>
                 (<?php echo $pm_budjet_external['currency_type'];?>) <?php echo $pm_budjet_external['amount'];?>
               </span>
               <input type="hidden" class="internal-contract-price" value="<?php echo $pm_budjet_internal['amount'];?>">
               <input type="hidden" class="external-contract-price" value="<?php echo $pm_budjet_external['amount'];?>">

             <?php
           }
           else if ($internal == 1 && $external == 0)
           {
             //only an internal budjet
             ?>
             (<?php echo $pm_budjet_internal['currency_type'];?>) <?php echo $pm_budjet_internal['amount'];?>
             <input type="hidden" class="internal-contract-price" value="<?php echo $pm_budjet_internal['amount'];?>">
             <?php
           }
           else if ($internal == 0 && $external == 1)
           {
             //only an external budjet
             ?>
               (<?php echo $pm_budjet_external['currency_type'];?>) <?php echo $pm_budjet_external['amount'];?>
               <input type="hidden" class="external-contract-price" value="<?php echo $pm_budjet_external['amount'];?>">
             <?php
           }
           else
           {
             echo "Contract Price not defined.";
           }

      ?>
      <br/>
    </center>

      <button class="btn btn-link" style="float:right;" data-toggle="modal" data-target="#add-payment-modal">
          <i class="fa fa-plus-circle"></i> Add Payment
      </button>


    </div>
    <div class="card-body table-responsive">
     <table class="table table-striped table-bordered table-hover" id="project-payments-table" style="width:100%">
       <thead>
         <tr>
           <td>#</td>
           <td>Payment Due</td>
           <td>Milestone</td>
           <td>Payment Clause</td>
           <td>Anticipated Cost</td>
           <td>Invoices Paid</td>
           <td>Update Invoice</td>
           <td>Edit</td>
         </tr>
       </thead>
       <?php
            $sql_payments = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment0 WHERE project_id='".$project['project_id']."'");
            $no = 1;

            while($row_sql_payments = mysqli_fetch_array($sql_payments))
            {
              $sql_anticipated = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment1
                                                          WHERE
                                                          milestone_payment_id='".$row_sql_payments['milestone_payment_id']."'");

              $sql_anticipated_invoice = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment1
                                                            WHERE
                                                            milestone_payment_id='".$row_sql_payments['milestone_payment_id']."'");

            $sql_anticipated_internal = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment1
                                                            WHERE
                                                            milestone_payment_id='".$row_sql_payments['milestone_payment_id']."'
                                                            && budget_line='internal'");
            $sql_anticipated_external = mysqli_query($dbc,"SELECT * FROM pm_milestone_payment1
                                                           WHERE
                                                           milestone_payment_id='".$row_sql_payments['milestone_payment_id']."'
                                                           && budget_line='external'");

            $sql_anticipated_internal_count = mysqli_num_rows($sql_anticipated_internal);
            $sql_anticipated_external_count =  mysqli_num_rows($sql_anticipated_external);
            $row_anticipated_internal = mysqli_fetch_array($sql_anticipated_internal);
            $row_anticipated_external = mysqli_fetch_array($sql_anticipated_external);

              ?>
              <tr style="cursor: pointer;">
                <td> <?php echo $no++ ;?>.  </td>
                <td><?php echo $row_sql_payments['payment_due'];?> %</td>
                <td><?php echo $row_sql_payments['payment_milestone'];?></td>
                <td><?php echo $row_sql_payments['payment_clause'];?></td>
                <td>
                      <?php
                          $total_rows = mysqli_num_rows($sql_anticipated);
                          $i = 1;
                             while($row_anticipated = mysqli_fetch_array($sql_anticipated))
                             {

                               ?> (<?php echo $row_anticipated['currency'] ;?>) <?php echo number_format($row_anticipated['anticipated_cost'],2);?>

                               <?php
                               if ($i < $total_rows)
                                {
                                  ?>
                                  <p class="text-center font-weight-bold"> + </p>
                                  <?php
                                }
                                $i ++;
                             }
                       ?>
                </td>
                <td>
                  <?php
                      $total_rows = mysqli_num_rows($sql_anticipated_invoice);
                      $i = 1;
                         while($row_anticipated_invoice = mysqli_fetch_array($sql_anticipated_invoice))
                         {
                           if($row_anticipated_invoice['invoice_paid'] == NULL)
                           {
                             echo "-" . "</br>";
                           }
                           else
                           {
                             ?>
                             <a href="views/project-management/documents/<?php echo $row_anticipated_invoice['invoice_file'] ;?>" target="_blank">
                             (<?php echo $row_anticipated_invoice['invoice_number'] ;?>) <br/>
                             <?php echo $row_anticipated_invoice['currency'] ;?>  <?php echo number_format($row_anticipated_invoice['invoice_paid'],2) ;?>
                             </a>
                             <?php
                                if ($i < $total_rows)
                                 {
                                   ?>
                             <p class="text-center font-weight-bold"> & </p>
                             <?php
                             }
                             $i ++;
                           }
                         }
                   ?>
                </td>
                <td>
                  <a class="btn" href="#" data-toggle="modal" data-target="#invoice-payments-modal-<?php echo $row_sql_payments['id'];?>">
                    <i class="fad fa-file-plus fa-lg text-primary"></i>
                  </a>
                  <!-- start invoce payment modal -->
                  <div class="modal fade invoice-payments-modal" id="invoice-payments-modal-<?php echo $row_sql_payments['id'];?>" role="dialog">
                   <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                         <div class="modal-header alert alert-success">
                            <h5 class="modal-title">Invoice Payment for  : <?php echo  $row_sql_payments['payment_milestone'];?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                         </div>
                         <div class="modal-body">
                            <form>
                               <!-- start invoice payment in Ksh and Usd -->
                               <div class="row">
                                  <small class="text-muted">(Changes made here are saved automatically)</small><br/><br/>
                               </div>
                               <div class="row">

                                 <?php
                                 if( $sql_anticipated_internal_count == 1 && $sql_anticipated_external_count ==1)
                                 {
                                   //both internal and external budget line
                                   ?>
                                   <!-- start invoice files -->
                                   <div class="col-lg-4 col-xs-12 form-group">
                                      <label></span>Invoice document (<?php echo $row_anticipated_internal['budget_line'];?>)</label>
                                      <div class="input-group mb-3">
                                         <div class="input-group-prepend">
                                            <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file project-file">
                                            <i class="fal fa-file-alt"></i>  Browse &hellip;
                                            <input type="file" name="file" id="invoice_file-<?php echo $row_anticipated_internal['id'];?>"
                                                  class="form-control invoice_doc"
                                                  onchange="SubmitInvoice('<?php echo $row_anticipated_internal['id'];?>');" single>
                                            </span>
                                            </span>
                                         </div>
                                         <input type="text" class="form-control bg-white invoice-document-label" id="invoice-document-label-<?php echo $row_anticipated_internal['id'];?>" readonly>
                                      </div>
                                      <div class="row invoice-document-feedback" id="invoice-document-feedback-<?php echo $row_anticipated_internal['id'];?>"></div>
                                   </div>
                                   <!-- end invoice files -->
                                   <div class="col-lg-4 col-xs-12 form-group">
                                       <label>Amount in figures (<?php echo $row_anticipated_internal['currency'];?>)</label>
                                       <input data-column-name-payment="invoice_paid:<?php echo $row_anticipated_internal['id'];?>" type="number" min="0" max="<?php echo $pm_budjet_internal['amount'];?>"
                                              class="form-control editable-payment" value="<?php echo $row_anticipated_internal['invoice_paid'] ;?>"
                                               name="anticipated_cost[]">
                                   </div>
                                   <div class="col-lg-4 col-xs-12 form-group">
                                      <label>Invoice number (<?php echo $row_anticipated_internal['budget_line'];?>)</label>
                                         <input type="text" class="form-control editable-payment"
                                                data-column-name-payment="invoice_number:<?php echo $row_anticipated_internal['id'];?>"
                                                value="<?php echo $row_anticipated_internal['invoice_number'];?>"
                                           >
                                   </div>

                                   <!-- start invoice files -->
                                   <div class="col-lg-4 col-xs-12 form-group">
                                      <label></span>Invoice document (<?php echo $row_anticipated_external['budget_line'];?>)</label>
                                      <div class="input-group mb-3">
                                         <div class="input-group-prepend">
                                            <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file project-file">
                                            <i class="fal fa-file-alt"></i>  Browse &hellip;
                                            <input type="file" name="file" id="invoice_file-<?php echo $row_anticipated_external['id'];?>"
                                               class="form-control invoice_doc"
                                               onchange="SubmitInvoice('<?php echo $row_anticipated_external['id'];?>');"
                                               single>
                                            </span>
                                            </span>
                                         </div>
                                         <input type="text" class="form-control bg-white invoice-document-label" id="invoice-document-label-<?php echo $row_anticipated_external['id'];?>" readonly>
                                      </div>
                                      <div class="row invoice-document-feedback" id="invoice-document-feedback-<?php echo $row_anticipated_external['id'];?>"></div>
                                   </div>
                                   <!-- end invoice files -->
                                   <div class="col-lg-4 col-xs-12 form-group">
                                       <label>Amount in figures (<?php echo $row_anticipated_external['currency'];?>)</label>
                                       <input data-column-name-payment="invoice_paid:<?php echo $row_anticipated_external['id'];?>" type="number" min="0" max="<?php echo $pm_budjet_external['amount'];?>"
                                              class="form-control editable-payment" value="<?php echo $row_anticipated_external['invoice_paid'] ;?>"
                                              name="anticipated_cost[]">
                                   </div>
                                   <div class="col-lg-4 col-xs-12 form-group">
                                      <label>Invoice number (<?php echo $row_anticipated_external['budget_line'];?>)</label>
                                         <input type="text" class="form-control editable-payment"
                                                data-column-name-payment="invoice_number:<?php echo $row_anticipated_external['id'];?>"
                                                value="<?php echo $row_anticipated_external['invoice_number'];?>"
                                           >
                                   </div>
                                   <?php
                                 }
                                 else if ($sql_anticipated_internal_count == 1 && $sql_anticipated_external_count == 0)
                                 {
                                   //only an internal budjet
                                   ?>
                                   <!-- start invoice files -->
                                   <div class="col-lg-12 col-xs-12 form-group">
                                      <label></span>Invoice document (<?php echo $row_anticipated_internal['budget_line'];?>)</label>
                                      <div class="input-group mb-3">
                                         <div class="input-group-prepend">
                                            <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file project-file">
                                            <i class="fal fa-file-alt"></i>  Browse &hellip;
                                            <input type="file" name="file" id="invoice_file-<?php echo $row_anticipated_internal['id'];?>"
                                               class="form-control invoice_doc"
                                               onchange="SubmitInvoice('<?php echo $row_anticipated_internal['id'];?>');"
                                               single>
                                            </span>
                                            </span>
                                         </div>
                                         <input type="text" class="form-control bg-white invoice-document-label" id="invoice-document-label-<?php echo $row_anticipated_internal['id'];?>" readonly>
                                      </div>
                                      <div class="row invoice-document-feedback" id="invoice-document-feedback-<?php echo $row_anticipated_internal['id'];?>"></div>
                                   </div>
                                   <!-- end invoice files -->
                                   <div class="col-lg-12 col-xs-12 form-group">
                                       <label>Amount in figures (<?php echo $row_anticipated_internal['currency'];?>)</label>
                                       <input data-column-name-payment="invoice_paid:<?php echo $row_anticipated_internal['id'];?>" type="number" min="0" max="<?php echo $pm_budjet_internal['amount'];?>"
                                              class="form-control editable-payment" value="<?php echo $row_anticipated_internal['invoice_paid'] ;?>"
                                              name="anticipated_cost[]">
                                   </div>
                                   <div class="col-lg-12 col-xs-12 form-group">
                                      <label>Invoice number (<?php echo $row_anticipated_internal['budget_line'];?>)</label>
                                         <input type="text" class="form-control editable-payment"
                                                data-column-name-payment="invoice_number:<?php echo $row_anticipated_internal['id'];?>"
                                                value="<?php echo $row_anticipated_internal['invoice_number'];?>"
                                           >
                                   </div>
                                   <?php
                                 }
                                 else if ($sql_anticipated_internal_count == 0 && $sql_anticipated_external_count == 1)
                                 {
                                   //only an external budjet
                                   ?>
                                   <!-- start invoice files -->
                                   <div class="col-lg-12 col-xs-12 form-group">
                                      <label></span>Invoice document (<?php echo $row_anticipated_external['budget_line'];?>)</label>
                                      <div class="input-group mb-3">
                                         <div class="input-group-prepend">
                                            <span class="input-group-btn">
                                            <span class="btn btn-primary btn-file project-file">
                                            <i class="fal fa-file-alt"></i>  Browse &hellip;
                                            <input type="file" name="file" id="invoice_file-<?php echo $row_anticipated_external['id'];?>"
                                               class="form-control invoice_doc"
                                               onchange="SubmitInvoice('<?php echo $row_anticipated_external['id'];?>');"
                                               single>
                                            </span>
                                            </span>
                                         </div>
                                         <input type="text" class="form-control bg-white invoice-document-label" id="invoice-document-label-<?php echo $row_anticipated_external['id'];?>" readonly>
                                      </div>
                                      <div class="row invoice-document-feedback" id="invoice-document-feedback-<?php echo $row_anticipated_external['id'];?>"></div>
                                   </div>
                                   <!-- end invoice files -->
                                   <div class="col-lg-12 col-xs-12 form-group">
                                     <label>Amount in figures (<?php echo $row_anticipated_external['currency'];?>)</label>
                                     <input data-column-name-payment="invoice_paid:<?php echo $row_anticipated_external['id'];?>" type="number" min="0" max="<?php echo $pm_budjet_external['amount'];?>"
                                            class="form-control editable-payment" value="<?php echo $row_anticipated_external['invoice_paid'] ;?>">
                                   </div>
                                   <div class="col-lg-12 col-xs-12 form-group">
                                      <label>Invoice number (<?php echo $row_anticipated_external['budget_line'];?>)</label>
                                         <input type="text" class="form-control editable-payment"
                                                data-column-name-payment="invoice_number:<?php echo $row_anticipated_external['id'];?>"
                                                value="<?php echo $row_anticipated_external['invoice_number'];?>"
                                           >
                                   </div>
                                   <?php
                                 }
                                 else
                                 {
                                   echo "Contract Price not defined.";
                                 }


                                  ?>
                               </div>
                               <div class="row">
                                 <small class="status-payment text-success"></small><br/>
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
                 <!-- end invoice  payment modal -->
                </td>
                <td>
                  <button type="button" class="btn btn-link" data-toggle="modal" data-target="#edit-milestone-payment-modal-<?php echo $row_sql_payments['id'];?>">
                               <i class="fad fa-edit text-primary"></i>
                   </button>

                   <!-- edit milestone payment modal -->
                   <div class="modal fade milestone-payments-modal" id="edit-milestone-payment-modal-<?php echo $row_sql_payments['id'];?>" role="dialog">
                   <div class="modal-dialog" role="document">
                     <div class="modal-content">
                       <div class="modal-header alert alert-success">
                         <h5 class="modal-title">Editing payments for the project: <br/><?php echo $project['project_name'];?>

                          </h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                           <span aria-hidden="true">&times;</span>
                         </button>
                       </div>
                       <div class="modal-body">
                         <form id="edit-project-milestone-payments-form-<?php echo $row_sql_payments['id'];?>" onsubmit="ModifyMilestonePayment('<?php echo $row_sql_payments['id'];?>');">
                           <input type="hidden" id="milestone_payment_id-<?php echo $row_sql_payments['id'];?>" value="<?php echo $milestone_payment_id;?>">
                           <!-- start of row -->
                           <div class="row">
                               <div class="col-lg-12 col-xs-12 form-group">
                                   <label><span class="required">*</span>Payment Milestone</label>
                                   <select id="payment_milestone-<?php echo $row_sql_payments['id'];?>" data-tags="true" class="select2 form-control" data-placeholder="Select Payment Milestone" required>
                                      <option value="<?php echo $row_sql_payments['payment_milestone'];?>" selected> <?php echo $row_sql_payments['payment_milestone'];?></option>
                                      <?php
                                      $result = mysqli_query($dbc, "SELECT * FROM pm_milestones WHERE project_id ='".$project['project_id']."' ORDER BY id");
                                      while($row = mysqli_fetch_array($result)) {
                                          ?>
                                            <option value=" <?php echo $row['milestone_name'] ;?>"><?php echo $row['milestone_name'] ;?></option>
                                          <?php
                                      }
                                       ?>
                                   </select>
                               </div>
                               <div class="col-lg-12 col-xs-12 form-group">
                                   <label><span class="required">*</span>Payment Clause</label>
                                   <textarea id="payment_clause-<?php echo $row_sql_payments['id'];?>" placeholder="Payment Clause" class="form-control" required><?php echo $row_sql_payments['payment_clause'];?></textarea>
                               </div>

                             </div>
                             <div class="row">
                              <div class="col-lg-12 col-xs-12 form-group">
                                  <label><span class="required">*</span>Payment Due</label>
                                  <select id="payment_due-<?php echo $row_sql_payments['id'];?>" class="select2 form-control payment_due-<?php echo $row_sql_payments['milestone_payment_id'];?>" onchange="ChangePaymentDue('<?php echo $row_sql_payments['milestone_payment_id'];?>');">
                                    <option selected value="<?php echo $row_sql_payments['payment_due'];?>"> <?php echo $row_sql_payments['payment_due'];?> % </option>
                                    <option value="10">10 %</option>
                                    <option value="20">20 %</option>
                                    <option value="30">30 %</option>
                                    <option value="40">40 %</option>
                                    <option value="50">50 %</option>
                                    <option value="60">60 %</option>
                                    <option value="70">70 %</option>
                                    <option value="80">80 %</option>
                                    <option value="90">90 %</option>
                                    <option value="100">100 %</option>
                                  </select>
                              </div>
                            </div>
                               <div class="row">
                                 <?php

                                 if( $sql_anticipated_internal_count == 1 && $sql_anticipated_external_count ==1)
                                 {
                                   //both internal and external budget line
                                   ?>

                                   <div class="col-lg-6 col-xs-12 form-group">
                                       <label>Anticipated Cost (<?php echo $row_anticipated_internal['currency'];?>)</label>
                                       <input readonly id="internal-anticipated-cost-<?php echo $row_sql_payments['milestone_payment_id'];?>"
                                              data-column-name-payment="anticipated_cost:<?php echo $row_anticipated_internal['id'];?>" type="number" step="0.01"
                                              min="0" max="<?php echo $pm_budjet_internal['amount'];?>"
                                              class="form-control  editable-payment" value="<?php echo $row_anticipated_internal['anticipated_cost'] ;?>"  name="anticipated_cost[]">
                                       <input type="hidden" name="anticipated_currency[]" value="<?php echo $row_anticipated_internal['currency'] ;?>">
                                       <input type="hidden" name="anticipated_budget_line[]" value="internal">
                                   </div>

                                   <div class="col-lg-6 col-xs-12 form-group">
                                       <label>Anticipated Cost (<?php echo $row_anticipated_external['currency'];?>)</label>
                                       <input readonly id="external-anticipated-cost-<?php echo $row_sql_payments['milestone_payment_id'];?>"
                                              data-column-name-payment="anticipated_cost:<?php echo $row_anticipated_external['id'];?>" type="number" step="0.01"
                                              min="0" max="<?php echo $pm_budjet_external['amount'];?>" class="form-control editable-payment" value="<?php echo $row_anticipated_external['anticipated_cost'] ;?>" name="anticipated_cost[]">
                                       <input type="hidden" name="anticipated_currency[]" value="<?php echo $row_anticipated_external['currency'] ;?>">
                                       <input type="hidden" name="anticipated_budget_line[]" value="external">
                                   </div>
                                   <?php
                                 }
                                 else if ($sql_anticipated_internal_count == 1 && $sql_anticipated_external_count == 0)
                                 {
                                   //only an internal budjet
                                   ?>
                                   <div class="col-lg-12 col-xs-12 form-group">
                                       <label>Anticipated Cost (<?php echo $row_anticipated_internal['currency'];?>)</label>
                                       <input readonly id="internal-anticipated-cost-<?php echo $row_sql_payments['milestone_payment_id'];?>"
                                              data-column-name-payment="anticipated_cost:<?php echo $row_anticipated_internal['id'];?>" type="number" step="0.01"
                                              min="0" max="<?php echo $pm_budjet_internal['amount'];?>" class="form-control editable-payment" value="<?php echo $row_anticipated_internal['anticipated_cost'] ;?>"  name="anticipated_cost[]">
                                       <input type="hidden" name="anticipated_currency[]" value="<?php echo $row_anticipated_internal['currency'] ;?>">
                                       <input type="hidden" name="anticipated_budget_line[]" value="internal">
                                   </div>
                                   <?php
                                 }
                                 else if ($sql_anticipated_internal_count == 0 && $sql_anticipated_external_count == 1)
                                 {
                                   //only an external budjet
                                   ?>
                                   <div class="col-lg-12 col-xs-12 form-group">
                                     <label>Anticipated Cost (<?php echo $row_anticipated_external['currency'];?>)</label>
                                     <input readonly id="external-anticipated-cost-<?php echo $row_sql_payments['milestone_payment_id'];?>"
                                            data-column-name-payment="anticipated_cost:<?php echo $row_anticipated_external['id'];?>" type="number" step="0.01"
                                            min="0" max="<?php echo $pm_budjet_external['amount'];?>" class="form-control editable-payment" value="<?php echo $row_anticipated_external['anticipated_cost'] ;?>" name="anticipated_cost[]">
                                     <input type="hidden" name="anticipated_currency[]" value="<?php echo $row_anticipated_external['currency'] ;?>">
                                       <input type="hidden" name="anticipated_budget_line[]" value="external">
                                   </div>
                                   <?php
                                 }
                                 else
                                 {
                                   echo "Contract Price not defined.";
                                 }

                                  ?>

                               </div>
                               <div class="row">
                                 <small class="status-payment text-success"></small><br/>
                               </div>
                               <br/>
                           <div class="pull-left mt-4">
                             <small class="text-muted">Recorded by:-</br> <?php echo $_SESSION['name'];?></small>
                           </div>
                                 <!-- start row button -->
                             <div class="col-md-12 text-center">
                                 <button type="submit" class="btn btn-primary btn-block font-weight-bold">SUBMIT</button>
                             </div>
                                 <!-- end row button -->
                         </form>

                       <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                       </div>
                     </div>
                   </div>
                   </div>
                   </div>
                   </div>
                   <!-- end of edit milestone payment modal -->
                </td>
              </tr>
              <?php
            }
        ?>
     </table>
    </div>
  </div>
</div>


<!-- add milestone payment modal -->
<div class="modal fade" id="add-payment-modal" role="dialog">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header alert alert-success">
      <?php
      //fetch last id
       $select_last_id_sql = mysqli_query($dbc,"SELECT milestone_payment_id FROM pm_milestone_payment0 ORDER BY
                                            id DESC LIMIT 1");
        $id_row = mysqli_fetch_array($select_last_id_sql);
        $id = $id_row['milestone_payment_id'];
        $int = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $int = $int+1;

        $milestone_payment_id = "PAY".$int;

        //select
       ?>
      <h5 class="modal-title">Adding payments for the project: <br/><?php echo $project['project_name'];?> <br/>
           Contract Price :
          <?php
                //check if the project contains internal or external currency
                $internal = mysqli_num_rows($pm_budjet_internal_query);
                $external =  mysqli_num_rows($pm_budjet_external_query);



                if( $internal == 1 && $external ==1)
                {
                  //both internal and external budget line
                  ?>
                    <span>
                      (<?php echo $pm_budjet_internal['currency_type'];?>) <?php echo $pm_budjet_internal['amount'];?> <strong> + </strong>
                      (<?php echo $pm_budjet_external['currency_type'];?>) <?php echo $pm_budjet_external['amount'];?>
                     </span>
                     <input type="hidden" class="internal-contract-price" value="<?php echo $pm_budjet_internal['amount'];?>">
                     <input type="hidden" class="external-contract-price" value="<?php echo $pm_budjet_external['amount'];?>">

                  <?php
                }
                else if ($internal == 1 && $external == 0)
                {
                  //only an internal budjet
                  ?>
                  (<?php echo $pm_budjet_internal['currency_type'];?>) <?php echo $pm_budjet_internal['amount'];?>
                  <input type="hidden" class="internal-contract-price" value="<?php echo $pm_budjet_internal['amount'];?>">
                  <?php
                }
                else if ($internal == 0 && $external == 1)
                {
                  //only an external budjet
                  ?>
                    (<?php echo $pm_budjet_external['currency_type'];?>) <?php echo $pm_budjet_external['amount'];?>
                    <input type="hidden" class="external-contract-price" value="<?php echo $pm_budjet_external['amount'];?>">
                  <?php
                }
                else
                {
                  echo "Contract Price not defined.";
                }

           ?>

       </h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <form id="add-project-milestone-payments-form">
        <input type="hidden" name="milestone_payment_id" value="<?php echo $milestone_payment_id;?>">
        <input type="hidden" name="project_id" value="<?php echo $project['project_id'];?>">
        <input type="hidden" name="add_project-milestone-payment" value="add_project-milestone-payment">
        <!-- start of row -->
        <div class="row">


            <div class="col-lg-12 col-xs-12 form-group">
                <label><span class="required">*</span>Payment Milestone</label>
                <?php
                $result = mysqli_query($dbc, "SELECT * FROM pm_milestones WHERE project_id ='".$project['project_id']."' ORDER BY id");
                echo '
                <select name="payment_milestone" data-tags="true" class="select2 form-control" data-placeholder="Select Payment Milestone" required>
                <option></option>';
                while($row = mysqli_fetch_array($result)) {
                    echo '<option value="'.$row['milestone_name'].'">'.$row['milestone_name']."</option>";
                }
                echo '</select>';
                ?>
            </div>
            <div class="col-lg-12 col-xs-12 form-group">
                <label><span class="required">*</span>Payment Clause</label>
                <textarea name="payment_clause" placeholder="Payment Clause" class="form-control" required></textarea>
            </div>

          </div>
          <div class="row">
            <div class="col-lg-12 col-xs-12 form-group">
                <label><span class="required">*</span>Payment Due</label>
                <select name="payment_due" class="select2 form-control payment-due">
                  <option disabled selected> --Select Percentage -- </option>
                  <option value="10">10 %</option>
                  <option value="20">20 %</option>
                  <option value="30">30 %</option>
                  <option value="40">40 %</option>
                  <option value="50">50 %</option>
                  <option value="60">60 %</option>
                  <option value="70">70 %</option>
                  <option value="80">80 %</option>
                  <option value="90">90 %</option>
                  <option value="100">100 %</option>
                </select>
            </div>
          </div>
            <div class="row">
              <?php
              if( $internal == 1 && $external ==1)
              {
                //both internal and external budget line
                ?>
                <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span> Anticipated Cost (<?php echo $pm_budjet_internal['currency_type'];?>)</label>
                    <input type="number" readonly min="0" max="<?php echo $pm_budjet_internal['amount'];?>" step="0.01" class="form-control internal-anticipated-cost"  placeholder="Anticipated cost in figures" name="anticipated_cost[]" onchange="setTwoNumberDecimal">
                    <input type="hidden" name="anticipated_currency[]" value="<?php echo $pm_budjet_internal['currency_type'];?>">
                    <input type="hidden" name="anticipated_budget_line[]" value="internal">
                </div>

                <div class="col-lg-6 col-xs-12 form-group">
                    <label><span class="required">*</span> Anticipated Cost (<?php echo $pm_budjet_external['currency_type'];?>)</label>
                    <input type="number" readonly min="0" max="<?php echo $pm_budjet_external['amount'];?>" step="0.01" class="form-control external-anticipated-cost" placeholder="Anticipated cost in figures" name="anticipated_cost[]" onchange="setTwoNumberDecimal">
                    <input type="hidden" name="anticipated_currency[]" value="<?php echo $pm_budjet_external['currency_type'];?>">
                    <input type="hidden" name="anticipated_budget_line[]" value="external">
                </div>
                <?php
              }
              else if ($internal == 1 && $external == 0)
              {
                //only an internal budjet
                ?>
                <div class="col-lg-12 col-xs-12 form-group">
                    <label><span class="required">*</span> Anticipated Cost (<?php echo $pm_budjet_internal['currency_type'];?>)</label>
                    <input type="number" readonly min="0" max="<?php echo $pm_budjet_internal['amount'];?>" step="0.01" class="form-control internal-anticipated-cost"  placeholder="Anticipated cost in figures" name="anticipated_cost[]" onchange="setTwoNumberDecimal">
                    <input type="hidden" name="anticipated_currency[]" value="<?php echo $pm_budjet_internal['currency_type'];?>">
                    <input type="hidden" name="anticipated_budget_line[]" value="internal">
                </div>
                <?php
              }
              else if ($internal == 0 && $external == 1)
              {
                //only an external budjet
                ?>
                <div class="col-lg-12 col-xs-12 form-group">
                    <label><span class="required">*</span> Anticipated Cost (<?php echo $pm_budjet_external['currency_type'];?>)</label>
                    <input type="number" readonly min="0" max="<?php echo $pm_budjet_external['amount'];?>" step="0.01"  class="form-control external-anticipated-cost" placeholder="Anticipated cost in figures" name="anticipated_cost[]" onchange="setTwoNumberDecimal">
                    <input type="hidden" name="anticipated_currency[]" value="<?php echo $pm_budjet_external['currency_type'];?>">
                    <input type="hidden" name="anticipated_budget_line[]" value="external">
                </div>
                <?php
              }
              else
              {
                echo "Contract Price not defined.";
              }

               ?>

            </div>
            <br/>
        <div class="pull-left mt-4">
          <small class="text-muted">Recorded by:-</br> <?php echo $_SESSION['name'];?></small>
        </div>
              <!-- start row button -->
          <div class="col-md-12 text-center">
              <button type="submit" class="btn btn-primary btn-block font-weight-bold">SUBMIT</button>
          </div>
              <!-- end row button -->
      </form>

    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
  </div>
</div>
</div>
</div>
</div>
<!-- end of add milestone payment modal -->
