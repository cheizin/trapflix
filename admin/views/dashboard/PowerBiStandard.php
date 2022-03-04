<?php
session_start();

?>
<div class="row">
  <div class="col-lg-12">

<?php

  if($_SESSION['department_code'] == 'CA')
  {
    ?>
<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiOGNmODE4YzgtZjYzMy00NzI3LWFiODEtMzgyOTMyNzlmM2QyIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
 frameborder="0" allowFullScreen="true"></iframe>

<?php
}
?>

<?php
if($_SESSION['department_code'] == 'CC')
{
  ?>
  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZGY5Y2EzODgtMTcxOC00NjBiLTg3YzgtNjhmNWU0MmY2MmNkIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
   frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>


<?php
  if($_SESSION['department_code'] == 'SC')
  {
    ?>
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDdhNjA1OWQtY2VkNy00OGVjLTkzZWQtZGIxMGI3ODI3NmNiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
     frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>
<?php
if($_SESSION['department_code'] == 'FIN')
{
  ?>

  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDUzODhmYWQtMTBmNS00OWVkLWExMDYtYmU3Yzg2ZjQzMDdkIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
   frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>
<?php
if($_SESSION['department_code'] == 'HCA')
{
  ?>

  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMDllZjhkMmItNjUwYS00NTdhLWIzOWYtNjU3YTQwN2RkZjNjIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
  frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>
<?php
if($_SESSION['department_code'] == 'ICT')
{
  ?>

  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZjg4ZTZhZGQtMzA2MS00ZDlhLWJhMDktMWI2YWU4NzdlZTM3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
  frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>

<?php
if($_SESSION['department_code'] == 'IARM')
{
  ?>
  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiOGU1NTViODMtZjcwNC00MTJkLTg4MGMtZjFmMGNkNzA3ZTMwIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
   frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>

<?php
  if($_SESSION['department_code'] == 'SRPT')
  {
    ?>

<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNDJlZTA1NmMtMmY0MS00YWI4LTliYWMtYTZhM2FlMjk4Njg0IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>
<?php
if($_SESSION['department_code'] == 'IE')
{
  ?>
<iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMTM5MjczNjEtNDA3My00Y2JiLWExYWItMTI2ZjBlODk1MzA3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
 frameborder="0" allowFullScreen="true"></iframe>
  <?php
  }
  ?>

  <?php
    if($_SESSION['department_code'] == 'IEPA')
    {
      ?>
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMGZiNzFmNjUtZjI1Mi00OTlhLTk0M2UtNmZlNWZkNzE4NDI0IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>
<?php
}
?>

<?php
  if($_SESSION['department_code'] == 'LACS')
  {
    ?>
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiMjlkOGY5YzMtZDI5NC00NDhiLTk5YjQtYjI4MDI0M2NjODJiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
    frameborder="0" allowFullScreen="true"></iframe>
  <?php
  }
  ?>


<?php
  if($_SESSION['department_code'] == 'MS')
  {
    ?>
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiODhiNTAyMzQtYmNlMC00YTk4LTkwZjYtZTVkY2EwNTAyNDA5IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
     frameborder="0" allowFullScreen="true"></iframe>
  <?php
  }
  ?>
  <?php
    if($_SESSION['department_code'] == 'PROC')
    {
      ?>
    <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiN2EwY2MyOWUtMzhhYy00MzQ3LTg4YzQtMDk3OTU4OTkwYTg3IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9" frameborder="0" allowFullScreen="true"></iframe>

  <?php
  }
  ?>



<?php
if($_SESSION['department_code'] == 'PRF')
{
  ?>
  <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNzdjNTJmM2QtOGNkMC00MjI3LThiMmYtMDZiNDcyZDE4OGNiIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
   frameborder="0" allowFullScreen="true"></iframe>
  <?php
  }
  ?>

  <?php
    if($_SESSION['department_code'] == 'RA')
    {
      ?>
      <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiNmRjNjJiZTEtOTcxYi00YWZlLWI3ZGItYzA4Mzk0OWFjYTBlIiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
      frameborder="0" allowFullScreen="true"></iframe>
      <?php
      }
      ?>

      <?php
        if($_SESSION['department_code'] == 'MD')
        {
          ?>
          <iframe width="100%" height="700" src="https://app.powerbi.com/view?r=eyJrIjoiZGMzYjVjMDUtOGI1Yy00NGY3LWI0MjktNTIyMmVhOTIzMjM2IiwidCI6IjNhZDg3OGViLTBjYzYtNDYwYy04ODA2LWI0MDNkYWE4YzQzZiIsImMiOjh9"
          frameborder="0" allowFullScreen="true"></iframe>
          <?php
          }
          ?>
</div>
</div>
