<?php namespace IXP\Services\Grapher\Backend;

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

use IXP\Contracts\Grapher\Backend as GrapherBackendContract;
use IXP\Services\Grapher\Backend as GrapherBackend;
use IXP\Services\Grapher\Graph;

use IXP\Exceptions\Services\Grapher\CannotHandleRequestException;
use IXP\Exceptions\Utils\Grapher\FileError as FileErrorException;

use Entities\{IXP,PhysicalInterface,Switcher,SwitchPort};
use IXP\Utils\Grapher\Mrtg as MrtgFile;

use View,Log;

/**
 * Grapher Backend -> Mrtg
 *
 * @author     Barry O'Donovan <barry@islandbridgenetworks.ie>
 * @category   Grapher
 * @package    IXP\Services\Grapher
 * @copyright  Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class Mrtg extends GrapherBackend implements GrapherBackendContract {

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function name(): string {
        return 'mrtg';
    }

    /**
     * The dummy backend requires no configuration.
     *
     * {@inheritDoc}
     *
     * @return bool
     */
    public function isConfigurationRequired(): bool {
        return true;
    }

    /**
     * This function indicates whether this graphing engine supports single monolithic text
     *
     * @see IXP\Contracts\Grapher::isMonolithicConfigurationSupported() for an explanation
     * @return bool
     */
    public function isMonolithicConfigurationSupported(): bool {
        return true;
    }

    /**
     * This function indicates whether this graphing engine supports multiple files to a directory
     *
     * @see IXP\Contracts\Grapher::isMonolithicConfigurationSupported() for an explanation
     * @return bool
     */
    public function isMultiFileConfigurationSupported(): bool {
        return false;
    }


    /**
     * Generate the configuration file(s) for this graphing backend
     *
     * {inheritDoc}
     *
     * @param Entities\IXP $ixp The IXP to generate the config for (multi-IXP mode)
     * @param int $config_type The type of configuration to generate
     * @return array
     */
    public function generateConfiguration( IXP $ixp, int $type = self::GENERATED_CONFIG_TYPE_MONOLITHIC ): array
    {
        return [
            View::make( 'services.grapher.mrtg.monolithic', [
                    'ixp'        => $ixp,
                    'data'       => $this->getPeeringPorts( $ixp ),
                    'snmppasswd' => config('grapher.backends.mrtg.snmppasswd'),
                ]
            )->render()
        ];
    }

    /**
     * Utility function to slurp all peering ports from the database and arrange them in
     * arrays for genertaing Mrtg configuration files.
     *
     * The array returned is an array of arrays containing:
     *
     * * array `['pis']` of PhysicalInterface objects indexed by their ID
     * * array `['custs']` of Customer objects indexed by their ID
     * * array `['sws']` of Switcher objects indexed by their ID
     * * array `['infras']` of Infrastructure objects indexed by their ID
     * * array `['custports']` containing an array of PhysicalInterface IDs indexed by customer ID
     * * array `['custlags']` containing an array of PhysicalInterface IDs contained in an array indexed
     *       by VirtualInterface IDs in turn in an array of customer IDs:
     *       `['custlags'][$custid][$viid][]`
     * * array `['swports']` indexed by Switcher ID conataining the PhysicalInterface IDs of peering ports
     * * array `['infraports']` indexed by Infrastructure ID conataining the PhysicalInterface IDs of peering ports
     * * array `['ixpports']` conataining the PhysicalInterface IDs of peering ports
     *
     *
     * @param Entities\IXP $ixp The IXP to generate the config for (multi-IXP mode)
     * @return array
     */
    public function getPeeringPorts( IXP $ixp ): array {
        $data = [];
        $data['ixpports_maxbytes'] = 0;

        // we need to wrap switch ports in physical interfaces for switch aggregates and, as such, we need to use unused physical interface IDs
        $maxPiID = 0;

        foreach( $ixp->getCustomers() as $c ) {

            foreach( $c->getVirtualInterfaces() as $vi ) {

                foreach( $vi->getPhysicalInterfaces() as $pi ) {

                    if( $pi->getId() > $maxPiID ) {
                        $maxPiID = $pi->getId();
                    }

                    // we're not multi-ixp in v4 but we'll catch non-relavent ports here
                    if( $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getIXP()->getId() != $ixp->getId() ) {
                        break 2;
                    }

                    if( !$pi->statusIsConnectedOrQuarantine() || !$pi->getSwitchPort()->getSwitcher()->getActive() ) {
                        continue;
                    }

                    $data['pis'][$pi->getId()] = $pi;

                    if( !isset( $data['custs'][ $c->getId() ] ) ) {
                            $data['custs'][ $c->getId() ] = $c;
                    }

                    if( !isset( $data['sws'][ $pi->getSwitchPort()->getSwitcher()->getId() ] ) ) {
                        $data['sws'][$pi->getSwitchPort()->getSwitcher()->getId() ] = $pi->getSwitchPort()->getSwitcher();
                        $data['swports_maxbytes'][ $pi->getSwitchPort()->getSwitcher()->getId() ] = 0;
                    }

                    if( !isset( $data['infras'][ $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getId() ] ) ) {
                        $data['infras'][ $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getId() ] = $pi->getSwitchPort()->getSwitcher()->getInfrastructure();
                        $data['infraports_maxbytes'][ $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getId() ] = 0;
                    }

                    $data['custports'][$c->getId()][] = $pi->getId();

                    if( count( $vi->getPhysicalInterfaces() ) > 1 ) {
                        $data['custlags'][$c->getId()][$vi->getId()][] = $pi->getId();
                    }

                    $data['swports'][ $pi->getSwitchPort()->getSwitcher()->getId() ][] = $pi->getId();
                    $data['infraports'][ $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getId() ][] = $pi->getId();
                    $data['ixpports'][] = $pi->getId();

                    $maxbytes = $pi->resolveSpeed() * 1000000 / 8; // Mbps * bps / to bytes
                    $data['swports_maxbytes'   ][ $pi->getSwitchPort()->getSwitcher()->getId() ] += $maxbytes;
                    $data['infraports_maxbytes'][ $pi->getSwitchPort()->getSwitcher()->getInfrastructure()->getId() ] += $maxbytes;
                    $data['ixpports_maxbytes'] += $maxbytes;
                }
            }
        }

        // include core switch ports.
        // This is a slight hack as the template requires PhysicalInterfaces so we wrap core SwitchPorts in temporary PhyInts.
        foreach( $ixp->getInfrastructures() as $infra ) {
            foreach( $infra->getSwitchers() as $switch ) {
                foreach( $switch->getPorts() as $sp ) {
                    if( $sp->isTypeCore() ) {
                        // this needs to be wrapped in a physical interface for the template
                        $pi = $this->wrapSwitchPortInPhysicalInterface( $sp, ++$maxPiID );
                        $data['pis'][$pi->getId()] = $pi;
                        $data['swports'][$switch->getId()][] = $pi->getId();

                        if( !isset( $data['swports_maxbytes'][$switch->getId()] ) ) {
                            $data['swports_maxbytes'][$switch->getId()] = 0;
                        }

                        $data['swports_maxbytes'][$switch->getId()] += ( ( $pi->resolveSpeed() > 0 ) ? $pi->resolveSpeed() : 1 ) * 1000000 / 8;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Wrap a switchport in a temporary PhysicalInterface.
     *
     * @see getPeeringPorts() for usage
     * @param Entities\SwitchPort $switchport
     * @param int $id The ID to set in the physical interface
     * @return Entities\PhysicalInterface 
     */
    public function wrapSwitchPortInPhysicalInterface( SwitchPort $sp, int $id ): PhysicalInterface {
        $pi = new PhysicalInterface;
        $pi->setId( $id );
        $pi->setSwitchPort($sp);
        $pi->setSpeed( $sp->getIfHighSpeed() );
        return $pi;
    }

    /**
     * Get a complete list of functionality that this backend supports.
     *
     * {inheritDoc}
     *
     * @return array
     */
    public static function supports(): array {
        return [
            'ixp' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => [ Graph::CATEGORY_BITS => Graph::CATEGORY_BITS,
                                    Graph::CATEGORY_PACKETS => Graph::CATEGORY_PACKETS ],
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'infrastructure' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => [ Graph::CATEGORY_BITS => Graph::CATEGORY_BITS,
                                    Graph::CATEGORY_PACKETS => Graph::CATEGORY_PACKETS ],
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'switcher' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => [ Graph::CATEGORY_BITS => Graph::CATEGORY_BITS,
                                    Graph::CATEGORY_PACKETS => Graph::CATEGORY_PACKETS ],
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'trunk' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => [ Graph::CATEGORY_BITS => Graph::CATEGORY_BITS ],
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'physicalinterface' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => Graph::CATEGORIES,
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'virtualinterface' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => Graph::CATEGORIES,
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
            'customer' => [
                'protocols'   => [ Graph::PROTOCOL_ALL => Graph::PROTOCOL_ALL ],
                'categories'  => Graph::CATEGORIES,
                'periods'     => Graph::PERIODS,
                'types'       => array_except( Graph::TYPES, Graph::TYPE_RRD )
            ],
        ];
    }

    /**
     * Get the data points for a given graph
     *
     * {inheritDoc}
     *
     * @param IXP\Services\Grapher\Graph $graph
     * @return array
     */
    public function data( Graph $graph ): array {
        try {
            $mrtg = new MrtgFile( $this->resolveFilePath( $graph, 'log' ) );
        } catch( FileErrorException $e ) {
            Log::notice("[Grapher] {$this->name()} data(): could not load file {$this->resolveFilePath( $graph, 'log' )}");
            return [];
        }

        return $mrtg->data( $graph );
    }

    /**
     * Get the PNG image for a given graph
     *
     * {inheritDoc}
     *
     * @param IXP\Services\Grapher\Graph $graph
     * @return string
     */
    public function png( Graph $graph ): string {
        if( ( $img = @file_get_contents( $this->resolveFilePath( $graph, 'png' ) ) ) === false ){
            // couldn't load the image so return a placeholder
            Log::notice("[Grapher] {$this->name()} png(): could not load file {$this->resolveFilePath( $graph, 'png' )}");
            return @file_get_contents( public_path() . "/images/image-missing.png" );
        }

        return $img;
    }

    /**
     * For larger IXPs, allow sharding of directories over 16 possible base directories
     * @param int $id The customer entity id
     * @return string shared path -> e.g. 18 -> 18 % 16 = 2 / 00016 -> 2/00016
     */
    private function shardMemberDir( int $id ): string {
        return sprintf( "%x/%05d", $id % 16, $id );
    }

    /**
     * For a given graph, return the path where the appropriate log file
     * will be found.
     *
     * @param IXP\Services\Grapher\Graph $graph
     * @return string
     */
    public function resolveFilePath( Graph $graph, $type ): string {
        $config = config('grapher.backends.mrtg');

        switch( $graph->classType() ) {
            case 'IXP':
                return sprintf( "%s/ixp/ixp%03d-%s%s.%s", $config['logdir'], $graph->ixp()->getId(),
                    $graph->category(), $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'Infrastructure':
                return sprintf( "%s/infras/%03d/ixp%03d-infra%03d-%s%s.%s", $config['logdir'],
                    $graph->infrastructure()->getId(), $graph->infrastructure()->getIXP()->getId(),
                    $graph->infrastructure()->getId(), $graph->category(), $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'Switcher':
                return sprintf( "%s/switches/%03d/switch-aggregate-%05d-%s%s.%s", $config['logdir'],
                    $graph->switch()->getId(), $graph->switch()->getId(),
                    $graph->category(), $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'Trunk':
                return sprintf( "%s/trunks/%s%s.%s", $config['logdir'], $graph->trunkname(),
                    $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'PhysicalInterface':
                return sprintf( "%s/members/%s/ints/%s-%s%s.%s", $config['logdir'],
                    $this->shardMemberDir( $graph->physicalInterface()->getVirtualInterface()->getCustomer()->getId() ),
                    $graph->identifier(), $graph->category(),
                    $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'VirtualInterface':
                return sprintf( "%s/members/%s/lags/%s-%s%s.%s", $config['logdir'],
                    $this->shardMemberDir( $graph->virtualInterface()->getCustomer()->getId() ),
                    $graph->identifier(), $graph->category(),
                    $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;

            case 'Customer':
                return sprintf( "%s/members/%s/%s-%s%s.%s", $config['logdir'],
                    $this->shardMemberDir( $graph->customer()->getId() ),
                    $graph->identifier(), $graph->category(),
                    $type == 'log' ? '' : "-{$graph->period()}", $type );
                break;


            default:
                throw new CannotHandleRequestException("Backend asserted it could process but cannot handle graph of type: {$graph->type()}" );
        }
    }



}
