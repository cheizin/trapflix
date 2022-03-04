//file
$type = explode('.',$_FILES['emp_photo']['name']);
$type = $type[count($type) -1];
//$url = '../files/' .uniqid(rand()) . '.' . $type;
$url = '../dist/img/' .$photoss;

//check if there is a document attached
//  if(!empty($photoss))
//  {
    //check file size
    $max_allowed_size = ini_get('post_max_size');
    $size =  (int) filter_var($max_allowed_size, FILTER_SANITIZE_NUMBER_INT) * 1000000;
    if(filesize($photoss) > $size)
    {
      exit("file upload limit is 8mb");
    }
    if(!in_array($type, array('jpg','png','JPG','PNG')))
    {
      exit("invalid");
    }
    if(in_array($type, array('jpg','png','JPG','PNG')))
    {
        if(is_uploaded_file($_FILES['emp_photo']['tmp_name']))
        {
            if(move_uploaded_file($_FILES['emp_photo']['tmp_name'],$url))
            {
                //check for duplicate Reference Number or Programme Name
              /*  $sql_check = mysqli_num_rows(mysqli_query($dbc,"SELECT * FROM staff_users WHERE EmpNo='".$emp_no."'"));
                if($sql_check > 0)
                {
                    exit("duplicate");
                }
*/


$sql_statement = "UPDATE staff_users SET emp_photo='".$photoss."'

                    WHERE EmpNo='".$emp_no."';
                    ";


//check if query runs

if($insert_query = mysqli_query($dbc,$sql_statement))
{
    exit ("success");
}
else
{
    exit (mysqli_error($dbc));
  }
}
}
}
