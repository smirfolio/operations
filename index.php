<?php

// We autoload needed calss (#composer dumpautoload)
require_once __DIR__ . '/vendor/autoload.php';

use request;

$request = new request\RequestHandler();

// if the handlar return FALSE we render the Operation form (/public/index.html)
if(!$request->handler()){
    $request->operationsForm(); 
}