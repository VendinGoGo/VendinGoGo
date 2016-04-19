/* 
____   ____                 .___.__         ________         ________        
\   \ /   /____   ____    __| _/|__| ____  /  _____/  ____  /  _____/  ____  
 \   Y   // __ \ /    \  / __ | |  |/    \/   \  ___ /  _ \/   \  ___ /  _ \ 
  \     /\  ___/|   |  \/ /_/ | |  |   |  \    \_\  (  <_> )    \_\  (  <_> )
   \___/  \___  >___|  /\____ | |__|___|  /\______  /\____/ \______  /\____/ 
              \/     \/      \/         \/        \/               \/        

 * JS By Eli C Davis <3
 * github.com/EliCDavis
 */


/* Eli is going to hate me for this*/
/* This is for the darkmode */
document.onclick = $('#mode').change(function() {
    if($(this).prop('checked'))
    {
        map.setMapTypeId("mapDark");

        $('body').addClass('dark-mode');
        $('nav').addClass('dark-mode');
        $('.btn').addClass('dark-mode');
        $('.navbar-brand').addClass('dark-mode');
        $('.dropdown-toggle').addClass('dark-mode');
        $('.label').addClass('dark-mode');
        $('.label-success').addClass('dark-mode');
        $('#profilePic').addClass('dark-mode');
        $('#brandIcon').addClass('dark-mode');
        $('#sidebar').addClass('dark-mode');
        $('.panel').addClass('dark-mode');
        $('.panel-body').addClass('dark-mode');

        console.log("Dark mode map")
    }
    else
    {
        map.setMapTypeId("mapLight");

        $('body').removeClass('dark-mode');
        $('nav').removeClass('dark-mode');
        $('.btn').removeClass('dark-mode');
        $('.navbar-brand').removeClass('dark-mode');
        $('.dropdown-toggle').removeClass('dark-mode');
        $('.label').removeClass('dark-mode');
        $('.label-success').removeClass('dark-mode');
        $('#profilePic').removeClass('dark-mode');
        $('#brandIcon').removeClass('dark-mode');
        $('.panel').removeClass('dark-mode');
        $('.panel-body').removeClass('dark-mode');
        $('#sidebar').removeClass('dark-mode');
        
        console.log("Light mode map")
    }

});


/**
 * Knockout View Model meant to be bound to the page to control
 * what is displayed on the sidebar
 * @returns {undefined}
 */
function SidebarViewModel(){
    
    // Good Practice
    var self = this;
    
    
    /**
     * The current view we're currentely displaying in our sidebar.
     * Currentely the two views available are "machine" and "creation"
     */
    self.mainView = ko.observable("machine");
    
    
    /**
     * Computed boolean that determines whether or not we should be showing
     * DOM elements relating to browswing through vending machines
     */
    self.shouldShowVendingMachineView = ko.computed(function(){
        return self.mainView() === "machine";
    }, this);
    
    self.shouldShowVendingMachineView.subscribe(function(){
        $('.panel').addClass('dark-mode');
        $('.panel-body').addClass('dark-mode');
    }, this);
    
    
    /**
     * Computed boolean that determines whether or not we should be showing
     * DOM elements relating to creating a new vending location
     */
    self.shouldShowVendingCreationView = ko.computed(function(){
        return self.mainView() === "creation";
    }, this);
    
    
    /**
     * Changes the type of view we're working with to the creation of a
     * vending machine
     * 
     * @returns {undefined}
     */
    self.switchToVendingCreationView = function () {
        self.mainView("creation");
        self.clearCreateLocationMarker();
    };


    self.switchToVendingMachineView = function () {
        self.mainView("machine");
       self.clearCreateLocationMarker();
    };
    
    
    /**
     * The main vending machine to be displayed with all it's 
     * details and updates
     */
    self.mainVendingMachine = ko.observable(null);
    
    
    /**
     * Computed boolean that determines whether or not to display the html
     * presenting teh main vending machine
     */
    self.shouldShowMainView = ko.computed(function(){
        return (self.mainVendingMachine() !== null && self.mainVendingMachine() !== undefined);
    }, this);
    
    
    /**
     * Array of location objects loaded on the page
     */
    self.smallLocationsLoaded = ko.observableArray([]);
    
    
    /**
     * Computed array to determine which locations should ve displayed 
     * based on the main view and potentially other rules
     */
    self.getSmallLocationsToView = ko.computed(function(){
        
        return [];
        
        if(self.mainVendingMachine() === null || self.mainVendingMachine() === undefined){
            return self.smallLocationsLoaded();
        }
        
        var toView = [];
        
        for(var i = 0; i < self.smallLocationsLoaded().length; i ++){
            if(self.smallLocationsLoaded()[i].id !== self.mainVendingMachine().id){
                toView.push(self.smallLocationsLoaded()[i]);
            }
        }
        
        return toView;
        
    }, this);
    
    
    /**
     * Makes a marker that was passed in bounce up
     * and down on the map
     * 
     * @param {type} loc
     * @returns {undefined}
     */
    self.animateSmallLocation = function(loc){
        makeMarkerBounce(loc.id);
    };
    
    
    /**
     * Removes all animations that a marker passed in 
     * might have
     * 
     * @param {type} loc
     * @returns {undefined}
     */
    self.stopSmallLocationAnimation = function(loc){
        clearMarkerAnimation(loc.id);
    };
    
    
    /**
     * Adds a location to be viewed compressed on the sidebar
     * 
     * @param {VendingLocationJSON} loc
     * @returns {undefined}
     */
    self.addSmallLocation = function(loc){
        
        if(loc === null || loc === ""){
            console.log("The Location Your Trying To View Is Null/Undefined!");
            return;
        }
        
        self.smallLocationsLoaded.push(loc);
        
    };
    
    
    /**
     * Loads in all the machines information based on the id of the 
     * location and displays it on the main view.
     * 
     * @param {VendingLocationJSON} loc
     * @returns {undefined}
     */
    self.viewSmallLocation = function(loc){
        
        getVendingMachineInfo(loc.id, function(){}, function(data){
            self.setMainVendingMachineView(data);
            clearMarkerAnimation(loc.id);
            console.log(loc);
            map.panTo( {
                "lat": parseFloat(loc.lat),
                "lng": parseFloat(loc.lng)
            });

        });
        
    };
    
    
    /**
     * Computed string that returns the how to find details of the main vending
     * machine, if they exhist.
     * 
     * If there's no info, it defaults to 'No Info :('
     */
    self.getHowToFind = ko.computed(function(){
        return (self.shouldShowMainView() && self.mainVendingMachine().howToFind !== null && self.mainVendingMachine().howToFind !== "") ? self.mainVendingMachine().howToFind : 'No Info :(';
    }, this);
    

    /**
     * A computed variable that create's a string representing a url
     * that will pull up a certain vending machine
     */
    self.getShareLink = ko.computed(function(){
        
        if(self.mainVendingMachine() === null || self.mainVendingMachine() === undefined){
            return;
        }
        
        if(self.mainVendingMachine().id === null || self.mainVendingMachine().id === undefined){
            return;
        }
        
        return window.location.origin+"/?id="+self.mainVendingMachine().id;
        
    },this);


    /**
     * Set's the machine passed to be the main view if appropriate
     * 
     * @param {VendingLocationJSON} machine
     * @returns {undefined}
     */
    self.setMainVendingMachineView = function(machine){
        
        if(machine === null || machine === undefined){
            console.log("The Machine Your Trying To View Is Null/Undefined!");
            return;
        }
        
        self.mainVendingMachine(machine);
        
    };
    
    
    
    self.rightClickEvent = function(latLng){
        
        if(self.shouldShowVendingCreationView() === true){
            self.setCreationMarkerLocation(latLng.lat(), latLng.lng());
        }
        
    };
    
    self.creationMarker = ko.observable(null);
    
    self.creationNumMachines = ko.observable(1);
    
    self.creationHowToFind = ko.observable("");
    
    self.setCreationMarkerLocation = function(lat, lng){
        
        if(self.creationMarker() !== null){
            self.creationMarker().setPosition({lat: lat, lng: lng});
        } else {
            var marker = new google.maps.Marker({
                position: {
                    lat: lat,
                    lng: lng
                },
                map: map,
                title: 'Click to zoom',
                icon: {
                    "url": 'img/VGG-marker-selected.svg',
                    "scaledSize": new google.maps.Size(40, 40)
                },
            });

            marker.setAnimation(google.maps.Animation.BOUNCE);

            self.creationMarker(marker);
        }
        
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    self.createMachineLocation = function(){
        
        if(self.creationMarker() === null){
            return;
        }
        
        var loc = self.creationMarker().getPosition();
        
        //TRY NOT EVEN ROUNDINGs
        var url = "api/addVendingMachine.php?lat="+loc.lat()+"&lng="+loc.lng()+"&m="+self.creationNumMachines()+"&w="+self.creationHowToFind();
        console.log(url);
        makeHttpRequest(url, 
        function(){
            displayMessage("Something went wrong trying to create the vending machine!");
        }, function(data){
            
            if(data.result === "success"){
                
                self.switchToVendingMachineView();
                self.clearCreateLocationMarker();
                setMainVendingMachine(data.id);
                addVendingMachineToMap(data.id);
                
            } else if( data.result === "failure"){
               
               displayMessage("Failure creating the vending machine!");
               
            }
            
            

        });
        
    };
    
    self.clearCreateLocationMarker = function(){
        if(self.creationMarker() !== null){
            self.creationMarker().setMap(null);
        }
        self.creationMarker(null);
    };


    self.newCommentText = ko.observable("");
    
    self.leaveComment = function(){
        
        if(self.newCommentText() === null || self.newCommentText() === undefined || self.newCommentText() === ""){
            return;
        }
        
        if(self.mainVendingMachine() === null || self.mainVendingMachine().id === undefined){
            return;
        }
        
        var request = "api/addVendingStatus.php?id=" + self.mainVendingMachine().id + "&comment=" + self.newCommentText();
        
        console.log(request);
        
        makeHttpRequest(request, function () {
            displayMessage("Unable to post the comment");
        }, 
                function (data) {
                    if (data.result === "success") {
                        setMainVendingMachine(self.mainVendingMachine().id);
                        self.newCommentText("");
                    } else if (data.result === "failure") {
                        console.log("Unable to add the status");
                        displayMessage("Unable to create the status!")
                    }
                });
    };
    
    self.specificUserInfo = ko.observable(null);

}


// Create instance of view model
var viewModel = new SidebarViewModel();


// bind our viewmodel to the side bar
ko.applyBindings(viewModel, document.getElementById("sidebar"));


// Map handle
var map;


// Marker cluster for containing all our markers
var markerCluster;


// list of ids of the locations that have been added
var locationsAdded = [];


/**
 * Callback function for Googlemaps when it's done loading. (So don't change it's name)
 * Creates a map instance as well as setting up styles and
 * 
 * @returns {undefined}
 */
function initMap() {


    // Create a map object and specify the DOM element for display.
    map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 33.4540 , lng: -88.7890},
        scrollwheel: true,
        zoom: 12
    });


    map.addListener('rightclick', function(data) {
        viewModel.rightClickEvent(data.latLng);
    });

    // Set the style of the map
    map.mapTypes.set('mapLight', getMapStyle());
    map.mapTypes.set('mapDark', getMapStyleDark());
    map.setMapTypeId("mapLight");

    // Create a marker cluster for preventing a plethura of icons in the view
    markerCluster = new MarkerClusterer(map, []);

    displayVedingLocations();

    // Try HTML5 geolocation.
    if (navigator.geolocation) {

        navigator.geolocation.getCurrentPosition(function(position) {

            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            map.setCenter(pos);

        });

    }
    
    tryLoadingMachineFromURL();

}


/**
 * Retrieve vending machine locations and display them on the page
 * 
 * @returns {undefined}
 */
function displayVedingLocations() {

    getVendingLocations(function (locations) {

        for (var i = 0; i < locations.length; i++) {

            marker = addLocationToMap(locations[i]);

            if (marker !== null && marker !== undefined) {
                markerCluster.addMarker(marker, true);
            } 

        }
    });



}


function makeMarkerBounce(markerId){
    
    for(var i = 0; i < markerCluster.getMarkers().length; i ++){
        
        if(markerCluster.getMarkers()[i].vendingId === markerId){
            markerCluster.getMarkers()[i].setAnimation(google.maps.Animation.BOUNCE);
        }
        
    }
    
}

function clearMarkerAnimation(markerId){
    
    for(var i = 0; i < markerCluster.getMarkers().length; i ++){
        
        if(markerCluster.getMarkers()[i].vendingId === markerId){
            markerCluster.getMarkers()[i].setAnimation(null);
        }
        
    }
    
}


/**
 * Adds a vending location to the map to be displayed as well as on the sidebar
 * if appropriate
 * 
 * @param {type} locData
 * @returns {undefined|google.maps.Marker}
 */
function addLocationToMap(locData){
    
    
    // Catch it if it's null
    if(locData === null || locData === undefined){
        console.log("Attempting to add null marker!");
        return;
    }
    
    
    // Before we do anything make sure we haven't seen this marker before
    for(var i = 0; i < locationsAdded.length; i ++){
        if(locData.id === locationsAdded[i]){
            return null;
        }
    }
    
    
//    // Create some html to be written in an infowindow when mousing over a vending machine
//    var contentString = "<h4 style='text-align: center'>Out of "+(parseInt(locData.ups)+parseInt(locData.downs))+" votes</h4>";
//
//    var likePercentage = parseInt((parseInt(locData.ups) / (parseInt(locData.ups) + parseInt(locData.downs))) * 100);
//    
//    contentString +=    '<div class="progress" style="width:200px">\
//                            <div class="progress-bar progress-bar-success" style="width: ' + likePercentage + '%">\
//                            </div>\
//                            <div class="progress-bar progress-bar-danger" style="width: ' + (100 - likePercentage) + '%">\
//                            </div>\
//                        </div>';
//
//    contentString += "<h5 style='text-align: center'>Aproval of: " + likePercentage + "%</h5>";
//
//    var infowindow = new google.maps.InfoWindow({
//        content: contentString
//    });

    var marker = new google.maps.Marker({
        position: {
            "lat": parseFloat(locData.lat),
            "lng": parseFloat(locData.lng)
        },
        map: map,
        title: 'Submitted On: ' + locData.createdOn,
        icon: {
            "url": 'img/VGG-marker.svg',
            "scaledSize": new google.maps.Size(40, 40)
        },
//        infowindow: infowindow,
        vendingId: locData.id
    });

//    google.maps.event.addListener(marker, 'mouseover', function () {
//        this.infowindow.open(map, this);
//    });
//
//    google.maps.event.addListener(marker, 'mouseout', function () {
//        this.infowindow.close(map, this);
//    });

    // Add events to fire during a click
    google.maps.event.addListener(marker, 'click', function () {
        map.panTo(this.getPosition());
        getVendingMachineInfo(this.vendingId, function (err) {
        }, function (data) {
            viewModel.setMainVendingMachineView(data);
        });
    });
    
    
    viewModel.addSmallLocation(locData);
    
    locationsAdded.push(locData.id);
        
    return marker;

}


function setMainVendingMachine(id){
    getVendingMachineInfo(id, function (err) {
        displayMessage("Unable to retrieve the requesting information for the vending machine");
    }, function (data) {
        viewModel.setMainVendingMachineView(data);
    });
}


function getVendingMachineInfo(id, errorCallBack, successCallBack){
    
    
    makeHttpRequest("api/getVendingInfo.php?id="+id, errorCallBack, successCallBack);
    
    
}


function getVendingLocations(cb){
    
    makeHttpRequest("api/getVendingLocations.php",
    function(){
        
        displayMessage("Unable to retrieve vending locations");
        
    }, function(data){
        cb(data);
    });
    
}


function getMapStyle(){
    
    var mapOptions =
        [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},
        {"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#ebefeb"}]},
         {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#cdecbc"}]}, 
        {"featureType":"road","elementType":"geometry","stylers":[{"saturation":-100},{"lightness":45}]},
        {"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"}]},
        {"featureType":"water","elementType":"geometry","stylers":[{"color":"#7ed3f5"},{"visibility":"on"}]}];
    
    var customMapType = new google.maps.StyledMapType(mapOptions);
    
    return customMapType;
    
}

function getMapStyleDark(){

    var mapOptions =
        [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#1d1d1d"}]},
         {"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#0c2434"}]},
         {"featureType":"poi","elementType":"geometry","stylers":[{"color":"#4E3A24"}]}, 
         {"featureType":"transit","elementType":"geometry","stylers":[{"color":"#888681"}]}, 
         {"featureType":"road","elementType":"geometry","stylers":[{"saturation":-100},{"lightness":5},{"color":"#7b7b7b"}]},
         {"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"simplified"},{"saturation":-100},{"lightness":10},{"color":"#535353"}]},
         {"featureType":"water","elementType":"geometry","stylers":[{"color":"#3c6d80"},{"visibility":"on"}]}];

    var customMapType = new google.maps.StyledMapType(mapOptions);

    return customMapType;

}


/**
 * Creates an asynchronus http request for loading contents onto the page
 * to make it more dynamic.
 * 
 * @param {string} url Location to hit to pull information from
 * @param {function} errcb Function called when the request fails
 * @param {function} succb Function called when the request suceeds with the data recieved
 * @returns {undefined}
 */
function makeHttpRequest(url, errcb, succb){

    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
            console.log(xmlhttp.responseText);
            var postData = JSON.parse(xmlhttp.responseText);
            succb(postData);
        } else {
    
            if(xmlhttp.readyState === 4 && xmlhttp.status !== 200){
    
                if(errcb !== null){
                    errcb();
                }
    
            }
    
        }
    };
    
    xmlhttp.open("GET",
            url, true);
    
    xmlhttp.send();

}


function addVendingMachineToMap(id){
    makeHttpRequest("api/getVendingInfo.php?id="+id, function(){
        displayMessage("Failure to add the vending machine to the map");
    }, function(data){
        markerCluster.addMarker(addLocationToMap(data));
    });
 }


/**
 * If an id is a variable in the url then we will try loading a vending
 * location that uses that id to show to the user
 * 
 * @returns {undefined}
 */
function tryLoadingMachineFromURL(){
    
    var id = getParameterByName("id");
    
    console.log(id);
    
    if(id !== undefined && id !== null){
        setMainVendingMachine(parseInt(id));
    }
    
}


/**
 * Attempts to grab a user based on the userid passed in.
 * If grabbing the information is succesful, then a modal pops up with the users information.
 * 
 * @param {type} userId
 * @returns {undefined}
 */
function displayUserInfo(userId){
    
    if(userId === null || userId === undefined || parseInt(userId) === 0){
        return;
    }
    
    makeHttpRequest("api/getUserInfo.php?id="+userId, function(){
        displayMessage("Unable to grab user information!");
    }, function(data){
        
        console.log(data);
        viewModel.specificUserInfo(data);
        $("#display-user-model").modal();
        ko.applyBindings(viewModel, document.getElementById("display-user-model"));

    });
    
}


//http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
function getParameterByName(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)", "i"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}


/// Conjures modal that asks user whether they want to perform or cancel an action.
function displayMessage(actionMessage) {
    
    $("#confirm-action").modal();
    $("#custom-Action-Message").text(actionMessage);

}
