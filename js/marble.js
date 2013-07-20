var Marble = {};

Marble.setSelectedNavEntry = function(page) {
    if (["home", "bookmarks", "routes", "tracks"].indexOf(page) < 0) {
        // if not among the options above...
        return;
    }

    // unselect all menu entries
    $("#marble-navigation li").removeClass("pure-menu-selected");

    // if "home" is wanted, don't select anything
    if (page === "home") {
        return;
    }

    $("#marble-navigation #marble-nav-" + page).addClass("pure-menu-selected");
};

/**
 * Setup controllers
 */
Marble.Controller = {
    Home: function() {
        Marble.setSelectedNavEntry("home");
        $("#marble-context").html("<p>HOME!</p>");
    },
    Bookmarks: function() {
        Marble.setSelectedNavEntry("bookmarks");
        $("#marble-context").html("<p>DISPLAY BOOKMARKS TREE HERE</p>");
    },
    Routes: function(timestamp) {
        Marble.setSelectedNavEntry("routes");
        $("#marble-context").html("<p>DISPLAY ROUTES LIST HERE</p>");
        if (timestamp) {
            $("#marble-context").append("<p>Highlight " + timestamp + " and show on map.</p>");
        }
    },
    Tracks: function(timestamp) {
        Marble.setSelectedNavEntry("tracks");
        $("#marble-context").html("<p>DISPLAY TRACKS LIST HERE</p>");
    }
};

/**
 * Use the Router object
 */
Marble.Router = Router;

Marble.Router.routes(
    /^#\/$/, Marble.Controller.Home,
    /^#\/bookmarks\/?$/, Marble.Controller.Bookmarks,
    /^#\/routes\/?$/, Marble.Controller.Routes,
    /^#\/routes\/(\d+)\/?$/, Marble.Controller.Routes,
    /^#\/tracks\/?$/, Marble.Controller.Tracks
);

Marble.Router.redirects(
    /.*/, "#/"
);

/**
 * Sets the #map div to full height
 */
Marble.setMapSize = function() {
    $("#marble-map").height($("#content").height());
};

/**
 * When document is ready:
 */
$(function() {
    Marble.setMapSize();

    // create a map in the #map div, set the view to a given place and zoom
    var map = L.map('marble-map').setView([45, 10], 5);

    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
});

/**
 * When window is resized:
 */
$(window).on('resize', function() {
    Marble.setMapSize();
});
