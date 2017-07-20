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

use PowerOn\Utility\Arr;
use PowerOn\Utility\Str;
use PowerOn\Helper\Helper;

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
        return '<table ' . Str::htmlserialize( $params + $config ) . '>' . PHP_EOL;
    }
    
    /**
     * Crea el encabezado de una tabla
     * @param array $header Encabezado
     * @return string
     */
    public function header(array $header) {
        $r = '';
        if ( $header ) {
            $r .= '<thead>' . PHP_EOL;
                $r .= '<tr>' . PHP_EOL;
                foreach ($header as $hr) {
                    $title = Arr::trim($hr, 'title');
                    $r .= '<th ' . Str::htmlserialize($hr) . '>';
                        $r .= $title;
                    $r .= '</th>' . PHP_EOL;
                }
                $r .= '</tr>' . PHP_EOL;
            $r .= '</thead>' . PHP_EOL;
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
            $r .= '<tfoot>' . PHP_EOL;
                $r .= '<tr>' . PHP_EOL;
                foreach ($footer as $fr) {
                    $title = Arr::trim($fr, 'title');
                    if ( $title !== NULL )  {
                        $r .= '<td ' . Str::htmlserialize($fr) . '>';
                            $r .= $title;
                        $r .= '</td>' . PHP_EOL;
                    }
                }
                $r .= '</tr>' . PHP_EOL;
            $r .= '</tfoot>' . PHP_EOL;
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
            $r .= '<tbody>' . PHP_EOL;
            foreach ($body as $row_id => $rw) {
                $r .= '<tr ' . ( key_exists($row_id, $rows_params) ? Str::htmlserialize($rows_params[$row_id]) : '' ) . '>' . PHP_EOL;
                    foreach ($rw as $data) {
                        $link = Arr::trim($data, 'link');
                        $title = Arr::trim($data, 'title');
                        if ($title !== NULL) {
                            $r .= '<td ' . Str::htmlserialize($data) . '>';
                                $r .= $link ? $this->html->link($title, $link) : $title;
                            $r .= '</td>' . PHP_EOL;
                        }
                    }
                $r .= '</tr>' . PHP_EOL;
            }
            $r .= '</tbody>' . PHP_EOL;
        }
        
        return $r;
    }
    
    /**
     * Finaliza la tabla
     * @return string
     */
    public function end() {
        return '</table>' . PHP_EOL;
    }
    
    /**
     * Renderiza una tabla solicitada
     * @see \PowerOn\Table\Table
     * @param \PowerOn\Table\Table $table
     * @param $params [Opcional] Parámetros adicionales para la tabla
     */
    public function render(Table $table, array $params = []) {
        $r = $this->start($table->getConfig(), $params);
        $r .= $this->header($table->getHeader());
        $r .= $this->footer($table->getFooter());
        $r .= $this->body($table->getBody(), $table->getRowsParams());
        $r .= $this->end();
        
        return $r;
    }
    
    /**
     * Paginación de resultados
     * @see \PowerOn\Table\Pagination
     * @param \PowerOn\Table\Pagination $pagination
     * @return string
     */
    public function pagination(Pagination $pagination) {
        $list = [];
        $config = [];
        if ( $pagination->showFirstPage() ) {
            $list['first_page'] = $this->html->link('1', ['add' => ['query' => ['page' => 1]]]);
            $config['first_page'] = ['class' => 'first'];
            $prev_pages = $pagination->getPreviousPages();
            if ($prev_pages) {
                $list['previous_pages'] = $this->html->link('...', ['add' => ['query' => ['page' => $prev_pages]]]);
                $config['previous_pages'] = ['class' => 'previous_pages'];
            }
        }
        
        for ( $i = $pagination->getStartPagination(); $i <= $pagination->getEndPagination(); ++$i ) {
            $list[$i] = $this->html->link($i, ['add' => ['query' => ['page' => $i]]]);
            $config[$i] = ['class' => ($i == $pagination->getCurrentPage() ? 'active' : NULL)];
        }
        
        if ( $pagination->showLastPage() ) {
            $number_pages = $pagination->getNumberOfPages();
            $next_pages = $pagination->getNextPages();
            if ($next_pages) {
                $list['next_pages'] = $this->html->link('...', ['add' => ['query' => ['page' => $next_pages]]]);
                $config['next_pages'] = ['class' => 'next_pages'];
            }
            $list['last_page'] = $this->html->link($number_pages, ['add' => ['query' => ['page' => $number_pages]]]);
            $config['last_page'] = ['class' => 'last_page'];
        }
        
        return $this->html->nestedList($list, $pagination->getConfig(), $config);
    }
}
