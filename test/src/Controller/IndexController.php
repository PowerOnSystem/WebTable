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

namespace App\Controller;

use PowerOn\Controller\Controller;
use PowerOn\Table\Table;
/**
 * IndexController
 * @author Lucas Sosa
 * @version 0.1
 */
class IndexController extends Controller {

    public function index() {
        $table = new Table(['border' => 1]);
        
        $table->head('id', 'Identificador')
            ->head('name', 'Nombre')
            ->head('years', 'Edad')
        
            ->foot('title', ['title' => 'Total', 'align' => 'right', 'colspan' => '2'])
            ->foot('total', ['title' => '2 personas'])
            
            ->row(['id' => 15, 'name' => 'Carlos', 'years' => '25'])
            ->row(['id' => 24, 'name' => 'Sergio', 'years' => '47'])
        ;
        
        $this->view->set('clients_table', $table);
    }
}
