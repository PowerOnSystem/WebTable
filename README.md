# PowerOn System - WebTable

WebTable es una clase para la creación y manipulación de tablas y paginación de resultados

## Instalación vía Composer

Podés instalar WebTable vía
[Composer](https://getcomposer.org).  a travéz de la consola:

``` bash
$ composer require poweronsystem/webtable: "0.1.0"
```
## Requisitos

PHP >= 5.4
poweronsystem/webframework: "^0.1.0"

## Uso

### Básico
El esquema de la tabla puede ser completado directamente con arrays con los métodos **header** y **body** de la clase Table.
```
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

```
$table = new Table();

//Creamos el encabezado
$table
  ->head('id', 'ID')
  ->head('name', [...])
  ...
  
//Creamos el cuerpo de la misma forma
$table
  ->body(...)
```
