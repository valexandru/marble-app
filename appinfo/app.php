<?php

namespace OCA\Marble;

use \OCA\AppFramework\Core\API;

if(\OCP\App::isEnabled('appframework')){

    $api = new API('marble');

    $api->addNavigationEntry(array(

        // the string under which your app will be referenced in owncloud
        'id' => $api->getAppName(),

        // sorting weight for the navigation. The higher the number, the higher
        // will it be listed in the navigation
        'order' => 10,

        // the route that will be shown on startup
        'href' => $api->linkToRoute('marble_index'),

        // the icon that will be shown in the navigation
        'icon' => $api->imagePath('marble_nav.png' ),

        // the title of your application. This will be used in the
        // navigation or on the settings page of your app
        'name' => $api->getTrans()->t('Marble')

    ));

} else {
    $msg = 'Can not enable the Marble app because the App Framework App is disabled';
    \OCP\Util::writeLog('marble', $msg, \OCP\Util::ERROR);
}
