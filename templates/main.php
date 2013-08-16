<?php
\OCP\Util::addStyle('marble', 'leaflet/leaflet');
\OCP\Util::addScript('marble', 'leaflet/leaflet');

\OCP\Util::addScript('marble', 'KML');

\OCP\Util::addStyle('marble', 'icons/css/marble-icons');

\OCP\Util::addStyle('marble', 'pure/buttons-min');
\OCP\Util::addStyle('marble', 'pure/grids-min');
\OCP\Util::addStyle('marble', 'pure/menus-min');
\OCP\Util::addStyle('marble', 'pure/base-context-min');

\OCP\Util::addScript('marble', 'router');
\OCP\Util::addScript('marble', 'transitional');
\OCP\Util::addScript('marble', 'handlebars');

\OCP\Util::addScript('marble', 'tree.jquery');
\OCP\Util::addStyle('marble', 'jqtree/jqtree');

\OCP\Util::addStyle('marble', 'marble');
\OCP\Util::addScript('marble', 'marble');
?>
<div class="pure-g-r">
    <div class="pure-u-1-4">
        <div id="marble-navigation" class="pure-menu pure-menu-open">
            <ul>
                <li id="marble-nav-bookmarks"><a href="#/bookmarks/"><i class="icon-pushpin"></i>Bookmarks</a></li>
                <li id="marble-nav-routes"><a href="#/routes/"><i class="icon-compass"></i>Routes</a></li>
                <li id="marble-nav-tracks"><a href="#/tracks/"><i class="icon-road"></i>Tracks</a></li>
            </ul>
        </div>
        <div id="marble-context"></div>
    </div>
    <div id="marble-map-container" class="pure-u-3-4">
        <div id="marble-map"></div>
    </div>
</div>

<script id="marble-home-template" type="text/x-handlebars-template">
    <div id="marble-home-indicator" class="pure"><i class="icon-up-dir"></i>Choose a category from the menu</div>
</script>

<script id="marble-route-list-template" type="text/x-handlebars-template">
    <ul id="marble-routes">
    {{#each routes}}
        {{>route}}
    {{/each}}
    </ul>
</script>

<script id="marble-route-template" type="text/x-handlebars-template">
    <li data-timestamp="{{timestamp}}">
        <div style="font-size: 115%; text-align: center;"><strong>{{name}}</strong></div>
        <div style="overflow: hidden;">
            <div style="float: left; width: 50%; text-align: center;">{{distance}} km</div>
            <div style="float: left; width: 50%; text-align: center;">{{duration}} minutes</div>
        </div>
    </li>
</script>

<script id="marble-route-selected-template" type="text/x-handlebars-template">
    <li id="marble-selected-route" data-timestamp="{{timestamp}}" style="">
        <div style="">
            <div style="font-size: 115%; text-align: center;"><strong>{{name}}</strong></div>
            <div style="overflow: hidden;">
                <div style="float: left; width: 50%; text-align: center;">{{distance}} km</div>
                <div style="float: left; width: 50%; text-align: center;">{{duration}} minutes</div>
            </div>
        </div>
        <div style="width: 20px;">
            <button class="pure-button marble-route-delete">Delete</button>
            <button class="pure-button marble-route-edit">Edit</button>
        </div>
    </li>
</script>

<script id="marble-route-edit-template" type="text/x-handlebars-template">
    <li id="marble-edited-route" data-timestamp="{{timestamp}}">
        <form id="marble-edit-form">
            <label for="new_name">New name:</label>
            <input id="new_name" type="text" value="{{name}}" autofocus>
            <button id="submit_button" type="submit" class="pure-button marble-route-submit">Submit</button>
        </form>
    </li>
</script>
    
<script id="marble-bookmarks-template" type="text/x-handlebars-template">
    <div id="marble-bookmarks" class="pure"></div>
</script>
