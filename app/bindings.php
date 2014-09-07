<?php


// For IXP Manager, we use Doctrine2 so we will bind the User repository
// as the authentication user provider
App::singleton( 'Oss2\\Auth\\UserProviderInterface', function(){
    return new \Oss2\Auth\Providers\FixedProvider( [], new \Oss2\Auth\Hashing\PlaintextHasher );
});
