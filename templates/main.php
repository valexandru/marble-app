<?php
\OCP\Util::addStyle('marble', 'leaflet/leaflet');
\OCP\Util::addScript('marble', 'leaflet');

\OCP\Util::addStyle('marble', 'icons/css/marble-icons');

\OCP\Util::addStyle('marble', 'pure/buttons-min');
\OCP\Util::addStyle('marble', 'pure/grids-min');
\OCP\Util::addStyle('marble', 'pure/menus-min');

\OCP\Util::addScript('marble', 'router');

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
        <div id="marble-context">
            <ul>
                <li>ceva</li>
                <li>altceva</li>
            </ul>
        </div>
    </div>
    <div id="marble-map-container" class="pure-u-3-4">
        <div id="marble-map"></div>
    </div>
</div>
