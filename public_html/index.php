<!DOCTYPE html>
<!--
____   ____                 .___.__         ________         ________
\   \ /   /____   ____    __| _/|__| ____  /  _____/  ____  /  _____/  ____
 \   Y   // __ \ /    \  / __ | |  |/    \/   \  ___ /  _ \/   \  ___ /  _ \
  \     /\  ___/|   |  \/ /_/ | |  |   |  \    \_\  (  <_> )    \_\  (  <_> )
   \___/  \___  >___|  /\____ | |__|___|  /\______  /\____/ \______  /\____/
              \/     \/      \/         \/        \/               \/
Brought to you by: Kaleb Pace, Josh Hawkins, Hunter Holder, and Eli Davis
https://github.com/VendinGoGo/VendinGoGo
-->
<?php
session_start();

if (!isset($_SESSION['access_token'])) {
    $accountName = "Sign In";
    $profilePic = "/img/blank-avatar.svg";
} else {
    $accountName = $_SESSION['access_token']['screen_name'];
    $profilePic = $_SESSION['tprofile_pic_url'];
}

if (isset($_GET['LOGOUT'])) {
    session_destroy();
    header('Location: index.php');
}
?>
<html style="height:100%">

    <head>
        <title>VendinGoGo</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!--Load Style Sheets-->
        <link href="js/libs/twitter-bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="style/index.css" rel="stylesheet">
        <link href="style/index-darkmode.css" rel="stylesheet">

        <link rel="apple-touch-icon" sizes="57x57" href="ico/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="ico/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="ico/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="ico/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="ico/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="ico/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="ico/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="ico/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="ico/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192" href="ico/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="ico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="ico/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="ico/favicon-16x16.png">
        <link rel="manifest" href="ico/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="ico/ms-icon-144x144.png">
        <meta name="theme-color" content="#21D466">        
        
	<!--Setup Site for external links and social meadia-->
	<meta property="og:title" content="VendinGoGo" />
        <meta property="og:site_name" content="VendinGoGo"/>
        <meta property="og:url" content="https://vendingogo.com/">
        <meta property="og:description" content="One step closer to becoming and omnipresent being."/>
        <meta property="og:type" content="website"/>
        <meta property="og:image" content="https://vendingogo.com/img/FB_VGG_link_banner.png">
	<meta property="og:image:type" content="image/png" />

	<!--Facebook site sepecific tags-->
	<meta property="fb:app_id" content="171375589923747" />
    

    </head>

    <body style="height:100%" >

        <!--Navbar-->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav navbar-center">

                    <li>
                        <img id="brandIcon" src="ico/android-icon-192x192.png"/>
                    </li>

                    <li class="navbar-brand">
                        VendinGoGo
                    </li>
                    <!--<a href="#" class="navbar-brand" role="button" onclick="">
                        VendinGoGo
                    </a>-->

                </ul>
                <ul class="nav navbar-nav navbar-right">

                    <li>
                        <img id="profilePic"  style="margin-right: 5%;"src="<?php echo $profilePic; ?>"/>
                    </li>
                    <?php
                    if (!isset($_SESSION['access_token'])) {
                        echo '<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Options/Sign In<span class="caret"></span></a>

                            <ul class="dropdown-menu">
                                <li><a href="api/twitterLogin.php">Sign In via Twitter</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="https://github.com/VendinGoGo" target="_blank">VendinGoGo on GitHub</a></li>
                                <li><a href="https://twitter.com/VendinGoGo" target="_blank">VendinGoGo on Twitter</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a><label for="mode"><input type="checkbox" id="mode"> Dark Mode </label></a></li>
                            </ul>
                            </li>';
                    } else {
                        echo '<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $accountName . '<span class="caret"></span></a>

                            <ul class="dropdown-menu">
                                <li><a href="index.php?LOGOUT=true">Sign Out</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="add-button mobile-hide" onclick="viewModel.switchToVendingCreationView()"><a href="#">Add Vending Location</a></li>
                                <li class="add-button mobile-hide" onclick="displayUserInfo(\'' . $_SESSION['access_token']['user_id'] . '\')"><a href="#">View Account Info</a></li>
                                <li role="separator" class="divider mobile-hide"></li>
                                <li><a href="https://github.com/VendinGoGo" target="_blank">VendinGoGo on GitHub</a></li>
                                <li><a href="https://twitter.com/VendinGoGo" target="_blank">VendinGoGo on Twitter</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a><label for="mode"><input type="checkbox" id="mode"> Dark Mode </label></a></li>
                            </ul>
                        </li>';
                    }
                    ?>

                </ul>
            </div>
        </nav>

        <!--Sidebar-->
        <div class="container-fluid" style="height:100%">

            <div class="row" style="height:100%">

                <div class="mobile-hide col-sm-4 col-md-3" id="sidebar" style='height:100%; max-height: 100%;overflow:auto;'>

                    <br/>
                    <br/>
                    <br/>

                    <div data-bind="if: shouldShowVendingMachineView">

                        <div data-bind="if: shouldShowMainView">
                            <div class="panel panel-default panel-success">

                                <div class="panel-body">
                                    <div data-bind="with: mainVendingMachine">

                                        <h4>How To Find:</h4>
                                        <p style="word-wrap: break-word;" data-bind="text: $parent.getHowToFind"></p>

                                        <br/>

                                        <div data-bind="if: statuses.length > 0">
                                            <h4>Statuses:</h4>

                                            <div data-bind="foreach: statuses">
                                                <div class="well well-sm" role="alert" style="padding:8px;margin-bottom: 10px">

                                                    <span style="word-wrap: break-word;" data-bind="text: comment"></span>
                                                    <br/>
                                                    <br/>
                                                    <span data-bind="if: username">
                                                        <b>Submitted By:</b> <a data-bind="text: username, click: function(){$parents[1].displyUserInfo(userId)}" href="#"></a>
                                                    </span><br/>
                                                    <span class="label label-default" data-bind="text: date" style="margin-top: 15px;"></span>

                                                </div>

                                            </div>
                                            <br/>
                                        </div>

                                        <?php
                                        if (isset($_SESSION['access_token'])) {
                                            echo '<div>
                                            <h4>Leave Comment:</h4>
                                            <textarea class="form-control" style="resize: vertical; " data-bind="value: $parent.newCommentText, valueUpdate: \'afterkeydown\'"></textarea>
                                            <span data-bind="text: 160 - $parent.newCommentText().length"></span> characters left.<br>
                                            <button class="btn btn-success" data-bind="click: $parent.leaveComment" style="margin-top: 5px;">Post</button>
                                            <br/><br/>
                                        </div>';
                                        } else {
                                            echo '<h4>Sign in to leave a comment.</h4>';
                                        }
                                        ?>

                                        <span class="label label-success">Number of Machines: <span data-bind="html: numOfMachines"></span></span>
                                        <br>
                                        <br>
                                        <b>Submitted By:</b> <a data-bind="text: username, click: function(){$parent.displyUserInfo(userId)}" href="#"></a>
                                        <div>
                                            <h4>Share This Location</h4>
                                            <input onclick="this.focus();this.select()" readonly="readonly" class="form-control" type="text" data-bind="value: $parent.getShareLink">
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div data-bind="if: !shouldShowMainView() && getSmallLocationsToView().length === 0">
                            <center>
                                <h1 style="color:#7a7a7a; margin-top:80%; max-width:300px;">Click a location on the map to view information</h1>
                            </center>
                        </div>

                        <div class='row'>

                            <div data-bind='foreach: getSmallLocationsToView'>
                                <center class='col-md-4 col-lg-3 col-sm-6 col-xs-6' style='padding-left: 5px;padding-right: 5px;'>
                                    <button type="button" class="btn btn-info small-vending-loc" data-bind="click:$parent.viewSmallLocation, event: {mouseover: $parent.animateSmallLocation, mouseout: $parent.stopSmallLocationAnimation}">
                                        View
                                    </button>
                                </center>
                            </div>

                        </div>

                    </div>


                    <div data-bind="if: shouldShowVendingCreationView">
                        <div class="panel panel-default panel-success">
                            <div class="panel-body">

                                <div data-bind="if: creationMarker() ===null">
                                    <div class="alert alert-danger" role="alert" style="font-size:125%">
                                        Right click on the map to set a vending location.
                                    </div>
                                </div>

                                <span class="form-inline"><b>Number of machines at location</b> (max: 9)<br><input data-bind="value: creationNumMachines" class="form-control" type="number" min="1" max="9" placeholder="2"></span>

                                <br/>
                                <br/>

                                <span><b>Description of how to find the machine(s)</b></span>
                                <textarea data-bind="value:creationHowToFind" class="form-control" placeholder="To the left of the front entrance..." style="max-width:100%;"></textarea>

                                <br/>

                                <div data-bind="if: creationMarker() !== null">
                                    <button type="button" data-bind="click: createMachineLocation" class="btn btn-success">Add Machine Location</button>
                                </div>

                                <br/>
                                <br/>
                                <br/>
                                <center>
                                    <button class="btn btn-danger" style="width:100%; padding: 4px 4px 4px 4px;" data-bind="click: switchToVendingMachineView">
                                        Cancel Adding Location
                                    </button>
                                </center>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-8 col-md-9" style="padding-left: 0px;padding-right: 0px; height: 100%">
                    <div id="map"></div>
                </div>

            </div>
        </div>

        <!-- Display Location Modal -->
        <div class="modal fade" id="location-modal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-body" >
                        <button type="button" class="close" data-dismiss="modal">&times;</button>

                        
                        <div data-bind="with: mainVendingMachine">

                            <h4>How To Find:</h4>
                            <p style="word-wrap: break-word;" data-bind="text: $parent.getHowToFind"></p>

                            <br/>

                            <div data-bind="if: statuses.length > 0">
                                <h4>Statuses:</h4>

                                <div data-bind="foreach: statuses">
                                    <div class="well well-sm" role="alert" style="padding:8px;margin-bottom: 10px">

                                        <span style="word-wrap: break-word;" data-bind="text: comment"></span>
                                        <br/>
                                        <br/>
                                        <span data-bind="if: username">
                                            <b>Submitted By:</b> <a data-bind="text: username, click: function(){$parents[1].displyUserInfo(userId)}" href="#"></a>
                                        </span><br/>
                                        <span class="label label-default" data-bind="text: date" style="margin-top: 15px;"></span>

                                    </div>

                                </div>
                                <br/>
                            </div>

                            <?php
                            if (isset($_SESSION['access_token'])) {
                                echo '<div>
                                            <h4>Leave Comment:</h4>
                                            <textarea class="form-control" style="resize: vertical; " data-bind="value: $parent.newCommentText, valueUpdate: \'afterkeydown\'"></textarea>
                                            <span data-bind="text: 160 - $parent.newCommentText().length"></span> characters left.<br>
                                            <button class="btn btn-success" data-bind="click: $parent.leaveComment" style="margin-top: 5px;">Post</button>
                                            <br/><br/>
                                        </div>';
                            } else {
                                echo '<h4>Sign in to leave a comment.</h4>';
                            }
                            ?>

                            <span class="label label-success">Number of Machines: <span data-bind="html: numOfMachines"></span></span>
                            <br>
                            <br>
                            <b>Submitted By:</b> <a data-bind="text: username, click: function(){$parent.displyUserInfo(userId)}" href="#"></a>
                            <div>
                                <h4>Share This Location</h4>
                                <input onclick="this.focus();this.select()" readonly="readonly" class="form-control" type="text" data-bind="value: $parent.getShareLink">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-warning btn-ok" data-dismiss="modal">Okay</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Display Error Modal -->
        <div class="modal fade" id="confirm-action" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="padding:15px 30px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4><span class="modal-title"></span>Something went wrong.</h4>
                    </div>

                    <div class="modal-body" style="padding:50px 20px 2px;">
                        <p><span id="custom-Action-Message"></span>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-warning btn-ok" data-dismiss="modal">Okay</a>
                    </div>
                </div>

            </div>
        </div>

        <!-- Display User Info Modal -->
        <div class="modal fade" id="display-user-model" role="dialog"  >
            <div class="modal-dialog" data-bind="with: specificUserInfo">

                <!-- Modal content-->
                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2><span class="modal-title" data-bind="text: username"></span><br/><small> Signed Up: <span data-bind="text: signup"></span></small></h2>
                    </div>

                    <div class="modal-body">

                        <div class="container-fluid">

                            <div class="row">

                                <div class="col-sm-6">

                                    <h3>Submitted Locations</h3>

                                    <div data-bind="foreach: locations">
                                        <div class="well well-sm">

                                            <h4>How To Find:</h4>

                                            <span data-bind="if: howToFind===''">
                                                <p><b>No Info :(</b></p>
                                            </span>

                                            <span data-bind="if: howToFind!==''">
                                                <p style="word-wrap: break-word;" data-bind="text:howToFind"></p>
                                            </span>

                                            <h5>Machines: <span data-bind="text: numOfMachines"></span></h5>

                                            <button class="btn btn-default" data-bind="click: function(){$parents[1].viewLoc(id);}">View Location</button>

                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">

                                    <h3>Submitted Statuses</h3>

                                    <div data-bind="foreach: statuses">
                                        <div class="well well-sm">

                                            <h4>Status:</h4>

                                            <p style="word-wrap: break-word;" data-bind="text:comment"></p>

                                            <span class="label label-default" data-bind="text: date" style="margin-top: 15px;"></span>

                                            <button class="btn btn-default" data-bind="click: function(){$parents[1].viewLoc(vendId);}">View Location</button>

                                        </div>
                                    </div>

                                </div>


                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <a class="btn btn-danger btn-ok" data-dismiss="modal">Close</a>
                    </div>

                </div>

            </div>
        </div>

        <!--Libraries-->
        <script src="js/libs/knockout/knockout-3.4.0.js"></script>
        <script src='js/libs/markerclusterer_compiled.js'></script>
        <script src="js/libs/jquery/jquery.min.js"></script>
        <script src="js/libs/twitter-bootstrap/js/bootstrap.js"></script>

        <!--our code-->
        <script src="js/src/maps.js"></script>
        <script src="js/src/darkmode.js"></script>

        <!--Make sure this  is loaded after map is so it doesn't try calling init before init is loaded onto the page-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKdjL4G5gfdxhuqxVQTzVNmIUL7bE5-tE&callback=initMap" async defer></script>

    </body>

</html>
