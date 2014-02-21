<?php

use Entities\PatchPanel;

/*
 * Copyright (C) 2009-2014 Internet Neutral Exchange Association Limited.
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


/**
 * Controller: Manage patch panels
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP_Controller
 * @copyright  Copyright (c) 2009 - 2014, Internet Neutral Exchange Association Ltd
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class PatchPanelController extends IXP_Controller_FrontEnd
{
    /**
     * This function sets up the frontend controller
     */
    protected function _feInit()
    {
        $this->view->feParams = $this->_feParams = (object)[
            'entity'        => '\\Entities\\PatchPanel',
            'form'          => 'IXP_Form_PatchPanel',
            'pagetitle'     => 'Patch Panels',

            'titleSingular' => 'Patch Panel',
            'nameSingular'  => 'a patch panel',

            'listOrderBy'    => 'name',
            'listOrderByDir' => 'ASC'
        ];

        switch( $this->getUser()->getPrivs() )
        {
            case \Entities\User::AUTH_SUPERUSER:
                $this->_feParams->listColumns = [
                    'id'        => [ 'title' => 'UID', 'display' => false ],
                    'name'           => 'Name',

                    'cabinet'  => [
                        'title'      => 'Cabinet',
                        'type'       => self::$FE_COL_TYPES[ 'HAS_ONE' ],
                        'controller' => 'cabinet',
                        'action'     => 'view',
                        'idField'    => 'cabinet_id'
                    ],

                    'medium'         => [
                        'title'     => 'Medium',
                        'type'      => self::$FE_COL_TYPES[ 'XLATE' ],
                        'xlator'    => \Entities\PatchPanel::$MEDIA
                    ],

                    'connector'         => [
                        'title'     => 'Connector',
                        'type'      => self::$FE_COL_TYPES[ 'XLATE' ],
                        'xlator'    => \Entities\PatchPanel::$CONNECTORS
                    ],


                    'duplex_allowed'       => [
                            'title'    => 'Duplex?',
                            'type'     => self::$FE_COL_TYPES[ 'SCRIPT' ],
                            'script'   => 'frontend/list-column-active.phtml',
                            'colname'  => 'duplex_allowed'
                    ]
                ];

                // display the same information in the view as the list
                $this->_feParams->viewColumns = array_merge(
                    $this->_feParams->listColumns,
                    [
                        'u_position'     => 'Rack U Position',
                        'installed'      => [
                            'title'     => 'Installed',
                            'type'      => self::$FE_COL_TYPES[ 'DATE' ]
                        ],
                        'notes'          => 'Notes'
                    ]
                );

                $this->_feParams->defaultAction = 'list';
                break;

            default:
                $this->redirectAndEnsureDie( 'error/insufficient-permissions' );
        }
    }

    /**
     * Provide array of objects for the listAction and viewAction
     *
     * @param int $id The `id` of the row to load for `viewAction`. `null` if `listAction`
     */
    protected function listGetData( $id = null )
    {
        $qb = $this->getD2EM()->createQueryBuilder()
            ->select( 'pp.id AS id, pp.name AS name, pp.duplex_allowed AS duplex_allowed, pp.notes AS notes,
                        pp.medium AS medium, pp.connector AS connector, pp.u_position AS u_position,
                        pp.installed AS installed,
                        c.id AS cabinet_id, c.name AS cabinet' )
            ->from( '\\Entities\\PatchPanel', 'pp' )
            ->leftJoin( 'pp.Cabinet', 'c' );

        if( isset( $this->_feParams->listOrderBy ) )
            $qb->orderBy( $this->_feParams->listOrderBy, isset( $this->_feParams->listOrderByDir ) ? $this->_feParams->listOrderByDir : 'ASC' );

        if( $id !== null )
            $qb->andWhere( 'pp.id = ?1' )->setParameter( 1, $id );

        return $qb->getQuery()->getResult();
    }




    /**
     *
     * @param IXP_Form_PatchPanel $form The form object
     * @param \Entities\PatchPanel $object The Doctrine2 entity (being edited or blank for add)
     * @param bool $isEdit True of we are editing an object, false otherwise
     * @param array $options Options passed onto Zend_Form
     * @param string $cancelLocation Where to redirect to if 'Cancal' is clicked
     * @return void
     */
    protected function formPostProcess( $form, $object, $isEdit, $options = null, $cancelLocation = null )
    {
        if( $isEdit )
        {
            if( $object->getCabinet() )
                $form->getElement( 'cabinetid' )->setValue( $object->getCabinet()->getId() );
        }
    }

    /**
     * Preparation hook that can be overridden by subclasses for add and edit.
     *
     * This is called just before we process a possible POST / submission and
     * will allow us to change / alter the form or object.
     *
     * @param OSS_Form $form The Send form object
     * @param object $object The Doctrine2 entity (being edited or blank for add)
     * @param bool $isEdit True if we are editing, otherwise false
     */
    protected function addPrepare( $form, $object, $isEdit )
    {
        if( $isEdit )
            $form->getElement( 'installed' )->setValue( $object->getInstalled()->format( 'Y-m-d' ) );
    }


    /**
     *
     * @param IXP_Form_PatchPanel $form The form object
     * @param \Entities\PatchPanel $object The Doctrine2 entity (being edited or blank for add)
     * @param bool $isEdit True of we are editing an object, false otherwise
     * @return void
     */
    protected function addPostValidate( $form, $object, $isEdit )
    {
        $object->setCabinet(
            $this->getD2EM()->getRepository( '\\Entities\\Cabinet' )->find( $form->getElement( 'cabinetid' )->getValue() )
        );

        return true;
    }

    /**
     *
     * @param IXP_Form_PatchPanel $form The Send form object
     * @param \Entities\PatchPanel $object The Doctrine2 entity (being edited or blank for add)
     * @param bool $isEdit True if we are editing, otherwise false
     * @return bool If false, the form is not processed
     */
    protected function addPreFlush( $form, $object, $isEdit )
    {

        if( !( $object->getInstalled() instanceof DateTime ) )
            $object->setInstalled( new DateTime( $form->getValue( 'installed' ) ) );

        return true;
    }

    /**
     * Post database flush hook that can be overridden by subclasses for add and edit.
     *
     * This is called if the user POSTs a valid form after the posted
     * data has been flushed to the database.
     *
     * If you return `false`, the the standard log and OSS_Message will not be
     * created / displayed and a `redirect()` will not be performed.
     *
     * NB: also calls `postFlush()`
     *
     * @param OSS_Form $form The Send form object
     * @param object $object The Doctrine2 entity (being edited or blank for add)
     * @param bool $isEdit True if we are editing, otherwise false
     * @return bool If false, supress standard log and OSS_Message and the redirection
     */
    protected function addPostFlush( $form, $object, $isEdit )
    {
        // add the ports
        for( $i = 1; $i <= $form->getValue( 'numports'); $i++ )
        {
            $ppp = new Entities\PatchPanelPort;
            $ppp->setPatchPanel( $object );
            $ppp->setPosition( $i );
            $ppp->setMedium( $object->getMedium() );
            $ppp->setConnector( $object->getConnector() );
            $ppp->setAvailableForUse( true );
            $ppp->setDuplex( 0 );
            $ppp->setColoReference( '' );
            $ppp->setNotes( '' );
            $ppp->setDeleted( false );
            $this->getD2EM()->persist( $ppp );
        }

        $this->getD2EM()->flush();

        return $this->postFlush( $object );
    }

    /**
     * Function which can be over-ridden to perform any pre-deletion tasks
     *
     * You can stop the deletion by returning false but you should also add a
     * message to explain why.
     *
     * @param object $object The Doctrine2 entity to delete
     * @return bool Return false to stop / cancel the deletion
     */
    protected function preDelete( $object )
    {

        foreach( $object->getPatchPanelPorts() as $ppp )
        {
            if( $ppp->getPatchPanelPortObject() )
            {
                $this->addMessage(
                    "Could not delete the patch panel as at least one port is still assigned",
                    OSS_Message::ERROR
                );
                return false;
            }
        }

        // if we got here, all switch ports are free
        foreach( $object->getPatchPanelPorts() as $ppp )
            $this->getD2EM()->remove( $ppp );

        return true;
    }


}

