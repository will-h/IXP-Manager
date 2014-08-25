<?php

// included from start/global.php

Config::addNamespace( 'ixp-manager', app_path() . '/config/ixp-manager' );

// Utility function to save typing: 
function D2R( $entity, $namespace = 'Entities' )
{
    return D2EM::getRepository( $namespace . '\\' . $entity );
}

