<?php
define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

require ROOT . DS . 'vendor' . DS . 'autoload.php';

//Creo la tabla con parámetros iniciales en border 1
$table = new \PowerOn\Table\Table(['border' => 1]);

//Establezco el encabezado
$table->header([
    'id' => 'ID',
    'name' => 'Nombre',
    'client' => ['title' => 'Cliente', 'sort_name' => 'client_name'],
    'money' => 'Dinero en cuenta'
]);

//Agrego todo el contenido
$table->body([
    ['id' => '0001', 'name' => 'Juancito', 'client' => 'Jauncho y Asociados S.A.', 'money' => '300'],
    ['id' => '0002', 'name' => 'Yanina', 'client' => 'Argumentaria S.R.L.', 'money' => '230'],
    ['id' => '0023', 'name' => 'Pedrito', 'client' => 'Guardamotores Pedrito', 'money' => ['style' => 'color:red', 'title' => '-30']]
]);

//Creo un pie de tabla con el subtotal
$table->footer([
    'total_title' => ['title' => 'Total de dinero', 'align' => 'right', 'colspan' => 3],
    'total' => ['title' => '500', 'style' => 'color:green']
]);

//Inicio el manager de helpers
$helper_manager = new PowerOn\Helper\HelperManager();

//Cargo en el manager el helper de la tabla
$helper_manager->loadHelper('table', 'PowerOn\Table\\');

//Obtengo el helper por medio del manager
/* @var $renderizer PowerOn\Table\TableHelper */
$renderizer = $helper_manager->getHelper('table');

//Renderizo la tabla completa
echo $renderizer->render($table);