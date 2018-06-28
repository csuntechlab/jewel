<?php

namespace App\Http\Controllers;

use App\Handlers\HandlerUtilities;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Parses the request type and sends the appropriate
     * Response
     *
     * @param string $data The markup to send
     * @return Response
     */
    protected function sendResponse($data)
    {
        if (Request::get('web-one') === 'true') {
            $data = HandlerUtilities::addWebOneStyle($data);
        }

        if (Request::get('format') === 'html') {
            return $data;
        }

        return response()
            ->json([['data' => $data]])
            ->setCallback('jsonp_received');
    }

    /**
     * Sends the markup as a Web-One accordion with matching markup and script
     * functionality.
     *
     * @param string $data The initial markup for the accordion
     * @return Response
     */
    protected function sendWeboneAccordionResponse($data) {
        // create the JS to make the markup function as an accordion
        $script = "
            (function ($) {
                Drupal.attachBehaviors($('.jewel-accordion'));
            })(jQuery);
        ";

        $data = "
            <div class=\"jewel-accordion\">
                <div id=\"accordion\">
                    {$data}
                </div>
            </div>
            <script type=\"text/javascript\">
                {$script}
            </script>
        ";

        return response()
            ->json([['data' => $data]])
            ->setCallback('jsonp_received');
    }

    /**
     * Finds or creates a directory given the name and
     * returns true or false.
     *
     * @param string $name the directory name to create
     * @return bool
     */
    protected function findOrCreateDirectory($name)
    {
        if (!File::exists(storage_path($name))) {
            return File::makeDirectory(storage_path($name));
        }
        return true;
    }
}
