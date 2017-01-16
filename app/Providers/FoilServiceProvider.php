<?php namespace IXP\Providers;

// based on: https://github.com/franzliedke/laravel-plates

use Illuminate\Support\ServiceProvider;
use IXP\Services\FoilEngine as Engine;

use IXP\Utils\Foil\Extensions\IXP as IXPFoilExtensions;

class FoilServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        $app->singleton('Foil\Engine', function () use ($app) {

            $engine = \Foil\engine([
                'folders'          => config('view.paths'),
                'ext'              => 'foil.php',
                'autoescape'       => false,                    // enabling this is a serious performance hit
                                                                // e.g. >30secs to generate INEX's MRTG config
                                                                // vs. x without it
                'strict_variables' => true,                     // enabled as using undef'd vars is a programming error
                'alias'            => 't'                       // $t is now shorthand for $this
            ]);

            return $engine;
        });

        $app->resolving('view', function($view) use ($app) {
            $view->addExtension('foil.php', 'foil', function() use ($app) {
                $engine = new Engine($app->make('Foil\Engine'));

                // we have a few rendering functions we want to include here:
                $engine->engine()->loadExtension( new IXPFoilExtensions(), [] );

                return $engine;
            });
        });
    }

}
