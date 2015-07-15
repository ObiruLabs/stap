'use strict';

angular.module('stap')
    .constant('GOOGLE_STATIC_MAP', 'https://maps.googleapis.com/maps/api/staticmap?center=<%= coordinates %>&zoom=14&size=150x150&markers=size:mid%7Ccolor:red%7C<%= coordinates %>&scale=2&key=AIzaSyDGcxPj2JOF7ViOZELFJ3hEbpGKnZbwpmU')
    .constant('METERS_TO_MILES', 1609.344)
    .controller('testingCtrl', function ($scope, $timeout, GOOGLE_STATIC_MAP, METERS_TO_MILES, Geocode) {

        var STATIC_MAP = _.template(GOOGLE_STATIC_MAP);

        $scope.withinRadius = [
            { label: '5 miles', value: 5 },
            { label: '10 miles', value: 10 },
            { label: '20 miles', value: 20 }
        ];
        $scope.counties = [];
        $scope.searchPlace = '';
        $scope.searchRadius = 5;
        $scope.totalSearchLocations = 0;
        $scope.searching = false;

        $scope.search = function () {
            if (_.isEmpty($scope.searchPlace)) {
                return;
            }

            $scope.searching = true;

            Geocode
                .bestMatch($scope.searchPlace)
                .then(searchCountiesNear);
        };

        $scope.clearSearch = function () {
            $scope.searchPlace = '';
            $scope.closestCounties = $scope.counties;
            $scope.searching = false;
            refreshMaps();
        };

        $scope.$watch('counties', function () {
            if (!_.isEmpty($scope.counties)) {
                setMap($scope.counties);
                $scope.closestCounties = $scope.counties;
            }
        });

        /**
         * Updates the filtered counties to places nearby the provided address.
         *
         * @param address {Object}
         */
        function searchCountiesNear(address) {
            var source = new google.maps.LatLng(address.coordinates.latitude, address.coordinates.longitude),
                filtered = [];

            $scope.totalSearchLocations = 0;

            _.forEach($scope.counties, function (county) {
                var matchingLocations = _.filter(county.locations, function (location) {
                    var distance = google.maps.geometry.spherical.computeDistanceBetween(source, location.coordinates);
                    return (distance / METERS_TO_MILES) <= $scope.searchRadius;
                });

                if (!_.isEmpty(matchingLocations)) {
                    filtered.push({
                        county: county.county,
                        map: county.map,
                        locations: matchingLocations
                    });

                    $scope.totalSearchLocations += matchingLocations.length;
                }
            });

            $scope.closestCounties = filtered;
            refreshMaps();
        }

        /**
         * Sets the location URL for the static maps and initializes coordinates.
         *
         * @param counties {Array}
         */
        function setMap(counties) {
            _.forEach(counties, function (county) {
                _.forEach(county.locations, function (location) {
                    location.map = STATIC_MAP({ coordinates: location.latitude + ',' + location.longitude });
                    location.coordinates = new google.maps.LatLng(location.latitude, location.longitude);
                });
            });
        }

        /**
         * Refreshes the images since these are added and removed from filtering.
         */
        function refreshMaps() {
            $timeout(function () {
                $('img').unveil(500);
            }, 0);
        }

    });
