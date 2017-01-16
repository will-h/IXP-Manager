<?php

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

use IXP\Services\Grapher\Graph as Graph;

/**
 * Controller: Default controller for AUTH_SUPERUSER / admins
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP_Controller
 * @copyright  Copyright (C) 2009-2016 Internet Neutral Exchange Association Company Limited By Guarantee
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class AdminController extends IXP_Controller_AuthRequiredAction
{

    public function preDispatch()
    {
        if( !( Auth::check() && Auth::user()->isSuperUser() ) ) {
            $this->getLogger()->notice( "{$this->getUser()->getUsername()} tried to access the admin controller without sufficient permissions" );
            $this->redirectAndEnsureDie( 'error/insufficient-permissions' );
        }
    }


    /**
     * The default action - show the home page
     */
    public function indexAction()
    {
        $this->_publicPeeringGraphs();
        $this->_dashboardStats();
    }


    /**
     * Get public peering graphs
     *
     */
    private function _publicPeeringGraphs()
    {
        $grapher = App::make('IXP\Services\Grapher');

        // only do this once every five minutes
        if( $admin_home_stats = Cache::get( 'admin_home_stats' ) ) {
            $this->view->graphs = $admin_home_stats['graphs'];
        } else {
            $admin_home_stats = [];
            $graphs = [];

            if( $this->multiIXP() )
            {
                // TODO in multi IXP mode, we return the aggregate for each graph
            }
            else
            {
                $graphs['ixp'] = $grapher->ixp( d2r( 'IXP' )->getDefault() )
                                ->setType(     Graph::TYPE_PNG )
                                ->setProtocol( Graph::PROTOCOL_ALL )
                                ->setPeriod(   Graph::PERIOD_MONTH )
                                ->setCategory( Graph::CATEGORY_BITS );

                foreach( d2r('IXP')->getDefault()->getInfrastructures() as $inf )
                {
                    $graphs[$inf->getId()] = $grapher->infrastructure( $inf )
                                ->setType(     Graph::TYPE_PNG )
                                ->setProtocol( Graph::PROTOCOL_ALL )
                                ->setPeriod(   Graph::PERIOD_MONTH )
                                ->setCategory( Graph::CATEGORY_BITS );
                }
            }
            
            $admin_home_stats['graphs'] = $this->view->graphs     = $graphs;
            Cache::put( 'admin_home_stats', $admin_home_stats, 300 );
        }
    }

    /**
     * Get type counts
     *
     */
    private function _dashboardStats()
    {
        // only do this once every 60 minutes
        if( !( $admin_home_ctypes = Cache::get( 'admin_home_ctypes' ) ) )
        {
            $admin_home_ctypes['types'] = $this->getD2EM()->getRepository( 'Entities\\Customer' )->getTypeCounts();

            $ints = $this->getD2EM()->getRepository( 'Entities\\VirtualInterface' )->getByLocation();

            $speeds = [];
            $bylocation = [];
            $bylan = [];
            $byixp = [];

            foreach( $ints as $int )
            {
                if( $this->multiIXP() )
                {
                    $locationname = sprintf( "%s - %s", $int['locixp'], $int['locationname'] );
                    $infrastructure = sprintf( "%s - %s", $int['locixp'], $int['infrastructure'] );
                }
                else
                {
                    $locationname = $int['locationname'];
                    $infrastructure = $int['infrastructure'];
                }

                if( !isset( $bylocation[ $locationname ] ) )
                    $bylocation[ $locationname ] = [];

                if( !isset( $bylan[ $infrastructure ] ) )
                    $bylan[ $infrastructure ] = [];

                if( !isset( $byixp[ $int['ixp'] ] ) )
                    $byixp[ $int['ixp'] ] = [];

                if( !isset( $speeds[ $int['speed'] ] ) )
                    $speeds[ $int['speed'] ] = 1;
                else
                    $speeds[ $int['speed'] ]++;

                if( !isset( $bylocation[ $int['locationname'] ][ $int['speed'] ] ) )
                    $bylocation[ $locationname ][ $int['speed'] ] = 1;
                else
                    $bylocation[ $locationname ][ $int['speed'] ]++;

                if( !isset( $byixp[ $int['ixp'] ][ $int['speed'] ] ) )
                    $byixp[ $int['ixp'] ][ $int['speed'] ] = 1;
                else
                    $byixp[ $int['ixp'] ][ $int['speed'] ]++;

                if( !isset( $bylan[ $infrastructure ][ $int['speed'] ] ) )
                    $bylan[ $infrastructure ][ $int['speed'] ] = 1;
                else
                    $bylan[ $infrastructure ][ $int['speed'] ]++;
            }

            ksort( $speeds, SORT_NUMERIC );
            $this->view->speeds      = $admin_home_ctypes['speeds']      = $speeds;
            $this->view->bylocation  = $admin_home_ctypes['bylocation']  = $bylocation;
            $this->view->bylan       = $admin_home_ctypes['bylan']       = $bylan;
            $this->view->byixp       = $admin_home_ctypes['byixp']       = $byixp;

            Cache::put( 'admin_home_ctypes', $admin_home_ctypes, 3600 );
        }

        $this->view->ctypes      = $admin_home_ctypes['types'];
        $this->view->speeds      = $admin_home_ctypes['speeds'];
        $this->view->bylocation  = $admin_home_ctypes['bylocation'];
        $this->view->bylan       = $admin_home_ctypes['bylan'];
        $this->view->byixp       = $admin_home_ctypes['byixp'];
    }

    public function staticAction()
    {
        $page = $this->_request->getParam( 'page', null );

        if( $page == null )
            return( $this->_redirect( 'index' ) );

        // does the requested static page exist? And if so, display it
        if( preg_match( '/^[a-zA-Z0-9\-]+$/', $page ) > 0
                && file_exists( APPLICATION_PATH . "/views/admin/static/{$page}.tpl" ) )
        {
            $this->view->display( "admin/static/{$page}.tpl" );
        }
        else
        {
            $this->addMessage( "The requested page was not found.", OSS_Message::ERROR );
            $this->redirect( 'index' );
        }
    }
}
