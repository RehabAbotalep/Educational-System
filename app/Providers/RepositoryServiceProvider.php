<?php

namespace App\Providers;

use App\Http\Interfaces\AuthInterface;
use App\Http\Repositories\AuthRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\AuthInterface',
            'App\Http\Repositories\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\StaffInterface',
            'App\Http\Repositories\StaffRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\TeacherInterface',
            'App\Http\Repositories\TeacherRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\GroupInterface',
            'App\Http\Repositories\GroupRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\StudentInterface',
            'App\Http\Repositories\StudentRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\SessionInterface',
            'App\Http\Repositories\SessionRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\ComplaintInterface',
            'App\Http\Repositories\ComplaintRepository'
        );

        $this->app->bind(
            'App\Http\Interfaces\SubscriptionInterface',
            'App\Http\Repositories\SubscriptionRepository'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
