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

namespace PowerOn\View\Helper;
use function \PowerOn\Application\html_serialize;
use function \PowerOn\Application\array_trim;

/**
 * Ayudante de Tablas
 * @author Lucas Sosa
 * @version 0.1
 */
class TableHelper extends Helper {
    
    /**
     * Inicializa una tabla
     * @param array $config Configuración de la tabla
     * @param array $params Parámetros opcionales
     * @return string
     */
    public function start(array $config, array $params = []) {
        return '<table ' . html_serialize( $params + $config ) . '>';
    }
    
    /**
     * Crea el encabezado de una tabla
     * @param array $header Encabezado
     * @return string
     */
    public function header(array $header) {
        $r = '';
        if ( $header ) {
            $r .= '<thead>';
                $r .= '<tr>';
                foreach ($header as $hr) {
                    $title = array_trim($hr, 'title');
                    $r .= '<th ' . html_serialize($hr) . '>';
                        $r .= $title;
                    $r .= '</th>';
                }
                $r .= '</tr>';
            $r .= '</thead>';
        }
        
        return $r;
    }
    
    /**
     * Crea el pie de la tabla
     * @param array $footer El pie
     * @return string
     */
    public function footer(array $footer) {
        $r = '';
        if ($footer) {
            $r .= '<tfoot>';
                $r .= '<tr>';
                foreach ($footer as $fr) {
                    $title = array_trim($fr, 'title');
                    if ( $title !== NULL )  {
                        $r .= '<td ' . html_serialize($fr) . '>';
                            $r .= $title;
                        $r .= '</td>';
                    }
                }
                $r .= '</tr>';
            $r .= '</tfoot>';
        }
        
        return $r;
    }
    
    /**
     * Crea el contenido de la tabla
     * @param array $body El cuerpo de la tabla
     * @param array $rows_params Parámetros de cada fila
     * @return string
     */
    public function body(array $body, array $rows_params = []) {
        $r = '';
        if ($body) {
            $r .= '<tbody>';
            foreach ($body as $row_id => $rw) {
                $r .= '<tr ' . ( key_exists($row_id, $rows_params) ? html_serialize($rows_params[$row_id]) : '' ) . '>';
                    foreach ($rw as $data) {
                        $link = array_trim($data, 'link');
                        $title = array_trim($data, 'title');
                        if ($title !== NULL) {
                            $r .= '<td ' . html_serialize($data) . '>';
                                $r .= $link ? $this->html->link($title, $link) : $title;
                            $r .= '</td>';
                        }
                    }
                $r .= '</tr>';
            }
            $r .= '</tbody>';
        }
        
        return $r;
    }
    
    /**
     * Finaliza la tabla
     * @return string
     */
    public function end() {
        return '</table>';
    }
    
    /**
     * Renderiza una tabla solicitada
     * @see \PowerOn\Utility\Table
     * @param \PowerOn\Utility\Table $table
     * @param $params [Opcional] Parámetros adicionales para la tabla
     */
    public function table(\PowerOn\Utility\Table $table, array $params = []) {
        $r = $this->start($table->getConfig(), $params);
        $r .= $this->header($table->getHeader());
        $r .= $this->footer($table->getFooter());
        $r .= $this->body($table->getBody(), $table->getRowsParams());
        $r .= $this->end();
        
        return $r;
    }
}
