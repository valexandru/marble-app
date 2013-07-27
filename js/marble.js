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
            $.getJSON("bookmarks/json", function(data) {
                callback.call(undefined, data.data);
            });
        }
    }
};

/* Marble State Engine */
Marble.Engine = new Transitional({
    data: {},
    state: "init",
    initialize: function() {

        var engine = this;

        /* Setup the url router */
        Marble.Router = new Router();
        Marble.Router.routes(
            /^#\/$/, function() {
                engine.push("home");
            },
            /^#\/routes\/?$/, function() {
                engine.push("route_list");
            },
            /^#\/routes\/(\d+)\/?$/, function(route_id) {
                engine.push("route_display", route_id);
            }
        );
        Marble.Router.redirects(
            /.*/, "#/"
        );

        /* On document ready */
        $(function() {
            /* Setup the menu */
            $("#marble-nav-bookmarks").click(function(event) {
                event.preventDefault();
                engine.push("bookmarks");
            });
            $("#marble-nav-routes").click(function(event) {
                event.preventDefault();
                engine.push("route_list");
            });
            $("#marble-nav-tracks").click(function(event) {
                event.preventDefault();
                engine.push("track_list");
            });
        });
    },
    rules: {
        "! > home": function(){
            Marble.setSelectedNavEntry("home");
            Marble.Router.navigate("#/", false);

            var html = $("#marble-home-template").html();
            $("#marble-context").html(html);
        },
        "! route_list > route_list": function() {
            Marble.Router.navigate("#/routes/", false);
        },
        "! route_list route_display > route_list route_display": function() {
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
    }
});

/**
 * Setup controllers
 */
Marble.Controller = {
    Home: function() {
        //done
    },
    Bookmarks: function() {
        Marble.setSelectedNavEntry("bookmarks");
        Marble.Data.Bookmarks.get(function(data) {
            var html = $("#marble-bookmarks-template").html();
            $("#marble-context").html(html);

            $("#marble-bookmarks").tree({
                data: data,
                dragAndDrop: true,
                onCanMoveTo: function(moved, target) {
                    if (target.is_folder) {
                        return true;
                    }
                    return false;
                }
            });
        });
    },
    Routes: function(timestamp) {
        //done
    },
    Tracks: function(timestamp) {
        Marble.setSelectedNavEntry("tracks");
        $("#marble-context").html("<p>DISPLAY TRACKS LIST HERE</p>");
    }
};


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
