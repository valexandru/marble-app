<?php
\OCP\Util::addStyle('marble', 'leaflet/leaflet');
\OCP\Util::addScript('marble', 'leaflet');

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
<script id="marble-routes-template" type="text/x-handlebars-template">
    <ul id="marble-routes">
    {{#each routes}}
        <li data-timestamp="{{timestamp}}">
            <p>{{name}}</p>
            <p>{{distance}} km</p>
            <p>{{duration}} minutes</p>
            <button class="pure-button marble-route-delete">Delete</button>
        </li>
    {{/each}}
    </ul>
</script>

<script id="marble-bookmarks-template" type="text/x-handlebars-template">
    <div id="marble-bookmarks" class="pure"></div>
</script>
