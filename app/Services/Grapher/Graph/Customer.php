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

use Entities\Customer as CustomerEntity;

use Auth, Log;

/**
 * Grapher -> Customer Graph (LAGs)
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   Grapher
 * @package    IXP\Services\Grapher
 * @copyright  Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class Customer extends Graph {

    /**
     * Customer to graph
     * @var \Entities\Customer
     */
    private $cust = null;


    /**
     * Constructor
     */
    public function __construct( Grapher $grapher, CustomerEntity $c ) {
        parent::__construct( $grapher );
        $this->cust = $c;
    }

    /**
     * Get the customer we're set to use
     * @return \Entities\Vlan
     */
    public function customer(): CustomerEntity {
        return $this->cust;
    }

    /**
     * Set the customer we should use
     * @param Entities\customer $i=c
     * @return \IXP\Services\Grapher Fluid interface
     * @throws \IXP\Exceptions\Services\Grapher\ParameterException
     */
    public function setCustomer( CustomerEntity $c ): Grapher {
        if( $this->customer() && $this->customer()->getId() != $c->getId() ) {
            $this->wipe();
        }

        $this->cust = $c;
        return $this;
    }

    /**
     * The name of a graph (e.g. member name, IXP name, etc)
     * @return string
     */
    public function name(): string {
        return $this->customer()->getName();
    }

    /**
     * A unique identifier for this 'graph type'
     *
     * E.g. for an IXP, it might be ixpxxx where xxx is the database id
     * @return string
     */
    public function identifier(): string {
        return sprintf( "aggregate-%05d", $this->customer()->getId() );
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
        if( !Auth::check() ) {
            return $this->deny();
        }

        if( Auth::user()->isSuperUser() ) {
            return $this->allow();
        }

        if( Auth::user()->getCustomer()->getId() == $this->customer()->getId() ) {
            return $this->allow();
        }

        Log::notice( sprintf( "[Grapher] [Customer]: user %d::%s tried to access a customer aggregate graph "
            . "{$this->customer()->getId()} which is not theirs", Auth::user()->getId(), Auth::user()->getUsername() )
        );
        return $this->deny();
    }

    /**
     * Generate a URL to get this graphs 'file' of a given type
     *
     * @param array $overrides Allow standard parameters to be overridden (e.g. category)
     * @return string
     */
    public function url( array $overrides = [] ): string {
        return parent::url( $overrides ) . sprintf("&id=%d",
            isset( $overrides['id']   ) ? $overrides['id']   : $this->customer()->getId()
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
        $p['id'] = $this->customer()->getId();
        return $p;
    }


    /**
     * Process user input for the parameter: cust
     *
     * Does a abort(404) if invalid
     *
     * @param int $i The user input value
     * @return int The verified / sanitised / default value
     */
    public static function processParameterCustomer( int $i ): CustomerEntity {
        // if we're not an admin, default to the currently logged in customer
        if( !$i && Auth::check() && !Auth::user()->isSuperUser() && !Auth::user()->getCustomer()->isTypeAssociate() ) {
            return Auth::user()->getCustomer();
        }

        if( !$i || !( $cust = d2r( 'Customer' )->find( $i ) ) ) {
            abort(404);
        }
        return $cust;
    }

}
