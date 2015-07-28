'use strict';

angular.module('stap')
    .controller('donationCtrl', function($scope, $http) {

        $scope.creditCardMonths =  _.map(_.range(1, 13), function(month) {
            return ('0' + month).slice (-2);
        });
        $scope.creditCardYears = _.range(moment().year(), +moment().year() + 15);
        $scope.form = {};
        $scope.card = {};

        $scope.card = {
            number: '4242424242424242',
            expirationMonth: '05',
            expirationYear: 2018,
            cvc: '123'
        };

        var firstName = faker.name.firstName();
        var lastName = faker.name.lastName();

        $scope.form = {
            donorFirstName: firstName,
            donorLastName: lastName,
            donorEmail: firstName.toLowerCase() + '.' + lastName.toLowerCase() + '@gmail.com',
            donorAddress1: faker.address.streetAddress(),
            donorAddress2: '',
            donorCity: faker.address.city(),
            donorState: faker.address.stateAbbr(),
            donorCountry: 'US',
            donorZip: faker.address.zipCode(),

            behalfType: 'honor',
            behalfOtherType: '',
            behalfName: faker.name.firstName() + ' ' + faker.name.lastName(),

            notifyName: faker.name.firstName() + ' ' + faker.name.lastName(),
            notifyEmail: firstName.toLowerCase() + '.' + lastName.toLowerCase() + '@gmail.com',
            notifyAddress1: faker.address.streetAddress(),
            notifyAddress2: '',
            notifyCity: faker.address.city(),
            notifyState: faker.address.stateAbbr(),
            notifyCountry: 'US',
            notifyZip: faker.address.zipCode(),

            chargeAmount: _.random(500, 2500),
            chargeType: 'once',
            chargeStatus: 'complete'
        };

        $scope.submit = function() {
            Stripe.setPublishableKey($scope.stripePublishableKey);

            var cardNumber = $scope.card.number ? $scope.card.number.replace(/\D+/g, '') : '';
            var cardExpiryMonth = +$scope.card.expirationMonth;
            var cardExpiryYear = +$scope.card.expirationYear;
            var cardCVC = +$scope.card.cvc;

            Stripe.card.createToken({
                number: cardNumber,
                cvc: cardCVC,
                exp_month: cardExpiryMonth,
                exp_year: cardExpiryYear
            }, stripeCallback);
        };

        function stripeCallback(status, response) {
            $scope.$apply(function() {
                if (response.error) {
                    console.dir(response.error);
                } else {
                    completeDonation(response.id);
                }
            });
        }

        function completeDonation(token) {
            var prefixed = {};

            _.forOwn($scope.form, function(value, key) {
                prefixed['donation[' + key + ']'] = value;
            });

            prefixed.action = 'donationForm/donations/donate';

            var request = {
                method: 'POST',
                url: '/donation',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: serialize(prefixed)
            };

            $http(request)
                .success(function() {
                    console.warn('done');
                });
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
