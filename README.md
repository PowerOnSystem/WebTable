# PowerOn System - WebTable

WebTable es una clase para la creación y manipulación de tablas y paginación de resultados

## Instalación vía Composer

Podés instalar WebTable vía
[Composer](https://getcomposer.org)  a través de la consola:

``` bash
$ composer require poweronsystem/webtable: "0.1.0"
```
## Requisitos

* PHP >= 5.4
* poweronsystem/utility: "^0.1.1"

## Uso

### Básico
El esquema de la tabla puede ser completado directamente con arrays con los métodos **header** y **body** de la clase Table.

``` php
//Creamos una instancia
$table = new Table();

//Creamos el encabezado
$table->header([
  'id' => 'ID',
  'name' => ['title' => 'Nombre', 'width' => '30%'],
  'code' => 'Codigo'
]);

//Creamos el cuerpo
$table->body([
  ['id' => 0030, 'name' => 'Carlos', 'code' => '0021518'],
  [
    '_row_param' => [], //Parámetros opcionales de la fila
    'id' => 0031, 
    'name' => 'Sergio', 
    'code' => ['title' => '0021518', 'link' => ['controller' => 'clientes', 'action' => 'view', '21518']
  ],
]);

```
### Avanzado
Se puede ir completando el esquema de la tabla por pasos mediante los métodos **head**, **row**, **cell** y **next**

``` php
$table = new Table();

//Creamos el encabezado
$table
  ->head('id', 'ID')
  ->head('name', [...])
  ...
  
//El cuerpo de la tabla de la misma forma pero utilizando el método row de columna única
$table
  ->row(['id' => 0030, 'name' => 'Carlos', ...])
  ->row(['id' => 0031, 'name' => 'Sergio', ...], ['class' => 'alert', ...])
  ->row(...)
  ...
  
//Incluso podemos crear celda por celda
$table
  ->cell('id', '0030')
  ->cell('name', 'Sergio')
  ->cell('code', ['title' => 'Sergio', 'link' => ['controller' => 'clientes', 'action' => 'view', '21518'])
  
  ->next() //Pasamos a la siguiente fila, etc...
  
  ->cell('id', '0031'),
  ->cell(...)
  ...
```
