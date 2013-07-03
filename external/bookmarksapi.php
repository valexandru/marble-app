<?php

namespace OCA\Marble\BookmarksAPI;

use \OCA\AppFramework\Controller\Controller;


class BookmarksAPI extends Controller {

    public function __construct($api, $request) {
        parent::__construct($api, $request);
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function get() {
        //
    }

    /**
     * @IsAdminExemption
     * @IsSubAdminExemption
     * @Ajax
     * @CSRFExemption
     * @API
     */
    public function update() {
        //
    }

}
