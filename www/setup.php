<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'inc/genericIncludes.php'; ?>

    <!-- specific -->
    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/monoto/setup.css">

    <script type="text/javascript" charset="utf-8">
    $(document).ready( function ()
    {
        // #281
        // compare input in password fields
        // and enable or disable the 'update password' button
        $('#newPassword, #newPasswordConfirm').on('keyup', function ()
        {
            validatePasswordChangeInput();
        });

    } );
    </script>
</head>

<body role="document">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="setup.php"><img src="images/logo/monotoLogoWhite.png" width="63" height="25"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link js-scroll-trigger" href="#requirements">Installer</a>
                    </li>
                </ul>
            </div>

        </div>
    </nav>

    <!-- spacer -->
    <div class="row">&nbsp;</div>
    <div class="row">&nbsp;</div>

    <!-- section: requirements -->
    <section id="requirements" class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2><i class="fas fa-puzzle-piece fa-2x"></i> Requirements</h2>
                    <p class="lead">
                        <?php
                            checkGetTextSupport();
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- /requirements -->

    <!-- spacer -->
    <div class="row">&nbsp;</div>


    <!-- section: database -->
    <section id="database" class="bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2><i class="fas fa-database fa-2x"></i> Database</h2>
                    <p class="lead">Please create a database and all related tables according to the instructions in <span class="badge badge-secondary">docs/INSTALL.md</span> and adjust the values in <span class="badge badge-secondary">config/databaseConfig.php</span> according to it.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- /database -->

    <!-- spacer -->
    <div class="row">&nbsp;</div>

    <!-- section: account -->
    <section id="account">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h2><i class="fas fa-user-circle fa-2x"></i> Account</h2>
                    <p class="lead">As final step you can create your initial admin account</p>
                    <form name="login" action="setup.php" method="post" enctype="multipart/form-data">

                        <!-- Username -->
                        <div class="row">
                            <div class="col">
                                <label for="username">Username</label>
                            </div>
                            <div class="col">
                                <input type="text" name="username" placeholder="Username" required="required" autocomplete="username"/>
                            </div>
                            <div class="col">
                                <small>(max. 64 chars)</small>
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <!-- /username -->

                        <!-- Mail -->
                        <div class="row">
                            <div class="col">
                                <label for="email">Email</label>
                            </div>
                            <div class="col">
                                <input type="email" name="email" placeholder="foo@bar.com" required="required" autocomplete="email" />
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>

                        </div>
                        <!-- /Mail -->

                        <!-- Password  -->
                        <div class="row">
                            <div class="col">
                                Password
                            </div>
                            <div class="col">
                                <input type="password" id="newPassword" name="newPassword" placeholder="Password" required="required" pattern=".{8,}" autocomplete="off" onkeyup="calculatePasswordStrength()" />
                            </div>
                            <div class="col">
                                <small>(min 8 characters)</small>
                            </div>
                            <div class="col">
                                <span id="passstrength"></span>
                            </div>

                        </div>
                        <!-- /Password -->

                        <!-- Password confirm -->
                        <div class="row">
                            <div class="col">
                                Password
                            </div>
                            <div class="col">
                                <input type="password" id="newPasswordConfirm" name="newPasswordConfirm" placeholder="Password" required="required" pattern=".{8,}" autocomplete="off" />
                            </div>
                            <div class="col">
                                <small>(confirm password)</small>
                            </div>
                            <div class="col">
                                <span id="passwordDiff"></span>
                            </div>
                        </div>
                        <!-- /password confirm -->

                        <!-- Submit -->
                        <div class="row">
                            <div class="col">
                                <input type="submit" class="btn btn-primary" value="Create" id="bt_continue" name="bt_continue"  disabled=disabled />
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                            <div class="col">
                                &nbsp;
                            </div>
                        </div>
                        <!-- /Submit -->
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- / accounts -->

    <!-- spacer -->
    <div class="row">&nbsp;</div>

    <!-- warning to delete setup.php-->
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="alert alert-danger"><strong><i class="fas fa-skull-crossbones"></i> Warning:</strong><br>Please delete <i>setup.php</i> after finishing the install procedure. It is a major risk to keep that file.</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="container">
            <p class="m-0 text-center text-white"><?php require 'inc/genericFooter.php'; ?></p>
    </div>

    <!-- Plugin JavaScript -->
    <script src="js/setup/jquery.easing.min.js"></script>

    <!-- Custom JavaScript for this theme -->
    <script src="js/setup/scrolling-nav.js"></script>

</body>
</html>


<?php

if ($_SERVER[ 'REQUEST_METHOD' ] === 'POST')
{
    // creating the initial admin-account
    if ( isset( $_POST[ "bt_continue" ] ) )
    {
        $con = connectToDatabase();

        // check if user has already manually created the table: m_users
        $val = mysqli_query( $con, 'select 1 from `m_users`' );
        if($val !== FALSE)
        {
            // table m_users EXISTS - get the data
            $username= filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
            $email= filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
            $password= filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
            $password_confirm= filter_input(INPUT_POST, "password_confirm", FILTER_SANITIZE_STRING);

            // compare passwords
            if($password == $password_confirm) // both passwords do match
            {
                // playing with hash
                $hash = hash('sha256', $password);
                function createSalt() // playing with salt - creates a 3 character sequence
                {
                    $string = md5(uniqid(rand(), true));
                    return substr($string, 0, 3);
                }
                $salt = createSalt();
                $hash = hash('sha256', $salt . $hash);

                $query = "INSERT INTO m_users ( username, password, salt, is_admin, email, admin_note ) VALUES ( '$username' , '$hash' , '$salt', '1', '$email', 'monoto-admin' );";
                mysqli_query($con, $query) or die ("Failed Query of " . $query);
                mysqli_close($con); // close sql connection

                displayNoty('Finished installer. Forwarding to login page.', 'success');
                echo '<script type="text/javascript">window.location="index.php"</script>'; // whyever that works - but header not anymore. must be related to our header rework
            }
            else // Password mismatch
            {
                displayNoty('Password issues: password mismatch.', 'error');
            }
        }
        else // mysql tables dont exist
        {
            displayNoty('Database issues: table m_users does not exist.', 'error');
        }
    }

} // END: POST


?>
