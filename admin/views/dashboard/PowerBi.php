<?php
session_start();
include("../../controllers/setup/connect.php");
 ?>
<ul class="nav nav-tabs" role="tablist">
  <?php
  $sql_query = mysqli_query($dbc,"SELECT * FROM departments
                                  WHERE department_id!='SPU' && department_id!='SP' && department_id!='RF' ORDER BY department_id ASC");
                                  $number = 1;
  while($row = mysqli_fetch_array($sql_query))
  {

  ?>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#<?php echo $row['department_id'];?>" role="tab">
        <?php echo $number++;?> <?php echo $row['department_id'];?>
    </a>
  </li>
  <?php
    }
   ?>
</ul>
<div class="tab-content">
   <div id="CES OFFICE" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiYTFiNmIwOTQtNzFlYS00MWQxLWI5ZTAtODczZDllMzNlNGYzIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="CA" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiOGNmODE4YzgtZjYzMy00NzI3LWFiODEtMzgyOTMyNzlmM2QyIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="CC" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZGY5Y2EzODgtMTcxOC00NjBiLTg3YzgtNjhmNWU0MmY2MmNkIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="SC" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDdhNjA1OWQtY2VkNy00OGVjLTkzZWQtZGIxMGI3ODI3NmNiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="FIN" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDUzODhmYWQtMTBmNS00OWVkLWExMDYtYmU3Yzg2ZjQzMDdkIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id= "HCA" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDllZjhkMmItNjUwYS00NTdhLWIzOWYtNjU3YTQwN2RkZjNjIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="ICT" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZjg4ZTZhZGQtMzA2MS00ZDlhLWJhMDktMWI2YWU4NzdlZTM3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="IARM" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiOGU1NTViODMtZjcwNC00MTJkLTg4MGMtZjFmMGNkNzA3ZTMwIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="SRPT" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNDJlZTA1NmMtMmY0MS00YWI4LTliYWMtYTZhM2FlMjk4Njg0IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="IE" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMTM5MjczNjEtNDA3My00Y2JiLWExYWItMTI2ZjBlODk1MzA3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="IEPA" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMGZiNzFmNjUtZjI1Mi00OTlhLTk0M2UtNmZlNWZkNzE4NDI0IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="IG" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMTE1YjYxOWQtOGUxZS00N2I3LWE2NDgtZGZiOGFhYTdkNjc5IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="LACS" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMjlkOGY5YzMtZDI5NC00NDhiLTk5YjQtYjI4MDI0M2NjODJiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="MD" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZGMzYjVjMDUtOGI1Yy00NGY3LWI0MjktNTIyMmVhOTIzMjM2IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="RA" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNmRjNjJiZTEtOTcxYi00YWZlLWI3ZGItYzA4Mzk0OWFjYTBlIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="MS" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiODhiNTAyMzQtYmNlMC00YTk4LTkwZjYtZTVkY2EwNTAyNDA5IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="PROC" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiN2EwY2MyOWUtMzhhYy00MzQ3LTg4YzQtMDk3OTU4OTkwYTg3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
   </div>
   <div id="PRF" class="tab-pane fade">
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNzdjNTJmM2QtOGNkMC00MjI3LThiMmYtMDZiNDcyZDE4OGNiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
         frameborder="0" allowFullScreen="true"></iframe>
   </div>
</div>
