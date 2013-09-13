<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Inherit from another theme
    |--------------------------------------------------------------------------
    |
    | Set up inherit from another if the file is not exists,
    | this is work with "layouts", "partials", "views" and "widgets"
    |
    | [Notice] assets cannot inherit.
    |
    */

    'inherit' => null, //default

    /*
    |--------------------------------------------------------------------------
    | Listener from events
    |--------------------------------------------------------------------------
    |
    | You can hook a theme when event fired on activities
    | this is cool feature to set up a title, meta, default styles and scripts.
    |
    | [Notice] these event can be override by package config.
    |
    */

    'events' => array(

        'before' => function($theme)
        {
            // You can remove this line anytime.
            $theme->setTitle('SoftLayer DNS Hosting');
            $theme->setKeywords('SoftLayer DNS, RoundRobin DNS, AnyCast DNS');
            $theme->setDescription('SoftLayer DNS Hosting');
        },

        'beforeRenderTheme' => function($theme)
        {

        },

        // Listen on event before render a layout,
        // this should call to assign style, script for a layout.
        'beforeRenderLayout' => array(

            'public' => function($theme)
            {
                $theme->asset()->usePath()->add('bootstrap', 'css/bootstrap.min.css');
                $theme->asset()->usePath()->add('theme', 'css/theme.css');
                $theme->asset()->usePath()->add('font-awesome', 'css/font-awesome.css');

                $theme->asset()->usePath()->add('jquery','js/jquery-1.8.1.min.js');
                $theme->asset()->usePath()->add('bootstrap-js','js/bootstrap.min.js');
            },
            'dashboard' => function($theme)
            {
                $theme->asset()->usePath()->add('bootstrap', 'css/bootstrap.min.css');
                $theme->asset()->usePath()->add('theme', 'css/theme.css');
                $theme->asset()->usePath()->add('font-awesome', 'css/font-awesome.css');

                $theme->asset()->usePath()->add('jquery','js/jquery-1.8.1.min.js');
                $theme->asset()->usePath()->add('bootstrap-js','js/bootstrap.min.js');
            }

        )

    )

);