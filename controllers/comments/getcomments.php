<?php
require_once('../setup/connect.php');
session_start();

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  //start of add job seeker
 if(isset($_POST['add-comment']))
{
    $video_id = mysqli_real_escape_string($dbc,strip_tags($_POST['youtube_vid']));
    
    //start comments
                $comments_no = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM videos_comments WHERE video_id ='".$video_id."'"));
?>

 <strong><?php echo $comments_no;?> </strong> Comments

 <table id="example" class="table table-striped table-bordered video_comments" style="width:100%">

   <thead>
     <tr>


    <!--   <td>Status</td> -->
     </tr>
   </thead>
                <?php
                $no = 1;
                 $sql33 = mysqli_query($dbc,"SELECT * FROM videos_comments WHERE video_id ='".$video_id."' ORDER BY id DESC" );
                 while($row33 = mysqli_fetch_array($sql33)){
                 ?>

                 <tr style="cursor: pointer;">


  <td>
    <?php echo $row33['commentor_name']; ?>
  </td>

  <td>
<?php echo $row33['comment_name']; ?>
  </td>
<td>
<time class="timeago" datetime="<?php echo $row33['time_recorded'];?>"><?php echo $row33['time_recorded'];?></time>
</td>
</tr>
<?php
}
?>

</table>
    
   <!--end comments -->
<?php


}

}

//END OF POST REQUEST


?>
