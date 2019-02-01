<?php
session_start();
if($_SESSION['valid'] != 1 || $_SESSION['admin'] != 1)    // check if the user-session is valid or not
{
    header('Location: redirect.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        include 'inc/coreIncludes.php';
        $con = connectToDB();
    ?>

    <!-- specific -->

    <!-- JS -->
    <!-- ckeditor-->
    <!-- 4.11.2 -->
    <script type="text/javascript" src="js/ckeditor/4.11.2/ckeditor.js"></script>

    <script type="text/javascript" charset="utf-8">
        $(document).ready(function() {
            $('#example').dataTable( {
                "bSort": false, // dont sort - trust the sql-select and its sort-order
                "sPaginationType": "full_numbers",
                "iDisplayLength" : 25,
                "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]]
            } );
        } );
    </script>
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
        <div class="container">
            <a class="navbar-brand" href="n.php"><img src="images/logo/monoto_logo_white.png" height="26"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="n.php"><i class="fas fa-edit"></i> <?php echo translateString("Notes") ?><span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="m.php"><i class="fas fa-user"></i> <?php echo translateString("MyMonoto") ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="k.php"><i class="fas fa-keyboard"></i> <?php echo translateString("Keyboard") ?></a></li>
                    <li class="nav-item active "><a class="nav-link" href="a.php"><i class="fas fa-cog"></i> <?php echo translateString("Admin") ?></a></li>
                    <li class="nav-item"><a class="nav-link" href="#" onclick="showLogoutDialog();"><i class="fas fa-sign-out-alt"></i> <?php echo translateString("Logout") ?></a></li>
                </ul>
            </div>
        </div>
    </nav> <!-- /navigation -->


    <!-- Page Content -->
    <div class="container theme-showcase" role="main">
        <div id="container">

            <!-- tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#general" role="tab" data-toggle="tab"><?php echo translateString("General information"); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#tasks" role="tab" data-toggle="tab"><?php echo translateString("Tasks"); ?></a>
                </li>
            </ul>


            <!-- Tab panes -->
            <div class="tab-content">

                <!-- Tab: general -->
                <div role="tabpanel" class="tab-pane active in" id="general">
                    <h3><i class="fas fa-sliders-h"></i> <?php echo translateString("Configuration"); ?></h3>
                    <?php
                    if (file_exists('setup.php'))     // check if setup.php still exists - if so - display a warning
                    {
                        echo '<div class="alert alert-danger">';
                        echo translateString("Please delete <i>setup.php</i>. It is a risk to keep that file.");
                        echo '</div>';
                    }
                    ?>


                    <!-- Maintenance Mode -->
                    <div class="row">
                        <div class="col">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo translateString("maintenance mode"); ?>
                        </div>
                        <div class="col">
                            <?php
                            if($s_enable_maintenance_mode == false){ echo "<span class='badge badge-secondary'>false</span>";}else{echo "<span class='badge badge-secondary'>true</span>";}
                            ?>

                        </div>
                        <div class="col">
                            <small>&nbsp;</small>
                        </div>
                    </div>

                    <!-- Version-->
                    <div class="row">
                        <div class="col">
                            <i class="fas fa-cloud"></i> <?php echo translateString("Version"); ?>
                        </div>
                        <div class="col">
                            <span class='badge badge-secondary'><?php echo $m_version; ?></span>
                        </div>
                        <div class="col">
                            <small>&nbsp;</small>
                        </div>
                    </div>


                    <h3><i class="fas fa-database"></i> <?php echo translateString("Database"); ?></h3>
                    <?php
                    // entire db size
                    $result = mysqli_query($con, "SELECT sum( data_length + index_length ) /1024 /1024 FROM information_schema.TABLES WHERE table_schema = '".$mysql_db."' ");
                    while($row = mysqli_fetch_array($result))
                    {
                        $stats_entire_monoto_db_size = $row[0];
                    }
                    echo $stats_entire_monoto_db_size." MB";
                    ?>

                    <!-- Users -->
                    <h3><i class="fas fa-users"></i> <?php echo translateString("Users"); ?></h3>
                    <table cellpadding="0" cellspacing="0" class="display" id="example" style="width: 100%">
                        <thead><tr><th>id</th><th><?php echo translateString("username"); ?></th><th><?php echo translateString("Notes"); ?></th><th><?php echo translateString("logins"); ?></th><th>current failed logins</th><th><?php echo translateString("mail"); ?></th><th>admin</th><th><?php echo translateString("comment"); ?></th></tr></thead>
                        <tbody>
                            <?php
                            // FIXME: shows only accounts with > 0 notes
                            $result = mysqli_query($con, "SELECT u.id, username, count(*), login_counter, failed_logins_in_a_row, email, is_admin, admin_note FROM `m_users` as u, `m_notes` as n WHERE n.owner = u.username GROUP BY username"); // m_log
                            while($row = mysqli_fetch_array($result))   // fill datatable
                            {
                                echo '<tr class="odd gradeU"><td>'.$row[0].'</td><td>'.$row[1].'</td><td>'.$row[2].'</td><td>'.$row[3].'</td><td>'.$row[4].'</td><td>'.$row[5].'</td><td>'.$row[6].'</td><td>'.$row[7].'</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>


                    <!-- Libraries -->
                    <h3><i class="fab fa-js"></i> <?php echo translateString("Libraries"); ?></h3>
                    <!-- jQuery -->
                    <div class="form-group">
                        <label for="libVersionJQuery">jQuery</label>
                        <input type="text" class="form-control" id="libVersionJQuery" aria-describedby="jqueryHelp" placeholder="jquery version" disabled>
                    </div>

                    <!-- CKEditor -->
                    <div class="form-group">
                        <label for="libVersionCKEditor">CKEditor</label>
                        <input type="text" class="form-control" id="libVersionCKEditor" aria-describedby="ckeditorHelp" placeholder="ckeditor version" disabled>
                        <small id="emailHelp" class="form-text text-muted">Required for notes UI</small>
                    </div>

                    <!-- Bootstrap -->
                    <div class="form-group">
                        <label for="libVersionBootstrap">Bootstrap</label>
                        <input type="text" class="form-control" id="libVersionBootstrap" aria-describedby="bootstrapHelp" placeholder="bootstrap version" disabled>
                    </div>

                    <!-- DataTables -->
                    <div class="form-group">
                        <label for="libVersionDataTables">DataTables</label>
                        <input type="text" class="form-control" id="libVersionDataTables" aria-describedby="datatablesHelp" placeholder="datatables version" disabled>
                    </div>

                    <!-- get libraries version and show them -->
                    <script type="text/javascript" charset="utf-8">
                        // jquery
                        if (typeof jQuery != 'undefined')
                        {
                            $("#libVersionJQuery").val(jQuery.fn.jquery);
                        }

                        // ckeditor
                        $("#libVersionCKEditor").val(CKEDITOR.version);

                        // bootstrap
                        var bootstrap_version = ($().modal||$().tab).Constructor.VERSION.split('.');
                        //bootstrap_version = bootstrap_version.replace(",", ".");
                        $("#libVersionBootstrap").val(bootstrap_version);

                        // DataTables
                        var datatables_version = $.fn.dataTable.version;
                        $("#libVersionDataTables").val(datatables_version);
                    </script>
                </div><!-- /tab -->




                <!-- Tab: tasks -->
                <div role="tabpanel" class="tab-pane fade" id="tasks">

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>

                    <!-- delete user -->
                    <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                        <h3><i class="fas fa-user-minus"></i> <?php echo translateString("Delete account"); ?></h3>
                        <table style="width: 100%">
                            <tr>
                                <td width='30%'>Select a user:</td>
                                <td>
                                    <select name="userDeleteSelector" required>
                                        <option value="" disabled selected>Username</option>
                                        <?php
                                        $result = mysqli_query($con, "SELECT id, username  FROM m_users ORDER by id ");
                                        while($row = mysqli_fetch_array($result))   // fill user-select box
                                        {
                                            echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Enter CONFIRM (uppercase)</td>
                                <td><input type="text" name="confirmDeleteUser" placeholder="no" required></td>
                            </tr>
                            <tr>
                                <td>Press the delete button to delete the user and all his notes plus all user-related events in the log</td>
                                <td><button type="submit" class="btn btn-default buttonDefault" name="doDeleteUser"><i class="fas fa-trash-alt"></i> <?php echo translateString("delete"); ?></button> </td>
                            </tr>
                        </table>
                    </form>

                    <!-- spacer -->
                    <div class="row">&nbsp;</div>


                    <!-- invite user -->
                    <h3><i class="fas fa-user-plus"></i> <?php echo translateString("Invite user"); ?></h3>
                    <form id="inviteForm" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                        <table style="width: 100%">
                            <tr>
                                <td width='30%'>Username:</td>
                                <td><input type="text" name="newUsername" placeholder="Username" required="required" /></td>
                            </tr>
                            <tr>
                                <td>Mail:</td>
                                <td><input type="email" name="newUserMail" placeholder="Email" required="required" /></td>
                            </tr>
                            <tr>
                                <td>Password:</td>
                                <td><input type="password" name="newPassword1" placeholder="Password" required="required" autocomplete="off" /></td>
                            </tr>
                            <tr>
                                <td>Repeat Password:</td>
                                <td><input type="password" name="newPassword2" placeholder="Repeat password" required="required" autocomplete="off" /></td>
                            </tr>
                            <tr>
                                <td>Send notification mail to new user: (optional)</td>
                                <td><input type="checkbox" name="sendNotification" value="sendNotification" /></td>
                            </tr>
                            <tr>
                                <td>Admin note about this invite or user: (optional)</td>
                                <td><input type="text" name="newUserNote" placeholder="Comment" /></td>
                            </tr>
                            <tr>
                                <td><button type="submit" class="btn btn-default buttonDefault" name="doCreateNewUserAccount" value="Invite" title="Starts the add user function if all informations are provided."><i class="fas fa-envelope"></i> <?php echo translateString("invite"); ?></button></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </form>



                    <h3><i class="fas fa-envelope"></i> <?php echo translateString("Broadcast message"); ?></h3>
                    <div class="panel-body">Send an email to all monoto-accounts.
                        <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                            <input type="text" placeholder="Subject" name="broadcastSubject" style="width:100%"><br>
                            <textarea rows="4" cols="50" style="width:100%" placeholder="Insert your broadcast message text here" name="broadcastMessage"></textarea><br>
                            <button type="submit" class="btn btn-default buttonDefault" name="doSendBroastcast" value="Send" style="width:200px" title="Sends a broadcast email to all users." /><i class="fas fa-envelope"></i> send

                        </button>

                    </form>
                </div>

                <h3><?php echo translateString("Misc"); ?></h3>
                <form action="<?php echo $_SERVER['SCRIPT_NAME']; ?>" method="post" enctype="multipart/form-data">
                    <button type="submit" class="btn btn-info" name="doOptimize" value="Optimize" title="Starts MySQL Optimize tables command.">Optimize tables</button> This will optimize your entire monoto mysql database.<br><br>
                    <button type="submit" class="btn btn-warning" name="doTruncateEvents" value="Truncate events" title="Deletes the entire content of the event-table. Affects all users. Be careful with that.">Truncate events</button> Warning: This will delete <b>ALL events</b> from the table: m_log.<br><br>
                    <button type="submit" class="btn btn-danger" name="doTruncateNotes" value="Truncate notes" title="Deletes the entire content of the notes-table. Affects all users. Be careful with that too.">Truncate notes</button> Warning: This will delete <b>ALL notes</b> from the table: m_notes.
                </form>

            </div>

            <!-- footer -->
            <?php require 'inc/footer.php'; ?>

        </div>

    </div> <!-- /container -->

    <!-- JS-->
    <script type="text/javascript" src="js/cookie/jquery.cookie.js"></script>
</body>
</html>


<?php
require 'conf/config.php';

// Send broastcast to all users (email)
if ( isset($_POST["doSendBroastcast"]) )
{
    $messageSubject = $_POST["broadcastSubject"];
    $messageText     = $_POST["broadcastMessage"];
    if (($messageText != "") && ($messageSubject != ""))
    {
        // select all users & email-data
        $query = "SELECT username, email FROM m_users;";
        $result = mysqli_query($con, $query);
        while($row = mysqli_fetch_array($result))
        {
            $username = $row[0];
            $email = $row[1];
            if(@mail($email, $messageSubject, $messageText)) // try to send notification email
            {
                displayNoty("Notification emails sent.","success");
            }
            else
            {
                displayNoty("Unable to sent notification mails.","error");
            }
        }
    }
    else
    {
        displayNoty("Error: Please enter a message text","error");
    }
}


//
// DELETE USER
//
if ( isset($_POST["doDeleteUser"]) )
{
    $userID         = $_POST['userDeleteSelector'];
    $confirmText    = $_POST['confirmDeleteUser'];
    if ($userID !="")
    {
        if($confirmText == "CONFIRM")
        {
            // get username to selected ID
            $query = "SELECT username FROM m_users WHERE id = '$userID';";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_array($result))
            {
                $usernameToDelete = $row[0];
            }

            // delete user
            $sql="DELETE FROM m_users WHERE id='$userID'";
            $result = mysqli_query($con, $sql);
            if (!$result)
            {
                die('Error: ' . mysqli_connect_error());
            }
            else  // update m_log
            {
                $event = "User delete";
                $details = "User: <b>".$userID." </b>is now gone.";
                $sql="INSERT INTO m_log (event, details, activity_date, owner) VALUES ('$event', '$details', now(), '$owner' )";
                $result = mysqli_query($con, $sql);

                // delete his notes as well
                $sql="DELETE FROM m_notes WHERE owner='$usernameToDelete'";
                $result = mysqli_query($con, $sql);

                // delete his log as well
                $sql="DELETE FROM m_log WHERE owner='$usernameToDelete'";
                $result = mysqli_query($con, $sql);

                displayNoty("Deleted user, his notes and the related log entries","notification");
            }
            mysqli_close($con);                                 // close sql connection
        }
        else // user hasnt entered CONFIRM
        {
            displayNoty("Please enter CONFIRM in the related field and try it again","error");
        }
    }
    else
    {
        displayNoty("Please select a user first","error");
    }
}


//
// OPTIMIZE MYSQL TABLES
//
if ( isset($_POST["doOptimize"]) )
{
    connectToDB();  // connect to mysql

    // select all table with (> 10% overhead) AND at (least > 100k free space)
    $res = mysqli_query($con, 'SHOW TABLE STATUS WHERE Data_free / Data_length > 0.1 AND Data_free > 102400');
    while($row = mysqli_fetch_assoc($res))
    {
        mysqli_query($con, 'OPTIMIZE TABLE ' . $row['Name']);
    }
    displayNoty("Database optimized","notification");
}

//
// TRUNCATE EVENTS
//
if ( isset($_POST["doTruncateEvents"]) )
{
    $con = connectToDB(); // connect to mysql
    mysqli_query($con, 'TRUNCATE TABLE m_log'); // truncate log-/events-table
    displayNoty("Truncated all eventlog entries","notification");
}

//
// TRUNCATE NOTES
//
if ( isset($_POST["doTruncateNotes"]) )
{
    $con = connectToDB(); // connect to mysql
    mysqli_query($con, 'TRUNCATE TABLE m_notes'); // truncate notes-table
    displayNoty("Truncated all user notes","notification");
}

//
// CREATE NEW USER
//
if ( isset($_POST["doCreateNewUserAccount"]) )
{
    $con = connectToDB();  // connect to mysql

    $invite_from     = $_SESSION['username'];
    // need  full page url for link in the invite mail
    $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    }
    else
    {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    //$invite_target     = $_SERVER['SERVER_NAME'];
    $invite_target     = $pageURL;
    // store values on vars
    $newPassword1     = $_POST['newPassword1'];
    $newPassword2     = $_POST['newPassword2'];
    $newUsername     = $_POST['newUsername'];
    $newUserMail     = $_POST['newUserMail'];
    $sendNotification = $_POST['sendNotification'];        // optional
    $newUserNote = $_POST['newUserNote'];                // optional

    // check if password is ok
    if($newPassword1 == $newPassword2) //& passwords match - we can continue trying to create this user
    {
        // check if account-name is available
        $result = mysqli_query($con, "SELECT count(username) FROM m_users WHERE username='$newUsername' "); // run the mysql query
        while($row = mysqli_fetch_array($result)) // fetch data and file table as a second step later on
        {
            if($row[0] == 0)  // username is free
            {
                // check if we got an emailaddress
                if(strlen($newUserMail) > 0)
                {
                    // create the new user account
                    $username    = $newUsername;
                    $password     = $newPassword1;
                    $hash = hash('sha256', $password); // playing with hash
                    function createSalt() // playing with salt - creates a 3 character sequence
                    {
                        $string = md5(uniqid(rand(), true));
                        return substr($string, 0, 3);
                    }
                    $salt = createSalt();
                    $hash = hash('sha256', $salt . $hash);

                    $query = "INSERT INTO m_users ( username, password, salt, date_invite, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt' , now() , '$newUserMail', '$newUserNote');";
                    echo $query;
                    writeToConsoleLog($query);
                    writeToConsoleLog("test--------------");

                    mysqli_query($con, $query);

                    displayNoty("Created new user account","notification");
                    //echo '<script>$.cookie("lastAction", "Note "+modifiedNoteTitle+" saved.");</script>';        // store last Action in cookie

                    // we should log that to m_notes -> admin only.
                    // check if we should send a notification as well
                    if($sendNotification == 'sendNotification' )
                    {
                        if($newUserMail != '')
                        {
                            $to = $newUserMail;
                            $subject = "monoto-notes invite";
                            $body = "Hi,
                            \n".$invite_from." invited you to monoto - his web-based notes solution.
                            \nFeel free to use it as your personal notes keeper as well.
                            \n\nYou can get some general informations about monoto here: https://github.com/macfidelity/monoto/wiki.
                                \n\n\n\nThe login credentials are as follows:
                                \nUsername: ".$username."
                                \nPassword: ".$password."
                                \n\n\nPlease change your password after your first visit at:
                                \n".$invite_target."
                                \n\nHave fun.";
                                if (mail($to, $subject, $body))
                                {
                                }
                                else
                                {
                                }
                                displayNoty("Notification mail send","notification");
                            }
                        }
                    }
                    else // no usermail-adress defined while trying to create new account
                    {
                        displayNoty("No mail address defined.","error");
                    }
                }
                else // username already in use - cancel and inform the admin
                {
                    displayNoty("This mail-adress is already in use","error");
                }
            }
        }
        else // passwords not matching
        {
            displayNoty("Passwords are not matching","error");
        }
    }
    ?>