'use strict';

angular.module('stap', [

        // Include all Angular dependencies
        'ngAnimate',
        'ngResource',
        'ngSanitize',
        'angularSpinner',
        'ui.mask'

    ])
    .config(function ($interpolateProvider) {

        $interpolateProvider
            .startSymbol('{[{')
            .endSymbol('}]}');

    });
