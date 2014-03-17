<?php

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
 * Form: adding / editing patch panels
 *
 * @author     Barry O'Donovan <barry@opensolutions.ie>
 * @category   IXP
 * @package    IXP_Form
 * @copyright  Copyright (c) 2009 - 2014, Internet Neutral Exchange Association Ltd
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL V2.0
 */
class IXP_Form_PatchPanelPort extends IXP_Form
{
    public function init()
    {
        $this->setDecorators( [ [ 'ViewScript', [ 'viewScript' => 'patch-panel-port/forms/edit.phtml' ] ] ] );

        $position = $this->createElement( 'text', 'position' );
        $position->addValidator( 'between', false, array( 1, 1024 ) )
            ->setAttrib( 'class', 'span3' )
            ->setLabel( 'Position' )
            ->addFilter( new OSS_Filter_StripSlashes() );
        $this->addElement( $position );

        $medium = $this->createElement( 'select', 'medium' );
        $medium->setMultiOptions( \Entities\PatchPanel::$MEDIA )
            ->setAttrib( 'class', 'span3 chzn-select' )
            ->setRegisterInArrayValidator( true )
            ->setLabel( 'Medium' )
            ->setErrorMessages( array( 'Please set the medium' ) );
        $this->addElement( $medium );

        $connector = $this->createElement( 'select', 'connector' );
        $connector->setMultiOptions( \Entities\PatchPanel::$CONNECTORS )
            ->setAttrib( 'class', 'span3 chzn-select' )
            ->setRegisterInArrayValidator( true )
            ->setLabel( 'Connector' )
            ->setErrorMessages( array( 'Please set the connector type' ) );
        $this->addElement( $connector );

        $availableForUse = $this->createElement( 'checkbox', 'available_for_use' );
        $availableForUse->setLabel( 'Available for Use?' )
            ->setCheckedValue( '1' )
            ->setUncheckedValue( '0' )
            ->setValue( '1' );
        $this->addElement( $availableForUse );

        $duplex = $this->createElement( 'select', 'duplex' );
        $duplex->setAttrib( 'class', 'span3 chzn-select' )
            ->setRegisterInArrayValidator( true )
            ->setLabel( 'Duplex With' );
        $this->addElement( $duplex );


        $coloref = $this->createElement( 'text', 'colo_reference' );
        $coloref->addValidator( 'stringLength', false, array( 1, 255 ) )
            ->setRequired( false )
            ->setAttrib( 'class', 'span3' )
            ->setLabel( 'Colo Ref' )
            ->addFilter( 'StringTrim' )
            ->addFilter( new OSS_Filter_StripSlashes() );
        $this->addElement( $coloref );

        foreach( [ 'assigned', 'connected', 'cancelled' ] as $date )
        {
            $$date = $this->createElement( 'text', $date );
            $$date->addValidator( 'stringLength', false, array( 0, 10 ) )
                ->addValidator( 'regex', false, array('/^(\d\d\d\d-\d\d-\d\d/){0,1}' ) )
                ->setRequired( false )
                ->setLabel( ucfirst( $date ) )
                ->setAttrib( 'placeholder', 'YYYY-MM-DD' )
                ->setAttrib( 'class', 'span4' )
                ->addFilter( 'StringTrim' )
                ->setAttrib( 'id', $date );
            $this->addElement( $$date );
        }


        $notes = $this->createElement( 'textarea', 'notes' );
        $notes->setLabel( 'Notes' )
            ->setRequired( false )
            ->addFilter( new OSS_Filter_StripSlashes() )
            ->setAttrib( 'cols', 60 )
            ->setAttrib( 'class', 'span3' )
            ->setAttrib( 'rows', 5 );
        $this->addElement( $notes );

        $this->addElement( self::createSubmitElement( 'submit', _( 'Add' ) ) );
        $this->addElement( $this->createCancelElement() );
    }

    
    /**
     * Create a SELECT / dropdown element of all patch panel ports indexed by their id.
     *
     * @param string $name The element name
     * @return Zend_Form_Element_Select The select element
     */
    public static function getPopulatedSelect( $name = 'patch_panel_port_id' )
    {
        $ppp = new Zend_Form_Element_Select( $name );
        
        $maxId = self::populateSelectFromDatabase( $ppp, '\\Entities\\PatchPanelPort', 'id', 'name', 'name', 'ASC' );
        
        $cab->setRegisterInArrayValidator( true )
            ->setRequired( true )
            ->setLabel( _( 'Cabinet' ) )
            ->setAttrib( 'class', 'span2 chzn-select' )
            ->addValidator( 'between', false, array( 1, $maxId ) )
            ->setErrorMessages( array( _( 'Please select a cabinet' ) ) );
        
        return $cab;
    }
}

