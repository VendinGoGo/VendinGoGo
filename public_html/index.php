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

	if(!isset($_SESSION['access_token'])) {
		$accountName = "Sign In";
	} else {
		$accountName = $_SESSION['access_token']['screen_name'];
	}
	
	if(isset($_GET['LOGOUT'])){
		session_destroy();
		header('Location: ../../index.php');
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
        
        <link rel="apple-touch-icon" sizes="57x57" href="ico/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="ico/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="ico/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="ico/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="ico/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="ico/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="ico/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="ico/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="ico/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="ico/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="ico/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="ico/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="ico/favicon-16x16.png">
        <link rel="manifest" href="ico/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="ico/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

    </head>

    <body style="height:100%">

<!--Navbar-->
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <ul class="nav navbar-nav navbar-center">
                    <div class="navbar-brand">
                        VendinGoGo
                    </div>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <?php
                        if(isset($_SESSION['access_token'])){
                            echo '<li class="add-button" onclick="viewModel.switchToVendingCreationView()"><a href="#">Add Vending Location</a></li>';
                        }
                    ?>

                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $accountName; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php
                                if(!isset($_SESSION['access_token'])){
                                                echo '<li><a href="api/twitterLogin.php">Sign In via Twitter</a></li>';
                                } else {
                                    echo '<li><a href="index.php?LOGOUT=true">Sign Out</a></li>';
                                }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>

<!--Sidebar-->
        <div class="container-fluid" style="height:100%">

            <div class="row" style="height:100%">

                <div class="col-md-3" id="sidebar" style='height:100%; max-height: 100%;overflow:auto;' >

                    <br/><br/><br/>

                    <div data-bind="if: shouldShowVendingMachineView">

                        <div data-bind="if: shouldShowMainView">
                            <div class="panel panel-default panel-success">

                                <div class="panel-body">
                                    <div data-bind="with: mainVendingMachine">

                                        <!--                                       
                                        <div class="progress" style="width:100%">
                                        
                                            <div class="progress-bar progress-bar-success" data-bind="style:{ width: (ups/(ups+downs))*100+'%'}" ></div>
                                            <div class="progress-bar progress-bar-danger" data-bind="style:{ width: (downs/(ups+downs))*100+'%'}" ></div>
                                        
                                            </div>
                                        -->

                                        <h4>How To Find:</h4>
                                        <p data-bind="text: $parent.getHowToFind"></p>

                                        <br/>
                                        
                                        <div data-bind="if: statuses.length > 0">
                                            <h4>Statuses:</h4>
                                            
                                            <div data-bind="foreach: statuses">
                                                <div class="well well-sm" role="alert" style="padding:8px;margin-bottom: 10px">
                                                    
                                                    <div class="row">
                                                        <div class="col-xs-8 col-sm-9">
                                                            <span  data-bind="text: comment"></span>
                                                            <br/><br/>
                                                            <span class="label label-default" data-bind="text: date" style="margin-top: 15px;" ></span>
                                                        </div>
                                                        <div class="col-xs-4 col-sm-3">
                                                            <center>
                                                                <span class="glyphicon glyphicon-arrow-up"  aria-hidden="true"></span>
                                                                <br/>
                                                                
                                                                180
                                                                
                                                                <br/>
                                                                <span class="glyphicon glyphicon-arrow-down"  aria-hidden="true"></span>

                                                            </center>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <br/>
                                        </div>

                                        <?php
                                        if (isset($_SESSION['access_token'])) {
                                            echo '<div>
                                            <h4>Leave Comment:</h4>
                                            <textarea class="form-control" style="resize: vertical; " data-bind="value: $parent.newCommentText"></textarea>
                                            <button class="btn btn-success" data-bind="click: $parent.leaveComment" style="margin-top: 5px;">Post</button>
                                            <br/><br/>
                                        </div>';
                                        } else {
                                            echo '<h4>Log in to leave a comment!</h4>';
                                        }
                                        ?>
                                        <!--                                        <div data-bind="foreach: updates">
                                                                                </div>-->


                                        <span class="label label-success">Number of Machines: <span data-bind="html: numOfMachines"></span></span>
                                        <br><br>
                                        <div>
                                            <h4>Share This Location</h4>
                                            <input class="form-control" type="text" data-bind="value: $parent.getShareLink">
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div data-bind="if: !shouldShowMainView() && getSmallLocationsToView().length === 0">
                            <center><h1 style="color:#7a7a7a; margin-top:80%; max-width:300px;">No Vending Locations Found Near You</h1></center>
                        </div>

                        <div class='row'>

                            <div data-bind='foreach: getSmallLocationsToView'>
                                <center class='col-md-4 col-lg-3 col-sm-6 col-xs-6' style='padding-left: 5px;padding-right: 5px;'>
                                    <button type="button" class="btn btn-info small-vending-loc"  
                                            data-bind="click:$parent.viewSmallLocation, event: {mouseover: $parent.animateSmallLocation, mouseout: $parent.stopSmallLocationAnimation}">
                                        View
                                    </button>
                                </center>
                            </div>

                        </div>

                    </div>


                    <div data-bind="if: shouldShowVendingCreationView">

                        <div  data-bind="if: creationMarker() ===null">
                            <div class="alert alert-danger" role="alert" style="font-size:125%">
                                Right click on the map to set a vending location.
                            </div>
                        </div>

                        <span class="form-inline"><b>Number of machines at location</b> (max: 9)<br><input data-bind="value: creationNumMachines" class="form-control" type="number" min="1" max="9" placeholder="2"></span>

                        <br/><br/>

                        <span><b>Description of how to find the machine(s)</b></span>
                        <textarea data-bind="value:creationHowToFind" class="form-control" placeholder="To the left of the front entrance..." style="max-width:100%;"></textarea>

                        <br/>

                        <div  data-bind="if: creationMarker() !== null">
                            <button type="button" data-bind="click: createMachineLocation" class="btn btn-success">Add Machine Location</button>
                        </div>
                        
                        <br/><br/><br/>
                        <center>
                            <button class="btn btn-danger btn-lg" style="width:80%;" data-bind="click: switchToVendingMachineView">
                                Cancel Adding Location <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 
                            </button>
                        </center>

                    </div>


                </div>

                <div class="col-md-9" style="padding-left: 0px;padding-right: 0px; height: 100%">
                    <div id="map"></div>
                </div>

            </div>

        </div>


        <!-- Modal -->
        <div class="modal fade" id="confirm-action" role="dialog">
            <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="padding:15px 30px;">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4><span class="modal-title"></span>Something went wrong!</h4>
                    </div>
                    
                    <div class="modal-body" style="padding:50px 20px 2px;">
                        <p><span id="custom-Action-Message"></span>
                        </p>
                    </div>
                    
                    <div class="modal-footer">
                        <a class="btn btn-warning btn-ok"data-dismiss="modal">Okay</a>
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

        <!--Make sure this  is loaded after map is so it doesn't try calling init before init is loaded onto the page-->
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKdjL4G5gfdxhuqxVQTzVNmIUL7bE5-tE&callback=initMap" async defer></script>

    </body>

</html>
