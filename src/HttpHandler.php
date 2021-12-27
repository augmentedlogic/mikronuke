<?php
/**
 *
 * @copyright 2020 Wolfgang Hauptfleisch <dev@augmentedlogic.com>
 * Apache Licence Version 2.0
 * This file is part of mikronuke
 *
 **/

namespace com\augmentedlogic\mikronuke;

interface HttpHandler
{
    public function handle(Request $request, Response $response);
}

