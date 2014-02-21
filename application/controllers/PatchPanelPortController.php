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
 * Controller: Manage patch panel ports
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP_Controller
 * @copyright  Copyright (c) 2009 - 2014, Internet Neutral Exchange Association Ltd
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class PatchPanelPortController extends IXP_Controller_FrontEnd
{
    /**
     * This function sets up the frontend controller
     */
    protected function _feInit()
    {
        $this->view->feParams = $this->_feParams = (object)[
            'entity'        => '\\Entities\\PatchPanelPort',
            'form'          => 'IXP_Form_PatchPanelPort',
            'pagetitle'     => 'Patch Panel Ports',

            'titleSingular' => 'Patch Panel Port',
            'nameSingular'  => 'a patch panel port'
        ];

        switch( $this->getUser()->getPrivs() )
        {
            case \Entities\User::AUTH_SUPERUSER:
                $this->_feParams->defaultAction = 'list';
                break;

            default:
                $this->redirectAndEnsureDie( 'error/insufficient-permissions' );
        }

        $this->view->registerClass( 'PATCH_PANEL', '\\Entities\\PatchPanel' );

    }

    /**
     * Provide array of objects for the listAction and viewAction
     *
     * @param int $id The `id` of the row to load for `viewAction`. `null` if `listAction`
     */
    protected function listGetData( $id = null )
    {
        if( !( $ppid = $this->getParam( 'ppid', false ) ) )
        {
            $this->addMessage( 'Please select a patch panel first', OSS_Message::ERROR );
            return $this->redirect( 'patch-panel/list' );
        }

        if( !( $this->view->patchPanel = $patchPanel = $this->getD2R( 'Entities\PatchPanel' )->find( $ppid ) ) )
        {
            $this->addMessage( 'Please select a valid patch panel', OSS_Message::ERROR );
            return $this->redirect( 'patch-panel/list' );
        }

        $qb = $this->getD2EM()->createQueryBuilder()
            ->select( 'ppp' )
            ->from( '\\Entities\\PatchPanelPort', 'ppp' )
            ->leftJoin( 'ppp.PatchPanel', 'pp' )
            ->where( 'ppp.deleted = FALSE' )
            ->andWhere( 'pp = :pp' )
            ->orderBy( 'ppp.position', 'ASC' );

        if( $id !== null )
            $qb->andWhere( 'ppp.id = ?1' )->setParameter( 1, $id );

        $q = $qb->getQuery();
        $q->setParameter( 'pp', $patchPanel );

        return $q->getResult();
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
            if( $object->getPatchPanel() )
                $form->getElement( 'patchpanelid' )->setValue( $object->getPatchPanel()->getId() );
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
        // if( $isEdit )
        //     $form->getElement( 'installed' )->setValue( $object->getInstalled()->format( 'Y-m-d' ) );
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
        $object->setPatchPanel(
            $this->getD2EM()->getRepository( '\\Entities\\PatchPanel' )->find( $form->getElement( 'patchpanelid' )->getValue() )
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

        // if( !( $object->getInstalled() instanceof DateTime ) )
        //     $object->setInstalled( new DateTime( $form->getValue( 'installed' ) ) );

        return true;
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

        return true;
    }


}

