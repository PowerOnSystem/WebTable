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

/**
 * Inflector
 * @example
 * <code>
 *      $results = [];
 *      $pagination = new Pagination();
 *      $content = array_slice($results, $pagination->getStartResults(), $pagination->getEndResults());
 * </code>
 * @author Lucas Sosa
 * @version 0.2
 */
class Pagination {
    /**
     * Página actual
     * @var integer
     */
    private $_current_page;
    /**
     * Resultados a mostrar por página
     * @var integer
     */
    private $_results_per_page;
    /**
     * Total de resultados
     * @var integer
     */
    private $_max_results;
    /**
     * Configuración del paginador
     * @var array
     */
    private $_config;

    /**
     * Crea una paginación de resultados
     * @param integer $current_page La página solicitada por GET
     * @param integer $max_results Máximos resultados de la tabla
     * @param integer $results_per_page Resultados a mostrar por página
     */
    public function __construct($current_page, $max_results, $results_per_page, array $config = []) {
        $this->_current_page = $current_page;
        $this->_results_per_page = $results_per_page;
        $this->_max_results = $max_results;
        $this->_config = [
            'id' => NULL,
            'class' => NULL,
            'max_show_pages' => 10,
        ] + $config;
    }
    
    /**
     * Devuelve el número de páginas totales de la paginación
     * @return integer
     */
    public function getNumberOfPages() {
        return intval(ceil($this->_max_results / $this->_results_per_page));
    }
    
    /**
     * Devuelve el número de página actual cargada
     * @return integer
     */
    public function getCurrentPage() {
        $number_of_pages = $this->getNumberOfPages();
        return $this->_current_page <= 0 ? 1 : (intval($this->_current_page < $number_of_pages ? $this->_current_page : $number_of_pages));
    }
    
    /**
     * Devuelve el número de resultados mostrados en pantalla
     * @retur integer
     */
    public function getLoadedResults() {
        return $this->getNumberOfPages() == $this->getCurrentPage() ? ($this->_max_results % $this->_results_per_page) : 
            $this->_results_per_page;
    }
    
    /**
     * Devuelve la página siguiente o FALSE si llega al límite
     * @return integer|boolean
     */
    public function getNextPage() {
        $current_page = $this->getCurrentPage();
        return $current_page < $this->getNumberOfPages() ? $current_page + 1 : FALSE;
    }
    
    /**
     * Devuelve la página anterior o FALSE si se encuentra en la primera
     * @return integer|boolean
     */
    public function getPreviousPage() {
        $current_page = $this->getCurrentPage();
        return $current_page > 1 ? $current_page - 1 : FALSE;
    }
    
    /**
     * Devuelve las próximas 10 páginas o FALSE en caso de que sea el límite
     * @return integer|boolean
     */
    public function getNextPages() {
        $number_of_pages = $this->getNumberOfPages();
        $current_page = $this->getCurrentPage();
        return $current_page < $number_of_pages - $this->_config['max_show_pages'] ? $current_page + $this->_config['max_show_pages'] : FALSE;
    }
    
    /**
     * Devuelve las 10 páginas anteriores o FALSE en caso de que existan
     * @return integer|boolean
     */
    public function getPreviousPages() {
        $current_page = $this->getCurrentPage();
        return $current_page >= ($this->_config['max_show_pages'] * 2) ? $current_page - $this->_config['max_show_pages'] : FALSE;
    }
    
    /**
     * Verifica si debe mostrarse la primer página
     * @return boolean
     */
    public function showFirstPage() {
        return $this->getCurrentPage() >= $this->_config['max_show_pages'];
    } 
    
    /**
     * Verifica si debe mostrarse la última página
     * @return boolean
     */
    public function showLastPage() {
        $number_of_pages = $this->getNumberOfPages();
        $current_page = $this->getCurrentPage();
        $start_pagination = intval($current_page / $this->_config['max_show_pages']) * $this->_config['max_show_pages'];
        $end_pagination = $start_pagination ? $start_pagination + $this->_config['max_show_pages'] : $this->_config['max_show_pages'];
        
        return $number_of_pages > $this->_config['max_show_pages'] && $end_pagination < $number_of_pages;
    } 
    
    /**
     * Devuelve el número donde comienza la paginación de resultados
     * @return integer
     */
    public function getStartPagination() {
        $current_page = $this->getCurrentPage();
        $start_pagination = intval($current_page / $this->_config['max_show_pages']) * $this->_config['max_show_pages'];
        return $start_pagination ? $start_pagination : 1;
    }
    
    /**
     * Devuelve el número donde finaliza la paginación de resultados
     * @return integer
     */
    public function getEndPagination() {
        $number_of_pages = $this->getNumberOfPages();
        $current_page = $this->getCurrentPage();
        $start_pagination = intval($current_page / $this->_config['max_show_pages']) * $this->_config['max_show_pages'];
        $end_pagination = $start_pagination ? $start_pagination + $this->_config['max_show_pages'] : $this->_config['max_show_pages'];
        
        return $end_pagination < $number_of_pages ? $end_pagination : $number_of_pages;
    }
    
    /**
     * Devuelve el número donde inician los resultados para una consulta MySql
     * @return type
     */
    public function getStartResults() {
        $current_page = $this->getCurrentPage();
        return $current_page > 1 ? ($current_page - 1) * $this->_results_per_page : 0;
    }
    
    /**
     * Devuelve el número donde finalizan los resultados para una consulta MySql
     * @return integer
     */
    public function getEndResults() {
        return $this->_results_per_page;
    }
    
    /**
     * Devuelve la configuración del paginador
     * @return array
     */
    public function getConfig() {
        return $this->_config;
    }
}
