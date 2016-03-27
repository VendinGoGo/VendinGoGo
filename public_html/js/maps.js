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


/**
 * Knockout View Model meant to be bound to the page to control
 * what is displayed on the sidebar
 * @returns {undefined}
 */
function SidebarViewModel(){
    
    // Good Practice
    var self = this;
    
    
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
        });
        
    };
    
    
    /**
     * Computed string that returns the how to find details of the main vending
     * machine, if they exhist.
     * 
     * If there's no info, it defaults to 'No Info :('
     */
    self.getHowToFind = ko.computed(function(){
        return (self.shouldShowMainView()&& self.mainVendingMachine().Description !== null && self.mainVendingMachine().Description !== "") ? self.mainVendingMachine().Description : 'No Info :(';
    }, this);
    

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


    // Set the style of the map
    var style = getMapStyle();
    map.mapTypes.set('mappp', style);
    map.setMapTypeId("mappp");

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

}


/**
 * Retrieve vending machine locations and display them on the page
 * 
 * @returns {undefined}
 */
function displayVedingLocations(){

    var locations = getVendingLocations();

    for(var i = 0; i < locations.length; i ++){
        
        marker = addLocationToMap(locations[i]);
        
        if(marker !== null && marker !== undefined){
            markerCluster.addMarker(marker, true);
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
    
    
    // Create some html to be written in an infowindow when mousing over a vending machine
    var contentString = "<h4 style='text-align: center'>Out of "+(parseInt(locData.ups)+parseInt(locData.downs))+" votes</h4>";

    var likePercentage = parseInt((parseInt(locData.ups) / (parseInt(locData.ups) + parseInt(locData.downs))) * 100);
    
    contentString +=    '<div class="progress" style="width:200px">\
                            <div class="progress-bar progress-bar-success" style="width: ' + likePercentage + '%">\
                            </div>\
                            <div class="progress-bar progress-bar-danger" style="width: ' + (100 - likePercentage) + '%">\
                            </div>\
                        </div>';

    contentString += "<h5 style='text-align: center'>Aproval of: " + likePercentage + "%</h5>";

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var marker = new google.maps.Marker({
        position: {
            "lat": locData.lat,
            "lng": locData.lon
        },
        map: map,
        title: 'Submitted By: ' + locData.submitted,
        icon: {
            "url": 'img/VendinGoGoicon.svg',
            "scaledSize": new google.maps.Size(80, 80)
        },
        infowindow: infowindow,
        vendingId: locData.id
    });

    google.maps.event.addListener(marker, 'mouseover', function () {
        this.infowindow.open(map, this);
    });

    google.maps.event.addListener(marker, 'mouseout', function () {
        this.infowindow.close(map, this);
    });

    // Add events to fire during a click
    google.maps.event.addListener(marker, 'click', function () {
        map.panTo(this.getPosition());
        console.log(this);
        getVendingMachineInfo(this.vendingId, function (err) {
        }, function (data) {
            viewModel.setMainVendingMachineView(data);
        });
    });
    
    
    viewModel.addSmallLocation(locData);
    
    locationsAdded.push(locData.id);
    
    return marker;

}

function getVendingMachineInfo(id, errorCallBack, successCallBack){
    
    var machineInfo = [
        {
            "lat": 33.4540,
            "lon": -88.7890,
            "id": 132,
            "Description": "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.",

            "updates":[
                {
                    "id":11,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":21,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                }
            ],
            "ups": 30,
            "downs": 6,
            "submitted": "Eli"
        },
        
        {
            "lat": 33.4540,
            "lon": -88.9890,
            "id": 131,
            "Description": "",

            "updates":[
                {
                    "id":10,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":12,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                }
            ],
            "ups": 111,
            "downs": 22,
            "submitted": "Josh"
        },
        
        {
            "lat": 33.540,
            "lon": -88.7890,
            "id": 11,
            "Description": "Lorem ipsnis dis parturient montes, nascetur ridiculus mus. Donec quam felis,assa quis enim. Donec pede justo, fringilla vel, aliquet nec, vu. Phasellus viverra nulla ut metus varius laoreet.",

            "updates":[],
            "ups": 10,
            "downs": 1,
            "submitted": "Kaleb"
        },
        
        {
            "lat": 33.540,
            "lon": -88.8890,
            "id": 31,
            "Description": "Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet.",
            "updates":[
                {
                    "id":1,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":2,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":3,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":4,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":5,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":6,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":7,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":8,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                },{
                    "id":9,
                    "ups": 4,
                    "downs": 2,
                    "raw": "TEST"
                }
            ],
            "ups": 3,
            "downs": 4,
            "submitted": "Hunter"
        }];
    
    var machine;
    
    for(var i = 0; i < machineInfo.length; i ++){
        if(machineInfo[i].id === id){
            machine = machineInfo[i];
        }
    }
    
    successCallBack(machine);
    
}

function getVendingLocations(){
    
    return [

        {
            "lat": 33.4540,
            "lon": -88.7890,
            "id": 132,
            "ups": 30,
            "downs": 6,
            "submitted": "Eli"
        },
        
        {
            "lat": 33.4540,
            "lon": -88.9890,
            "id": 131,
            "ups": 111,
            "downs": 22,
            "submitted": "Josh"
        },
        
        {
            "lat": 33.540,
            "lon": -88.7890,
            "id": 11,
            "ups": 10,
            "downs": 1,
            "submitted": "Kaleb"
        },
        
        {
            "lat": 33.540,
            "lon": -88.8890,
            "id": 31,
            "ups": 3,
            "downs": 4,
            "submitted": "Hunter"
        }

    ];
    
}

function getMapStyle(){
    
    var customMapType = new google.maps.StyledMapType([
        {
            stylers: [
                {hue: '#adebad'},
                {visibility: 'simplified'},
                {gamma: 0.5},
                {weight: 0.5}
            ]
        },
        {
            elementType: 'labels',
            stylers: [{visibility: 'on'}]
        },
        {
            featureType: 'water',
            stylers: [{color: '#1affb2'}]
        }
    ], {
        name: 'Custom Style'
    });
    
    return customMapType;
    
}

function makeHttpRequest(url, errcb, succb){

    var xmlhttp = new XMLHttpRequest();
    
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
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
