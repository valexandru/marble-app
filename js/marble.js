var Marble = {};

/**
 * Define the data source and its methods
 */
Marble.Data = {
    Routes: {
        getAll: function(callback) {
            $.getJSON("api/v1/routes", function(data) {
                callback.call(undefined, {
                    "routes": data.data
                });
            });
        },
        get: function(timestamp, callback) {
            $.getJSON("api/v1/routes/" + timestamp, function(data) {
                callback.call(undefined, data);
            });
        },
        delete: function(timestamp, callback) {
            $.ajax({
                url: "api/v1/routes/delete/" + timestamp,
                type: "DELETE",
                success: callback
            });
        }
    },
    Bookmarks: {
        get: function(callback) {
            $.getJSON("bookmarks", function(data) {
                callback.call(undefined, data.data);
            });
        }
    }
};


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
        Marble.Data.Bookmarks.get(function(data) {
            var html = $("#marble-bookmarks-template").html();
            $("#marble-context").html(html);

            $("#marble-bookmarks").tree({
                "data": data
            });
        });
    },
    Routes: function(timestamp) {
        Marble.setSelectedNavEntry("routes");
        Marble.Data.Routes.getAll(function(data) {
            /* load the template, compile it and include it */
            var html = $("#marble-routes-template").html();
            var template = Handlebars.compile(html);
            $("#marble-context").html(template(data));

            /* add on-click events for the delete buttons on each route */
            $("#marble-routes > li").each(function() {
                var route = this;
                var timestamp = $(route).data("timestamp");
                $(route).find("button.marble-route-delete").click(function() {
                    Marble.Data.Routes.delete(timestamp, function() {
                        $(route).remove();
                    });
                });
            });
        });
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
