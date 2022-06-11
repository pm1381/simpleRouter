<?php
// routers

$router->delete('/api/removeUnit/(\w+)', '\Bramus\Controller\UnitController@removeUnit');

$router->post('/api/convert/(\d+)', '\Bramus\Controller\UnitController@convert');

$router->post('/api/addUnit/(\w+)', '\Bramus\Controller\UnitController@addUnit');

$router->get('/api/getUnit/(\d+)', '\Bramus\Controller\UnitController@getUnit');

$router->post('/api/updateUnit/(\d+)', '\Bramus\Controller\UnitController@updateUnit');



$router->post('/api/addMeasure/(\w+)', function($measure){
    echo 'add measure' . htmlentities($measure);
});

$router->delete('/api/removeMeasure/(\w+)', function($measure){
    echo 'remove measure' . htmlentities($measure);
});

$router->get('/api/getMeasure/(\d+)', function($id){
    echo 'get measure ' . htmlentities($id);
});
