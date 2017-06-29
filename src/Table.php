<?php

/*
 * Copyright (C) PowerOn Sistemas
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace PowerOn\Table;
use PowerOn\Exceptions\DevException;
use function \PowerOn\Application\array_trim;

/**
 * Table
 * @author Lucas Sosa
 * @version 0.1
 */
class Table {
    /**
     * Contenido
     * @var array
     */
    private $_body = [];
    /**
     * Encabezado
     * @var array
     */
    private $_header = [];
    /**
     * Pie
     * @var array
     */
    private $_footer = [];
    /**
     * Parámetros de configuracion de fila
     * @var array
     */
    private $_rows_params = [];
    /**
     * Puntero interno de la fila para el método cell
     * @var integer
     */
    private $_pointer = 0;
    /**
     * Configuración de la tabla
     * @var array
     */
    private $_config = [];
    /**
     * Especifica si se puede ordenar los resultados
     * @var boolean 
     */
    public $sortable = FALSE;
    /**
     * Campo indice de orden
     * @var string
     */
    public $sort_by = NULL;
    /**
     * Modo de orden ascendente o descendente [asc|desc]
     * @var string
     */
    public $sort_mode = NULL;
    
    /**
     * Inicia una nueva tabla, ejemplo:<pre>
     * <code>
     *      $params = [
     *          'title' => 'Nueva Tabla',
     *          'width' => '100%',
     *          'class' => 'mytable',
     *          'id' => 'nwtbl',
     *          'sortable' => TRUE,
     *          'sort_by' => 'id',
     *          'sort_mode' => 'asc'
     *      ]
     * </code>
     * </pre>
     * @param mix $params Los parámetros en Array o el título de la tabla
     * @throws DevException
     */
    public function __construct( $params = NULL ) {
        if ( is_string($params) ) {
            $params = ['title' => $params];
        } else if ( $params && !is_array($params) ) {
            throw new DevException('Formato de configuraci&oacute;n de tabla incorrecto', ['params' => $params]);
        } else if ( !$params ) {
            $params = [];
        }
        
        $this->sortable = array_trim($params, 'sortable');
        $this->sort_by = array_trim($params, 'sort_by');
        $this->sort_mode = array_trim($params, 'sort_mode');
        
        $this->_config = $params + [
            'title' => NULL,
            'width' => 'auto',
            'class' => NULL,
            'id' => NULL,
        ];
    }
    
    /**
     * Inicia el encabezado de la tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header([
     *          'id' => ['title' => 'Codigo', 'width' => '15%'],
     *          'name' => 'Nombre'
     *          'client' => ['title' => 'Nro. de Cliente', 'sort_name' => 'internal_code']
     *      ]);
     * </code>
     * </pre>
     * 
     * @param array $header
     * @return \PowerOn\Utility\Table
     */
    public function header(array $header) {
        foreach ($header as $name => $head) {
            $this->head($name, $head);
        }
        return $this;
    }
    
    /**
     * Agrega un elemento al encabezado, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table
     *          ->head('id', 'Codigo')
     *          ->head('name', 'Nombre')
     *          ->head('client', ['title' => 'Nro. de Cliente', 'sort_name' => 'internal_code'])
     *          ...
     * </code>
     * </pre>
     * @param string $name El nombre de la columna
     * @param string|array $head Los parámetros de la columna o el título
     * @throws DevException
     * @return \PowerOn\Utility\Table
     */
    public function head($name, $head) {
        if ( is_string($head) ) {
            $head = ['title' => $head];
        } else if ( !is_array($head) ) {
            throw new DevException('Formato de configuración de cabezera de tabla incorrecto', ['name' => $name, 'error' => $head]);
        }

        $this->_header[$name] = $head + [
            'title' => NULL,
            'width' => 'auto',
            'class' => NULL,
            'sort_name' => NULL
        ];
        return $this;
    }
    
    /**
     * Inicia el pie de la tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header(...);
     *      $table->footer([
     *          'subtotal' => '$2500',
     *          'total' => ['title' => '$13599', class => 'green']
     *      ]);
     * </code>
     * </pre>
     * @param array $footer
     * @return \PowerOn\Utility\Table
     */
    public function footer( array $footer) {
        foreach ($footer as $name => $foot) {
            $this->foot($name, $foot);
        }
        return $this;
    }
    
    /**
     * Agrega un elemento al pie, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header(...);
     *      $table
     *          ->foot('subtotal', '$2500')
     *          ->foot('total', ['title' => '$13599', class => 'green'])
     *          ...
     * </code>
     * </pre>
     * @param string $name El nombre de la columna
     * @param string|array $foot Los parámetros de la columna o el título
     * @throws DevException
     * @return \PowerOn\Utility\Table
     */
    public function foot($name, $foot) {
        if ( is_string($foot) ) {
            $foot = ['title' => $foot];
        } else if ( $foot === NULL ) {
            $foot = ['title' => NULL];
        } else if ( !is_array($foot) ) {
            throw new DevException('Formato de configuración de pie de tabla incorrecto', ['name' => $name, 'error' => $foot]);
        }

        $this->_footer[$name] = $foot + [
            'title' => '',
            'width' => 'auto',
            'class' => NULL,
        ];
        return $this;
    }
    
    /**
     * Establece el contenido de una tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header([
     *          'id' => 'Codigo',
     *          'name' => 'Nombre'
     *          ...
     *      ]);
     *      $table->body([
     *          ['id' => 0030, 'name' => 'Carlos', ...],
     *          [
     *              '_row_param' => [...], //Parámetros opcionales de la fila
     *              'id' => 0031, 
     *              'name' => 'Sergio', 
     *              'client' => ['title' => '0021518', 'link' => ['controller' => 'clientes', 'action' => 'view', '21518']
     *              ...
     *          ],
     *          ...
     *      ]);
     * </code>
     * </pre>
     * @param array $body
     * @throws DevException
     */
    public function body(array $body) {
        foreach ($body as $row_id => $row) {
            $row_param = array_trim($row, '_row_param');
            $this->row($row, $row_param ? $row_param : [], $row_id);
        }
    }
    
    /**
     * Agrega una fila completa al contenido de la tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header([
     *          'id' => 'Codigo',
     *          'name' => 'Nombre'
     *          ...
     *      ]);
     *      $table->row([
     *          'id' => 0030,
     *          'name' => 'Carlos',
     *          'client' => ['title' => '0021518', 'link' => ['controller' => 'clientes', 'action' => 'view', '21518'],
     *          ...
     *      ]);
     * </code>
     * </pre>
     * @param array $row El campo solicitado
     * @param array $row_params [Opcional] Parámetros de configuración de la fila, ejemplo:<pre>
     * <code>
     *      $row_params = [
     *          'class' => 'warning'
     *          'id' => 'row75'
     *          ...
     *      ]
     * </code>
     * </pre>
     * @param integer $row_id [Opcional] El puntero de la columna, por defecto es autoincremental
     * @return \PowerOn\Utility\Table
     */
    public function row(array $row, array $row_params = [], $row_id = NULL) {
        $this->_rows_params[$row_id !== NULL ? $row_id : $this->_pointer] = $row_params;
        foreach ($row as $column_name => $data) {
            $this->cell($column_name, $data, $row_id !== NULL ? $row_id : $this->_pointer);
        }
        $this->next();
        return $this;
    }
    
    /**
     * Establce los parámetros de una fila específica
     * @param array $row_params
     * @param integer $row_id [Opcional] Si no se especifica utiliza el puntero interno.
     * @return \PowerOn\Utility\Table
     */
    public function params(array $row_params, $row_id = NULL) {
        $this->_rows_params[$row_id ? $row_id : $this->_pointer] = $row_params;
        return $this;
    }
    
    /**
     * Agrega una celda a una fila del contenido de la tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header([
     *          'id' => 'Codigo',
     *          'name' => 'Nombre'
     *          ...
     *      ]);
     *      $table
     *          ->cell('id', 0030)
     *          ->cell('name', 'Carlos')
     *          ->cell('client', ['title' => '0021518', 'link' => ['controller' => 'clientes', 'action' => 'view', '21518']]
     *          ...
     * </code>
     * </pre>
     * @param string $column_name El nombre de la columna
     * @param string|array $data Array con parámetros de la celda o el título
     * @param integer $row_id [Opcional] El identificador de la fila, si no se especifica se utiliza
     * el puntero interno de la tabla, si desea agregar una nueva fila utilize la función <b>Table::next()</b>
     * @throws DevException
     * @return \PowerOn\Utility\Table
     */
    public function cell($column_name, $data, $row_id = NULL) {
        if ( is_string($data) || is_numeric($data) ) {
            $data = ['title' => $data];
        } else if ($data === NULL) {
            $data = ['title' => NULL];
        } else if ( !is_array($data) ) {
            throw new DevException('Formato de configuración del contenido de la tabla incorrecto', 
                    ['row_id' => $row_id, 'error' => $data]);
        }
        
        $this->_body[$row_id][$column_name] = $data + [
            'title' => '',
            'class' => NULL,
            'link' => NULL
        ];
        
        return $this;
    }
    
    /**
     * Modifica el puntero interno de la fila de la tabla, ejemplo:<pre>
     * <code>
     *      $table = new Table();
     *      $table->header(...);
     *      $table
     *          ->cell(...)
     *          ->cell(...)
     *          ...
     * 
     *          ->next(..)
     * 
     *          ->cell(...)
     *          ->cell(...);
     * </code>
     * </pre>
     * @return \PowerOn\Utility\Table
     */
    public function next() {
        ++ $this->_pointer;
        return $this;
    }
    
    /**
     * Resetea el contenido de la tabla
     */
    public function reset() {
        $this->_pointer = 0;
        $this->_header = [];
        $this->_body = [];
        $this->_config = [];
        $this->_rows_params = [];
    }
    
    /**
     * Devuelve la configuración de la tabla
     * @return array
     */
    public function getConfig() {
        return $this->_config;
    }
    
    /**
     * Devuelve la cabecera de la tabla
     * @return array
     */
    public function getHeader() {
        return $this->_header ? $this->_header : [];
    }
    
    /**
     * Devuelve el contenido de la tabla
     * @return array
     */
    public function getBody() {
        return $this->_body ? $this->_body : [];
    }
    
    /**
     * Devuelve el contenido del pie de la tabla
     * @return array
     */
    public function getFooter() {
        return $this->_footer ? $this->_footer : [];
    }
    
    /**
     * Devuelve los parámetros de configuración de filas
     * @return array
     */
    public function getRowsParams() {
        return $this->_rows_params ? $this->_rows_params : [];
    }
}
