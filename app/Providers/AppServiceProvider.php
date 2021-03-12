<?php

namespace App\Providers;

use App\Models\Group;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('group_exist', function($attribute, $values, $parameters)
        {
            $groups_ids = Group::pluck('id')->toArray();
            foreach($values as $value) {
                if(!in_array($value[0], $groups_ids)) {
                    return false;
                }
            }

            return true;
        });
    }
}
