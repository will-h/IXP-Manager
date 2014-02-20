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
class IXP_Form_PatchPanel extends IXP_Form
{
    public function init()
    {
        $name = $this->createElement( 'text', 'name' );
        $name->addValidator( 'stringLength', false, array( 1, 255 ) )
            ->setRequired( true )
            ->setAttrib( 'class', 'span3' )
            ->setLabel( 'Name' )
            ->addFilter( 'StringTrim' )
            ->addFilter( new OSS_Filter_StripSlashes() );
        $this->addElement( $name );

        $this->addElement( IXP_Form_Cabinet::getPopulatedSelect( 'cabinetid' ) );

        $numports = $this->createElement( 'text', 'numports' );
        $numports->addValidator( 'between', false, array( 1, 1024 ) )
            ->setAttrib( 'class', 'span3' )
            ->setLabel( 'Number of Ports' )
            ->addFilter( new OSS_Filter_StripSlashes() );
        $this->addElement( $numports );

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

        $duplexAllowed = $this->createElement( 'checkbox', 'duplex_allowed' );
        $duplexAllowed->setLabel( 'Duplex Ports Allowed?' )
            ->setCheckedValue( '1' )
            ->setUncheckedValue( '0' )
            ->setValue( '0' );
        $this->addElement( $duplexAllowed );

        $notes = $this->createElement( 'textarea', 'notes' );
        $notes->setLabel( 'Notes' )
            ->setRequired( false )
            ->addFilter( new OSS_Filter_StripSlashes() )
            ->setAttrib( 'cols', 60 )
            ->setAttrib( 'class', 'span3' )
            ->setAttrib( 'rows', 5 );
        $this->addElement( $notes );

        $upos = $this->createElement( 'text', 'u_position' );
        $upos->addValidator( 'between', false, array( 0, 60 ) )
            ->setAttrib( 'class', 'span3' )
            ->setLabel( 'Rack U Position' )
            ->addFilter( new OSS_Filter_StripSlashes() );
        $this->addElement( $upos );

        $installed = $this->createElement( 'text', 'installed' );
        $installed->addValidator( 'stringLength', false, array( 10, 10 ) )
            ->addValidator( 'regex', false, array('/^\d\d\d\d-\d\d-\d\d/' ) )
            ->setRequired( false )
            ->setLabel( 'Installed' )
            ->setAttrib( 'placeholder', 'YYYY-MM-DD' )
            ->setAttrib( 'class', 'span4' )
            ->addFilter( 'StringTrim' )
            ->setAttrib( 'id', 'installed' );
        $this->addElement( $installed );

        $this->addElement( self::createSubmitElement( 'submit', _( 'Add' ) ) );
        $this->addElement( $this->createCancelElement() );
    }

    
    /**
     * Create a SELECT / dropdown element of all cabinet names indexed by their id.
     *
     * @param string $name The element name
     * @return Zend_Form_Element_Select The select element
     */
    public static function getPopulatedSelect( $name = 'cabinetid' )
    {
        $cab = new Zend_Form_Element_Select( $name );
        
        $maxId = self::populateSelectFromDatabase( $cab, '\\Entities\\Cabinet', 'id', 'name', 'name', 'ASC' );
        
        $cab->setRegisterInArrayValidator( true )
            ->setRequired( true )
            ->setLabel( _( 'Cabinet' ) )
            ->setAttrib( 'class', 'span2 chzn-select' )
            ->addValidator( 'between', false, array( 1, $maxId ) )
            ->setErrorMessages( array( _( 'Please select a cabinet' ) ) );
        
        return $cab;
    }
}

