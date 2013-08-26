var Marble = {};

Marble.Util = {};
Marble.Util.hash = function(str){
    var hash = 0, i, char;
    if (str.length === 0) return hash;
    for (i = 0, l = str.length; i < l; i++) {
        char  = str.charCodeAt(i);
        hash  = ((hash<<5)-hash)+char;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
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
 * Define the data source and its methods
 */
Marble.Data = {};

Marble.Data.Routes = (function() {
    var Cache = {};
    Cache.List = null;
    Cache.KML = {};
    Cache.Details = {};

    return {
        getList: function(callback, noCache) {
            noCache = (noCache !== undefined) && noCache;
            if (Cache.List && noCache === false) {
                callback.call(undefined, Cache.List);
            } else {
                $.getJSON("api/v1/routes", function(jsonData) {
                    var data = jsonData.data;
                    Cache.List = {"routes": data};
                    for (var i=0, len=data.length; i<len; i++) {
                        Cache.Details[data[i].timestamp] = data[i];
                    }
                    callback.call(undefined, Cache.List);
                });
            }
        },
        getDetails: function(timestamp, callback, noCache) {
            noCache = (noCache !== undefined) && noCache;
            if (Cache.List && noCache === false) {
                callback.call(undefined, Cache.Details[timestamp]);
            } else {
                $.getJSON("api/v1/routes", function(jsonData) {
                    var data = jsonData.data;
                    Cache.List = {"routes": data};
                    for (var i=0, len=data.length; i<len; i++) {
                        Cache.Details[data[i].timestamp] = data[i];
                    }
                    callback.call(undefined, Cache.Details[timestamp]);
                });
            }
        },
        getKML: function(timestamp, callback) {
            if (Cache.KML[timestamp]) {
                callback.call(undefined, Cache.KML[timestamp]);
            } else {
                $.get("api/v1/routes/" + timestamp, function(kml) {
                    Cache.KML[timestamp] = kml;
                    callback.call(undefined, kml);
                }, "xml");
            }
        },
        delete: function(timestamp, callback) {
            $.ajax({
                url: "api/v1/routes/delete/" + timestamp,
                type: "DELETE",
                success: function(jsonData) {
                    if (jsonData.status === "success") {
                        Cache.List = null;
                        delete Cache.KML[timestamp];
                        callback.call(undefined);
                    }
                }
            });
        },
        rename: function(timestamp, new_name, callback) {
            $.ajax({
                url: "routes/rename",
                data: {
                    timestamp: timestamp,
                    newName: new_name
                },
                type: "POST",
                success: function(jsonData) {
                    if (jsonData.status === "success") {
                        Cache.List = null;
                        callback.call(undefined);
                    }
                }
            });
        }
    };
})();

Marble.Data.Bookmarks = (function() {
    return {
        get: function(callback) {
            $.getJSON("bookmarks/json", function(jsonData) {
                callback.call(undefined, jsonData.data);
            });
        },
        update: function(newJson, callback) {
            $.ajax({
                url: "bookmarks/update",
                data: {
                    "json": newJson
                },
                type: "POST",
                success: function(jsonData) {
                    if (jsonData.status === "success") {
                        callback.call(undefined);
                    }
                }
            });
        }
    };
})();

Marble.setupMap = function() {
    function scaleMap() {
        $("#marble-map").height($("#content").height());
    }

    scaleMap();

    // on window resize
    $(window).on('resize', function() {
        scaleMap();
    });

    // create a map in the #map div, set the view to a given place and zoom
    Marble.map = L.map('marble-map').setView([45, 10], 2);

    // add an OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(Marble.map);
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
                engine.push("route_list", {noCache: true});
            },
            /^#\/routes\/(\d+)\/?$/, function(timestamp) {
                engine.push("route_display", {"timestamp": timestamp});
            },
            /^#\/bookmarks\/?$/, function() {
                engine.push("bookmarks");
            }
        );
        Marble.Router.redirects(
            /.*/, "#/"
        );

        /* Setup the leaflet icons */
        Marble.Util.icons = [];
        for (i = -12; i <= 12; i++) {
            Marble.Util.icons[i] = L.icon({
                iconUrl: '../../../apps/marble/js/leaflet/images/marker-icon' + i + '.png',
                shadowUrl: '../../../apps/marble/js/leaflet/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });
        }

        /* On document ready */
        $(function() {
            /* Setup the menu */
            $("#marble-nav-bookmarks a").click(function(event) {
                event.preventDefault();
                engine.push("bookmarks");
            });
            $("#marble-nav-routes a").click(function(event) {
                event.preventDefault();
                engine.push("route_list", {noCache: true});
            });
            $("#marble-nav-tracks a").click(function(event) {
                event.preventDefault();
                engine.push("track_list");
            });

            /* Setup the map */
            Marble.setupMap();

            /* Register handlebars partials */
            Handlebars.registerPartial("route", $("#marble-route-template").html());
            Handlebars.registerPartial("route-selected", $("#marble-route-selected-template").html());
        });
    },
    rules: {
        "! > !": function(data, input, from, to) {
            console.log(from + " > " + to);
            console.log(input);
        },
        "! > home": function(){
            Marble.setSelectedNavEntry("home");
            Marble.Router.navigate("#/", false);

            var html = $("#marble-home-template").html();
            $("#marble-context").html(html);
        },
        "! route_list > route_list": function() {
            Marble.Router.navigate("#/routes/", false);
        },
        "! > route_list route_display": function(data, input) {
            var engine = this;

            Marble.setSelectedNavEntry("routes");

            Marble.Data.Routes.getList(function(data) {
                /* load the template, compile it and include it */
                var html = $("#marble-route-list-template").html();
                var template = Handlebars.compile(html);
                $("#marble-context").empty().html(template(data));

                $("#marble-routes > li").each(function(index, routeEl) {
                    var timestamp = $(routeEl).data("timestamp");
                    $(routeEl).click(function() {
                        engine.push("route_display", {"timestamp": timestamp}); });
                });
            }, input.noCache);
        },
        "route_display route_edit > !": function(data) {
            Marble.map.removeLayer(data.routeLayer);
        },
        "route_display > ! route_edit": function(data) {
            var engine = this;

            var timestamp = data.selectedRouteTimestamp;
            var template = Handlebars.compile($("#marble-route-template").html());
            Marble.Data.Routes.getDetails(timestamp, function(route) {
                $("#marble-selected-route").replaceWith(template(route));
                $("#marble-routes li").filter(function() {
                    return $(this).data("timestamp") == timestamp;
                }).click(function() {
                    engine.push("route_display", {"timestamp": timestamp});
                });
            });
        },
        "! > route_display": function(data, input) {
            var engine = this;

            Marble.Router.navigate("#/routes/" + input.timestamp, false);

            data.selectedRouteTimestamp = input.timestamp;

            var template = Handlebars.compile($("#marble-route-selected-template").html());
            Marble.Data.Routes.getDetails(input.timestamp, function(route) {
                $("#marble-routes li").filter(function() {
                    return $(this).data("timestamp") == input.timestamp;
                }).replaceWith(template(route));
                $("#marble-selected-route button.marble-route-delete").click(function() {
                    Marble.Data.Routes.delete(input.timestamp, function() {
                        $("#marble-selected-route").remove();
                        engine.push("route_list", {noCache: false});
                    });
                });
                $("#marble-selected-route button.marble-route-edit").click(function() {
                    engine.push("route_edit", {timestamp: input.timestamp});
                });
            });
        },
        "route_display > route_edit": function(data, input) {
            var engine = this;

            var template = Handlebars.compile($("#marble-route-edit-template").html());
            Marble.Data.Routes.getDetails(input.timestamp, function(route) {
                $("#marble-selected-route").replaceWith(template(route));

                $("#marble-edit-form").submit(function(event) {
                    event.preventDefault();
                    Marble.Data.Routes.rename(input.timestamp, $("#new_name").val(), function() {
                        engine.push("route_display", {timestamp: input.timestamp});
                    });
                });
            });
        },
        "! > route_display route_edit": function(data, input) {
            Marble.Data.Routes.getKML(input.timestamp, function(kml) {
                var route = data.routeLayer = new L.KML(kml);
                Marble.map.fitBounds(route.getBounds());
                Marble.map.addLayer(route);
            });
        },
        "! > bookmarks": function(data) {
            var engine = this;
            Marble.Router.navigate("#/bookmarks/", false);
            Marble.setSelectedNavEntry("bookmarks");

            Marble.map.markers = [];

            Marble.Data.Bookmarks.get(function(treeData) {
                $("#marble-context").empty().html($("#marble-bookmarks-template").html());

                var treeEl = data.treeEl = $("#marble-bookmarks");
                treeEl.tree({
                    data: treeData,
                    dragAndDrop: true,
                    slide: false,
                    onCanMoveTo: function(moved, target) {
                        if (target.is_folder) {
                            return true;
                        }
                        return false;
                    },
                    onCreateLi: function(node, $li) {
                        $li.find("i").addClass("folder-icon" + Marble.Util.hash(node.name) % 13);
                    },
                    onCanSelectNode: function(node) {
                        if (treeEl.tree("getSelectedNode") === node) return false;
                        return true;
                    }
                });

                treeEl.bind("tree.open", function(event) {
                    $(event.node.element).find("i").first().addClass("folder-icon" + Marble.Util.hash(event.node.name) % 13);
                });

                treeEl.bind("tree.close", function(event) {
                    $(event.node.element).find("i").first().addClass("folder-icon" + Marble.Util.hash(event.node.name) % 13);
                });

                treeEl.bind("tree.move", function(event) {
                    event.preventDefault();
                    event.move_info.do_move();
                    $(this).trigger("tree.modified");
                });

                treeEl.bind("tree.modified", function() {
                    var newJson = $(this).tree("toJson");
                    Marble.Data.Bookmarks.update(newJson, function() {
                        console.log("updated");
                    });
                });

                treeEl.bind("tree.select", function(event) {
                    engine.push("bookmarks_selected", {node: event.node});
                });
            });
        },
        "bookmarks_selected > !": function(data) {
            $(data.selectedNode.element).find("button.marble-bookmarks-delete").remove();
            $(data.selectedNode.element).find("button.marble-bookmarks-rename").remove();
        },
        "bookmarks_rename > bookmarks_selected": function(data) {
            $(data.selectedNode.element).find("form").replaceWith(data.selectedNode.name);
        },
        "bookmarks bookmarks_selected bookmarks_rename > bookmarks_selected": function(data, input) {
            var engine = this,
                treeEl = data.treeEl;

            var node = data.selectedNode = input.node;

            for (var i=0, mList = Marble.map.markers, len = mList.length; i<len; i++) {
                Marble.map.removeLayer(mList[i]);
            }
            Marble.map.markers = [];
            displayNode(node);

            var deleteButton = $('<button class="pure-button marble-bookmarks-delete"><i class="icon-pushpin"></i></button>');
            $(input.node.element).find("div:first").append(deleteButton);
            deleteButton.click(function() {
                treeEl.tree("removeNode", node);
                treeEl.trigger("tree.modified");
            });

            var renameButton = $('<button class="pure-button marble-bookmarks-rename"><i class="icon-road"></i></button>');
            $(node.element).find("div:first").append(renameButton);
            renameButton.click(function() {
                console.log('edit');
                engine.push("bookmarks_rename");
            });
        },
        "bookmarks_selected > bookmarks_rename": function(data, input) {
            var engine = this,
                treeEl = data.treeEl,
                node = data.selectedNode,
                form = $('<form id="bookmarks-rename-form"><input type="text" value="' + node.name + '" autofocus></form>');

            $(node.element).find("span:first").html(form);

            $("#bookmarks-rename-form").submit(function() {
                node.name = $(this).find("input").val();
                treeEl.trigger("tree.modified");
                engine.push("bookmarks_selected", {node: node});

                return false;
            });
        }
    }
});

function displayPlacemark(node, colorId) {
    var coords = node.point_coordinates.split(",");
    var marker = L.marker([coords[1], coords[0]], {icon: Marble.Util.icons[colorId]}).addTo(Marble.map);
    Marble.map.markers.push(marker);
}

function displayFolder(node, colorId) {
    for (var i=0, len=node.children.length; i<len; i++) {
        if (node.children[i].is_folder) {
            displayFolder(node.children[i], colorId);
        } else {
            displayPlacemark(node.children[i], colorId);
        }
    }
}

function displayNode(node) {
    var colorId;
    if (node.is_folder) {
        colorId = Marble.Util.hash(node.name) % 13;
        displayFolder(node, colorId);
    } else {
        colorId = node.parent.name ? Marble.Util.hash(node.parent.name) % 13 : 12;
        displayPlacemark(node, colorId);
    }
}
