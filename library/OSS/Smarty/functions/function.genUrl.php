<?php
/**
 * OSS Framework
 *
 * This file is part of the "OSS Framework" - a library of tools, utilities and
 * extensions to the Zend Framework V1.x used for PHP application development.
 *
 * Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * All rights reserved.
 *
 * Open Source Solutions Limited is a company registered in Dublin,
 * Ireland with the Companies Registration Office (#438231). We
 * trade as Open Solutions with registered business name (#329120).
 *
 * Contact: Barry O'Donovan - info (at) opensolutions (dot) ie
 *          http://www.opensolutions.ie/
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * It is also available through the world-wide-web at this URL:
 *     http://www.opensolutions.ie/licenses/new-bsd
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@opensolutions.ie so we can send you a copy immediately.
 *
 * @category   OSS
 * @package    OSS_Smarty
 * @subpackage Functions
 * @copyright  Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * @license    http://www.opensolutions.ie/licenses/new-bsd New BSD License
 * @link       http://www.opensolutions.ie/ Open Source Solutions Limited
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @author     The Skilled Team of PHP Developers at Open Solutions <info@opensolutions.ie>
 */

/**
 * @category   OSS
 * @package    OSS_Smarty
 * @subpackage Functions
 * @copyright  Copyright (c) 2007 - 2012, Open Source Solutions Limited, Dublin, Ireland
 * @license    http://www.opensolutions.ie/licenses/new-bsd New BSD License
 */

    /**
     * Function to generate a Zend Controller URL from Smarty templates.
     *
     * The URL is made up of parameters as supplied in the $params associative array.
     * 'module', 'controller' and 'action' are special parameters which indicate the module,
     * controller and action to call. Any other parameters are added as additional name / value
     * pairs.
     *
     * Calls OSS_Utils::genUrl()
     *
     * @category   OSS
     * @package    OSS_Smarty
     * @subpackage Functions
     *
     * @param array $params An array of the parameters to make up the URL
     * @param Smarty $smarty A reference to the Smarty object
     * @return string
     */
    function smarty_function_genUrl( $params, &$smarty )
    {
        if( !isset( $params['controller'] ) )
            $params['controller'] = false;

        if( !isset( $params['action'] ) )
            $params['action'] = false;

        if( !isset( $params['module'] ) || $params['module'] == 'default' )
            $params['module'] = false;

        $p = $params;
        unset( $p['controller'] );
        unset( $p['action'] );
        unset( $p['module'] );

        return OSS_Utils::genUrl( $params['controller'], $params['action'], $params['module'], $p );
    }
