'use strict';

angular.module('stap')
    .constant('GOOGLE_GEOCODE', 'https://maps.googleapis.com/maps/api/geocode/json?address=<%= address %>&key=AIzaSyDGcxPj2JOF7ViOZELFJ3hEbpGKnZbwpmU')
    .factory('Geocode', function ($http, GOOGLE_GEOCODE) {

        var GEOCODE_URL = _.template(GOOGLE_GEOCODE);

        /**
         * Retrieves only the best match address.
         *
         * @param address {String}
         * @returns {Object}
         */
        function bestMatch(address) {
            return fromAddress(address).then(function (addresses) {
                return addresses[0];
            });
        }

        /**
         * Retrieves all addresses that matches the provided string.
         *
         * @param address {String}
         * @returns {Array}
         */
        function fromAddress(address) {
            return $http.get(GEOCODE_URL({ address: encodeURIComponent(address) })).then(function (response) {
                return _.map(response.data.results, function (location) {
                    return {
                        address: location.formatted_address,
                        coordinates: {
                            latitude: location.geometry.location.lat,
                            longitude: location.geometry.location.lng
                        }
                    };
                });
            });
        }

        return {
            bestMatch: bestMatch,
            fromAddress: fromAddress
        };

    });
