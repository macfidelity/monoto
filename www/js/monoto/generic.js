/** @namespace */
 var generic = {};


/**
 * @name disableRightContextMenu
 * @summary Disable right click context menu
 * @description Disabling the right click context menu  (used for the entire monoto userinterface) to simplify the user experience
 * @memberof generic
 */
function disableRightContextMenu()
{
    console.debug("disableRightContextMenu ::: Start");

    $(document).bind("contextmenu",function(e)
    {
        return false;
    });

    console.debug("disableRightContextMenu ::: End");
}


/**
 * @name calculatePasswordStrength
 * @summary Calculates strength of a password
 * @description calculates password strength from the content of input #newPassword (4 categories: too short, weak, medium, ok)
 * @memberof generic
 */
function calculatePasswordStrength()
{
    console.debug("calculatePasswordStrength ::: Start");

    console.log("calculatePasswordStrength ::: Validating password strength");

    currentPasswordStrength = "";

    var strongRegex = new RegExp("^(?=.{10,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
    var mediumRegex = new RegExp("^(?=.{9,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
    var enoughRegex = new RegExp("(?=.{8,}).*", "g");

    curPasswordString = $("#newPassword").val();

    if (false === enoughRegex.test(curPasswordString)) // < 8 chars
    {
        currentPasswordStrength = "... not valid yet (too short)";
        $("#passstrength").html("too short");
    }
    else if (strongRegex.test(curPasswordString))
    {
        currentPasswordStrength = "ok";
        $("#passstrength").className = "ok";
        $("#passstrength").html("Strong");
     }
     else if (mediumRegex.test(curPasswordString))
     {
        currentPasswordStrength = "medium";
        $("#passstrength").className = "alert";
        $("#passstrength").html("Medium");
     }
     else
     {
        currentPasswordStrength = "weak";
        $("#passstrength").className = "error";
        $("#passstrength").html("Weak");
     }

    console.log("calculatePasswordStrength ::: Current password strength is: " + currentPasswordStrength );

    console.debug("calculatePasswordStrength ::: End");
}


/**
 * @name validatePasswordChangeInput
 * @summary Compares content of password & password-confirm fields
 * @description compare content of password-change fields, shows if they differ and enables or disables the related button to proceed
 * @memberof generic
 */
function validatePasswordChangeInput()
{
    console.debug("validatePasswordChangeInput ::: Start");

    if ($("#newPassword").val() === $("#newPasswordConfirm").val())
    {
        // password & passwordConfirm do match

        // now check if min length is reached
        if($("#newPassword").val().length > 7)
        {
                console.log("Passwords do match and min length is reached");
                // enable button
                $("#bt_continue").prop("disabled",false);
                // update status icon
                $("#passwordDiff").text("");
                $("#passwordDiff").append("<i class='fas fa-smile'></i>");
        }
        else // password min length is not reached
        {
            console.warn("Passwords do match but min length is not reached");
            // disable button
            $("#bt_continue").prop("disabled",true);
            // update status icon
            $("#passwordDiff").text("");
            $("#passwordDiff").append("<i class='far fa-frown'></i>");
        }
    }
    else // passwords dont match
    {
        console.warn("Passwords do not match");

        // disable button
        $("#bt_continue").prop("disabled",true);

        // update status icon
        $("#passwordDiff").text("");
        $("#passwordDiff").append("<i class='far fa-frown'></i>");
    }

    console.debug("validatePasswordChangeInput ::: End");
}


/**
 * @name initNotyDefaults
 * @summary init the default noty notification
 * @description initialize the defaults for a simple noty notification
 * @memberof generic
 */
function initNotyDefaults()
{
    console.debug("initNotyDefaults ::: Start");

    $.noty.defaults = {
        layout: "topRight",
        theme: "defaultTheme",
        type: "alert",
        text: "",
        dismissQueue: true, // If you want to use queue feature set this true
        template: "<div class='noty_message'><span class='noty_text'></span><div class='noty_close'></div></div>",
        animation:
        {
            open: {
                height: "toggle"
            },
            close: {
                height: "toggle"
            },
            easing: "swing",
            speed: 500 // opening & closing animation speed
        },
        timeout: 5000, // delay for closing event. Set false for sticky notifications
        force: false, // adds notification to the beginning of queue when set to true
        modal: false,
        closeWith: ["click"], // ['click', 'button', 'hover']
        callback: {
            //onShow() {},
            //afterShow() {},
            //onClose() {},
            //afterClose() {}
        },
        buttons: false // an array of buttons
    };

    console.debug("initNotyDefaults ::: End");
}


/**
 * @name showLogoutDialog
 * @summary Show a logout dialog
 * @description Displays a noty logout confirm dialog triggered via the navigation
 * @requires logout.php
 * @memberof generic
 */
function showLogoutDialog()
{
    console.debug("showLogoutDialog ::: Start");

    var x = noty({
        text: "Do you really want to logout?",
        type: "confirm",
        dismissQueue: false,
        layout: "topRight",
        theme: "defaultTheme",
        buttons: [
        {
            addClass: "btn btn-primary", text: "Ok", onClick: function($noty)
            {
                $noty.close();
                window.location.href = "logout.php";
            }
        },
        {
            addClass: "btn btn-danger", text: "Cancel", onClick: function($noty)
            {
                $noty.close();
            }
        }
        ]
    });

    console.debug("showLogoutDialog ::: End");
}


/**
 * @name createNoty
 * @summary Creates a simple noty notification
 * @description Displays a simple noty notification. Supports several noty types
 * @memberof generic
 * @param {string} text - The notification text
 * @param {string} type - The notification type
 */
function createNoty(text, type)
{
    //console.log("createNoty ::: Display a noty notification");

    // NOTY-TYPES:
    //
    // alert
    // information
    // error
    // warning
    // notification
    // success
    var n = noty({text: text, type: type});
}


/**
 * @name onGenericPageReady
 * @summary init all main pages
 * @description executed on each pageReady via inc/coreIncludesJS.php
 * @memberof generic
 */
function onGenericPageReady()
{
    console.debug("onGenericPageReady ::: Start");

    // disable right click context menu
    disableRightContextMenu();

    // initialize the defaults for the Noty notifications
    initNotyDefaults();

    console.debug("onGenericPageReady ::: End");
}
