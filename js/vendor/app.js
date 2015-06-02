'use strict';

angular.module('stap', [

        // Include all Angular dependencies
        'ngAnimate',
        'ngResource',
        'ngSanitize',
        'angularSpinner'

    ])
    .config(function ($interpolateProvider) {

        $interpolateProvider
            .startSymbol('{[{')
            .endSymbol('}]}');

    });
