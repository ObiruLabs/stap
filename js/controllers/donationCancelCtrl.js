'use strict';

angular.module('stap')
    .controller('donationCancelCtrl', function($scope, $http) {

        $scope.form = {
            init: false,
            cancelled: true
        };

        initialize();

        $scope.submit = function() {
            var id = getParameterByName('a');

            var request = {
                method: 'POST',
                url: '/actions/donationForm/donations/cancel',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: serialize({
                    customer: id
                })
            };

            $http(request)
                .success(function() {
                    console.warn('donezo');
                });
        };

        function initialize() {
            var id = getParameterByName('a');

            $http.get('/actions/donationForm/donations/cancelStatus?customer=' + id)
                .then(function(response) {
                    $scope.form.init = true;

                    if (response.data.status === false) {
                        $scope.form.cancelled = false;
                    }
                })
                .catch(function() {
                    $scope.form.init = true;
                });
        }

        // http://stackoverflow.com/questions/901115/how-can-i-get-query-string-values-in-javascript
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }

        function serialize(obj) {
            var str = [];
            for (var p in obj)
                if (obj.hasOwnProperty(p)) {
                    str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
                }
            return str.join("&");
        }

    });
