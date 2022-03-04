<?php
if($_SERVER['REQUEST_METHOD'] == "POST")
{
  session_start();
  include("../../controllers/setup/connect.php");
  if($_SESSION['access_level']!='admin')
  {
	  exit("unauthorised");
  }

  $sign_in_name = mysqli_real_escape_string($dbc,strip_tags($_POST['sign_in_name']));
  $password = mysqli_real_escape_string($dbc,strip_tags($_POST['password']));
?>
    <div class="col-xs-12">
      <div class="card">
        <!-- /.card-header -->
        <div class="card-body table-responsive no-padding">
          <table class="table table-hover" id="ldap-users-table" width="100%">
            <thead>
              <tr>
                <td>NO</td>
                <td>NAME</td>
                <td>USERNAME</td>
                <td>EMAIL</td>
                <td>DEPARTMENT</td>
                <td>MEMBER OF</td>
                <td>PICTURE</td>
              </tr>
            </thead>
            <?php
              // Get vars from post
              $no = 1;
              $ldapdomain	= "Cma.local";
              $ldapip		= "ldap://10.0.70.1";
              $ldapport	= 389;
              $name = $sign_in_name;
              $username = 'CMAKE' . '\\' . $name;
              $password = $password;
              // Open the connection
              $ad = ldap_connect($ldapdomain,$ldapport) or die("Couldn't connect to AD!"); // ldap://servername
              ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION,3);
              ldap_set_option($ad, LDAP_OPT_REFERRALS,0);
              $bd = ldap_bind($ad,$username,$password) or die("Couldn't bind to AD!");
              // Do stuff
              // Create the DN
              $dn = "DC=Cma,DC=local"; // CN=foo,OU=bar etc...


            // Create the filter from the search parameters
            $filter = "(&(&(&(objectCategory=person)(objectClass=user)(!(UserAccountControl:1.2.840.113556.1.4.803:=2)))))"; //Select all users

            $justthese = array("ou", "sn", "displayname","givenname", "mail","department",
                                "memberof", "SAMAccountName","thumbnailPhoto","telephoneNumber");

            $search = @ldap_search($ad, $dn, $filter,$justthese) or die ("ldap search failed".ldap_error($ad));

            $entries = ldap_get_entries($ad, $search);

            if ($entries["count"] > 0)
              {
                for ($i=0; $i<$entries["count"]; $i++)
                  {

             ?>
            <tr>
              <td><?php echo $no++;?></td>
              <td>
                <?php
                 if(isset($entries[$i]["displayname"][0]))
                    {
                      echo $entries[$i]["displayname"][0];
                    }
                     else
                     {
                       echo "Name not set";
                     }
                  ?>
              </td>
              <td>
                <?php
                 if(isset($entries[$i]["samaccountname"][0]))
                    {
                      echo $entries[$i]["samaccountname"][0];
                    }
                     else
                     {
                       echo "Username not set";
                     }
                  ?>
              </td>
              <td>
                <?php
                 if(isset($entries[$i]["mail"][0]))
                    {
                      echo $entries[$i]["mail"][0];
                    }
                     else
                     {
                       echo "Mail not set";
                     }
                  ?>
              </td>
              <td>
                <?php
                 if(isset($entries[$i]["department"][0]))
                    {
                      echo $entries[$i]["department"][0];

                    }
                     else
                     {
                       echo "Department not set";
                     }
                  ?>
              </td>
              <td>
                <?php
                 if(isset($entries[$i]["memberof"][0]))
                    {
                      //echo $entries[$i]["memberof"][0];
                      $group = explode(",",$entries[$i]["memberof"][0],2);
                      $member = explode("=",$group[0]);
                      echo $member[1] . "";
                    }
                     else
                     {
                       echo "group not set";
                     }
                  ?>
              </td>
              <td>
                <?php
                 if(isset($entries[$i]["thumbnailphoto"][0]))
                    {
                      ?>
                        <img class="profile-user-img img-fluid img-circle" src="data:image/jpeg;base64,<?php echo base64_encode($entries[$i]["thumbnailphoto"][0]); ?>" />
                      <?php
                    }
                     else
                     {
                       echo "Picture not set";
                     }
                  ?>
              </td>
            </tr>
            <?php
          }
        }
        else
          {
            echo "<p>No results found!</p>";
          }
            ldap_unbind($ad);
             ?>
            <tfoot>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Member of</th>
                    <th>Picture</th>
                </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>


  <?php
}



 ?>
