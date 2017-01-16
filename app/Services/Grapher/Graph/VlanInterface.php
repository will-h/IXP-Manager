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

use Entities\VlanInterface as VlanInterfaceEntity;

use Auth, Log;

/**
 * Grapher -> VlanInterface Graph (l3)
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   Grapher
 * @package    IXP\Services\Grapher
 * @copyright  Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class VlanInterface extends Graph {

    /**
     * VlanInterface to graph
     * @var \Entities\VlanInterface
     */
    private $vlanint = null;


    /**
     * Constructor
     */
    public function __construct( Grapher $grapher, VlanInterfaceEntity $i ) {
        parent::__construct( $grapher );
        $this->vlanint = $i;
    }

    /**
     * Get the vlan we're set to use
     * @return \Entities\Vlan
     */
    public function vlanInterface(): VlanInterfaceEntity {
        return $this->vlanint;
    }

    /**
     * Set the interface we should use
     * @param Entities\VlanInterface $i
     * @return \IXP\Services\Grapher Fluid interface
     * @throws \IXP\Exceptions\Services\Grapher\ParameterException
     */
    public function setVlanInterface( VlanInterfaceEntity $i ): Grapher {
        if( $this->vlanInterface() && $this->vlanInterface()->getId() != $i->getId() ) {
            $this->wipe();
        }

        $this->vlanint = $i;
        return $this;
    }

    /**
     * The name of a graph (e.g. member name, IXP name, etc)
     * @return string
     */
    public function name(): string {
        $n = "Vlan Interface Traffic";

        if( $this->vlanInterface()->getIpv4Enabled() || $this->vlanInterface()->getIpv4Enabled() ) {
            $n .= ' :: ';

            if( $this->vlanInterface()->getIpv4Enabled() ) {
                $n .= $this->vlanInterface()->getIpv4Address()->getAddress() . ' ';
            }

            if( $this->vlanInterface()->getIpv6Enabled() ) {
                $n .= $this->vlanInterface()->getIpv6Address()->getAddress();
            }
        }
        return $n;
    }

    /**
     * A unique identifier for this 'graph type'
     *
     * E.g. for an IXP, it might be ixpxxx where xxx is the database id
     * @return string
     */
    public function identifier(): string {
        return sprintf( "vli%05d", $this->vlanInterface()->getId() );
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

        if( Auth::user()->getCustomer()->getId() == $this->vlanInterface()->getCustomer()->getId() ) {
            return $this->allow();
        }

        Log::notice( sprintf( "[Grapher] [VlanInterface]: user %d::%s tried to access a vlan interface graph "
            . "{$this->vlanInterface()->getId()} which is not theirs", Auth::user()->getId(), Auth::user()->getUsername() )
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
            isset( $overrides['id']   ) ? $overrides['id']   : $this->vlanInterface()->getId()
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
        $p['id'] = $this->vlanInterface()->getId();
        return $p;
    }


    /**
     * Process user input for the parameter: vlanint
     *
     * Does a abort(404) if invalid
     *
     * @param int $pi The user input value
     * @return int The verified / sanitised / default value
     */
    public static function processParameterVlanInterface( int $i ): VlanInterfaceEntity {
        if( !$i || !( $vlanint = d2r( 'VlanInterface' )->find( $i ) ) ) {
            abort(404);
        }
        return $vlanint;
    }

}
