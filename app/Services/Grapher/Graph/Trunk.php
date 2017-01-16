<?php namespace IXP\Services\Grapher\Graph;

/*
 * Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee.
 * All Rights Reserved.
 *
 * This file is part of IXP Manager.
 *
 * IXP Manager is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation, version v2.0 of the License.
 *
 * IXP Manager is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
 * more details.
 *
 * You should have received a copy of the GNU General Public License v2.0
 * along with IXP Manager.  If not, see:
 *
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

use IXP\Services\Grapher;
use IXP\Services\Grapher\{Graph,Statistics};

use IXP\Exceptions\Services\Grapher\{BadBackendException,CannotHandleRequestException,ConfigurationException,ParameterException};

use Entities\Switcher as SwitchEntity;

use Auth;

/**
 * Grapher -> Switch Graph
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   Grapher
 * @package    IXP\Services\Grapher
 * @copyright  Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class Trunk extends Graph {

    /**
     * Trunk to graph
     * @var string
     */
    private $trunkname = null;


    /**
     * Constructor
     */
    public function __construct( Grapher $grapher, string $n ) {
        parent::__construct( $grapher );
        $this->setTrunkname($n);
    }

    /**
     * Get the trunk name we're meant to graph
     * @return string
     */
    public function trunkname(): string {
        return $this->trunkname;
    }

    /**
     * Set the trunk we should use
     * @param string $trunkname
     * @return \IXP\Services\Grapher Fluid interface
     * @throws \IXP\Exceptions\Services\Grapher\ParameterException
     */
    public function setTrunkname( string $n ): Trunk {
        if( !is_array( config('grapher.backends.mrtg.trunks.'.$n) ) ) {
            throw new ParameterException("Invalid trunk name in constructor");
        }
        
        $this->trunkname = $n;

        return $this;
    }

    /**
     * The name of a graph (e.g. member name, IXP name, etc)
     * @return string
     */
    public function name(): string {
        return config('grapher.backends.mrtg.trunks.'.$this->trunkname().'.title');
    }

    /**
     * A unique identifier for this 'graph type'
     *
     * E.g. for an IXP, it might be ixpxxx where xxx is the database id
     * @return string
     */
    public function identifier(): string {
        return $this->trunkname();
    }

    /**
     * This function controls access to the graph.
     *
     * {@inheritDoc}
     *
     * For (public) vlan aggregate graphs we pretty much allow complete access.
     *
     * @return bool
     */
    public function authorise(): bool {
        // public access to trunk graphs
        return $this->allow();
    }

    /**
     * Generate a URL to get this graphs 'file' of a given type
     *
     * @param array $overrides Allow standard parameters to be overridden (e.g. category)
     * @return string
     */
    public function url( array $overrides = [] ): string {
        return parent::url( $overrides ) . sprintf("&id=%s",
            isset( $overrides['id']   ) ? $overrides['id']   : $this->trunkname()
        );
    }

    /**
     * Get parameters in bulk as associative array
     *
     * Extends base function
     *
     * @return array $params
     */
    public function getParamsAsArray(): array {
        $p = parent::getParamsAsArray();
        $p['id'] = $this->trunkname();
        return $p;
    }


    /**
     * Process user input for the parameter: switch
     *
     * Does a abort(404) if invalid
     *
     * @param int $s The user input value
     * @return int The verified / sanitised / default value
     */
    public static function processParameterTrunkname( string $n ): string {
        if( !is_array( config('grapher.backends.mrtg.trunks.'.$n) ) ) {
            abort(404);
        }
        return $n;
    }
}
