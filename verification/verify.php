<?php
require_once('../controllers/setup/connect.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if(isset($_POST['verify']))
    {
        $verification_code = mysqli_real_escape_string($dbc,strip_tags($_POST['code']));
        
       $sql = mysqli_query($dbc, "SELECT email FROM user_verification_codes WHERE verification_code='".$verification_code."'");
        
        if(mysqli_num_rows($sql) > 0)
        {
                
            $sql_update = mysqli_query($dbc,"UPDATE users SET is_verified=1 WHERE email IN 
    
                               (SELECT email FROM user_verification_codes WHERE verification_code='".$verification_code."')
                             ");
                             
            
            if($sql_update)
            {
                exit('success');
            }
        }
        else 
        {
            exit('invalid');
        }
        
                             
        
    }
}

?>