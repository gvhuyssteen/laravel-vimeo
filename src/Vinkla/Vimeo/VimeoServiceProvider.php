<?php namespace Vinkla\Vimeo;

use Illuminate\Support\ServiceProvider;

class VimeoServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $loader = $this->app['config']->getLoader();

        $env = $this->app['config']->getEnvironment();

        if(file_exists(app_path() . '/config/packages/Vinkla/Vimeo')){
            $loader->addNamespace('vimeo', app_path() . '/config/packages/Vinkla/Vimeo');
        } else {
            $loader->addNamespace('vinkla/vimeo', __DIR__ . '/../../config');
        }

        $config = $loader->load($env, 'config', 'vimeo');

        $this->app['config']->set('vimeo::config', $config);

		// Register 'Vimeo' instance container to our Vimeo object.
		$this->app->bindShared('Vinkla\Vimeo\Contracts\Vimeo', function($app)
		{
			return new Vimeo(
				$app['config']['vimeo::client_id'],
				$app['config']['vimeo::client_secret'],
				$app['config']['vimeo::access_token']
			);
		});
	}
	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('vinkla/vimeo');
	}
	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['Vinkla\Vimeo\Contracts\Vimeo'];
	}

}