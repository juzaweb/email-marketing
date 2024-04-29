<?php

namespace Juzaweb\EmailMarketing\Http\Controllers;

use Juzaweb\CMS\Http\Controllers\BackendController;

class EmailMarketingController extends BackendController
{
    public function index()
    {
        //

        return view(
            'jem::index',
            [
                'title' => 'Title Page',
            ]
        );
    }
}
