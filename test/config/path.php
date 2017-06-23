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

/**
 * Subdirectorio donde se va a acceder a la web
 */
define('PO_PATH_ROOT', '');
/**
 * Ubicacón física de los archivos de la aplicación
 */
define('PO_PATH_APP', ROOT . DS . 'test');
/**
 * Lenguajes
 */
define('PO_PATH_LANGS', PO_PATH_APP . DS . 'langs');
/**
 * Configuración
 */
define('PO_PATH_CONFIG', PO_PATH_APP . DS . 'config');
/**
 * Contenido de la web
 */
define('PO_PATH_APP_CONTENT', PO_PATH_APP . DS . 'src');
/**
 * Webroot de la web
 */
define('PO_PATH_WEBROOT', PO_PATH_APP . DS . 'webroot');
/**
 * Vistas, Templates y Helpers a utilizar
 */
define('PO_PATH_VIEW', PO_PATH_APP_CONTENT . DS . 'View');
/**
 * Templates de la web
 */
define('PO_PATH_TEMPLATES', PO_PATH_VIEW . DS . 'Template');
/**
 * Helpers de la web
 */
define('PO_PATH_HELPER', PO_PATH_VIEW . DS . 'Helper');
/**
 * Webroot carpeta javascript
 */
define('PO_PATH_JS', (PO_PATH_ROOT ? '/' . PO_PATH_ROOT : '') . '/js');
/**
 * Webroot carpeta de archivos de estilos css
 */
define('PO_PATH_CSS', (PO_PATH_ROOT ? '/' . PO_PATH_ROOT : '') . '/css');
/**
 * Webroot carpeta de imágenes
 */
define('PO_PATH_IMG', (PO_PATH_ROOT ? '/' . PO_PATH_ROOT : '') . '/img');