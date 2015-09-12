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
            expiration: '0518',
            expirationMonth: '05',
            expirationYear: 2018,
            cvc: '123'
        };

        var firstName = faker.name.firstName();
        var lastName = faker.name.lastName();

        $scope.form = {
            amountType: '',
            amountValue: 0,
            amountCustom: undefined,
            behalfSelected: false,
            notifyType: 'none',

            donorFirstName: firstName,
            donorLastName: lastName,
            donorEmail: firstName.toLowerCase() + '.' + lastName.toLowerCase() + '@gmail.com',
            //donorAddress1: faker.address.streetAddress(),
            //donorAddress2: '',
            //donorCity: faker.address.city(),
            //donorState: faker.address.stateAbbr(),
            //donorCountry: 'US',
            //donorZip: faker.address.zipCode(),

            behalfType: 'none',
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

        var currentStep = 0;

        $scope.selectAmountOption = function(amount) {
            $scope.form.amountType = 'option';
            $scope.form.amountValue = +amount;
            $scope.form.amountCustom = undefined;
        };

        /**
         * Formats the custom donation amount by stripping all non-numbers
         * and prefixing with '$' symbol.
         */
        $scope.updateCustomAmount = function() {
            if (!_.isEmpty($scope.form.amountCustom)) {
                var amount = $scope.form.amountCustom.match(/\d/g);
                $scope.form.amountType = 'custom';

                if (amount && amount[0] === '0') {
                    amount.splice(0, 1);
                }

                if (_.isEmpty(amount)) {
                    $scope.form.amountCustom = undefined;
                    $scope.form.amountType = undefined;
                } else {
                    $scope.form.amountCustom = '$' + amount.join('');
                }
            }
        };

        /**
         * Ensures cvc is less than or equal to 4 digits.
         *
         * @param oldCvc {String}
         */
        $scope.updateCardCvc = function(oldCvc) {
            if (!_.isEmpty($scope.card.cvc)) {
                var old = oldCvc.match(/\d/g);
                var cvc = $scope.card.cvc.match(/\d/g);

                if (old && cvc && cvc.length < old.length) {
                    return;
                }

                if (_.isEmpty(cvc)) {
                    $scope.card.cvc = '';
                } else if (cvc.length > 4) {
                    $scope.card.cvc = oldCvc;
                } else {
                    $scope.card.cvc = cvc.join('');
                }
            }
        };

        /**
         * Sets the default behalf type if selected or unsets it if unselected.
         */
        $scope.updateBehalfSelection = function() {
            if ($scope.form.behalfSelected && $scope.form.behalfType === 'none') {
                $scope.form.behalfType = 'honor';
            } else if (!$scope.form.behalfSelected && $scope.form.behalfType !== 'none') {
                $scope.form.behalfType = 'none';
            }
        };

        $scope.isStep = function(step) {
            return currentStep === step;
        };

        /**
         * Goes to the specified step if all the validations pass.
         *
         * @param step {Number} 0-index based step
         */
        $scope.gotoStep = function(step) {
            var validations = _.times(step, function(previousStep) {
                return isStepComplete(previousStep);
            });

            if (_.all(validations)) {
                currentStep = step;
            }
        };

        /**
         * Verifies whether or not the given step is complete.
         *
         * @param step
         * @returns {boolean}
         */
        function isStepComplete(step) {
            var isComplete = false;

            if (step === 0) {
                isComplete = $scope.donationAmount() > 0;
            }

            if (step === 1) {
                isComplete =
                    isPaymentInfoComplete() &&
                    isDedicationInfoComplete() &&
                    isNotifyInfoComplete();
            }

            return isComplete;
        }

        function isPaymentInfoComplete() {
            var requiredFields = [
                $scope.form.donorFirstName,
                $scope.form.donorLastName,
                $scope.form.donorEmail,
                $scope.form.donorZip,
                $scope.card.number,
                $scope.card.expiration,
                $scope.card.cvc
            ];
            var requiredEmailFields = [
                $scope.form.donorEmail
            ];

            var allRequired = _.all(requiredFields, function(value) {
                return $scope.requireField.validate(value);
            });
            var allEmailRequired = _.all(requiredEmailFields, function(value) {
                return $scope.requireEmailField.validate(value);
            });

            return allRequired && allEmailRequired;
        }

        function isDedicationInfoComplete() {
            if (!$scope.form.behalfSelected) {
                return true;
            }

            return $scope.requireField.validate($scope.form.behalfName);
        }

        function isNotifyInfoComplete() {
            if ($scope.form.notifyType === 'none') {
                return true;
            }

            var addtionalRequires = false;

            if ($scope.form.notifyType === 'email') {
                addtionalRequires = $scope.requireEmailField.validate($scope.form.notifyEmail);
            } else if ($scope.form.notifyType === 'letter') {
                var addressFields = [
                    $scope.form.notifyAddress1,
                    $scope.form.notifyCity,
                    $scope.form.notifyState,
                    $scope.form.notifyZip
                ];

                addtionalRequires = _.all(addressFields, function(value) {
                    return $scope.requireField.validate(value);
                });
            }

            return $scope.requireField.validate($scope.form.notifyName) && addtionalRequires;
        }

        /**
         * Extracts the chosen amount from the form depending on whether it is
         * a custom amount or not. This is the amount in cents;
         *
         * @returns {Number}
         */
        $scope.donationAmount = function() {
            var amount = 0;

            if ($scope.form.amountType === 'option') {
                amount = $scope.form.amountValue;
            }

            if ($scope.form.amountType === 'custom') {
                var customAmount = ($scope.form.amountCustom || '').match(/\d/g).join('');
                amount = +customAmount;
            }

            return amount * 100;
        };

        /**
         * Outputs the label of the field or the validation message when it fails.
         *
         * @param validator {Object}
         * @param value {String}
         * @param label {String}
         * @returns {String}
         */
        $scope.errorFieldLabel = function(validator, value, label) {
            if ($scope.form.submitted && !validator.validate(value)) {
                return validator.failureLabel;
            }

            return label;
        };

        /**
         * Determines whether the field has an error. The validator will only run
         * once the form has been submitted.
         *
         * @param validator {Object}
         * @param value {String}
         * @returns {Boolean}
         */
        $scope.errorField = function(validator, value) {
            return $scope.form.submitted && !validator.validate(value);
        };

        $scope.requireField = {
            failureLabel: 'Required',
            validate: function(value) {
                return !_.isEmpty(value);
            }
        };

        $scope.requireEmailField = {
            failureLabel: 'Invalid email address',
            validate: function(value) {
                var emailRegex = /\S+@\S+\.\S+/;
                return $scope.requireField.validate(value) && emailRegex.test(value);
            }
        };

        $scope.submit = function() {
            $scope.form.submitted = true;

            if (!isStepComplete(1)) {
                $scope.form.hasErrors = true;
                $scope.errorMessage = 'You are missing some required information. Please fill them out and try again.';
                return;
            }

            if ($scope.form.submitting) {
                return;
            }

            $scope.form.submitting = true;
            $scope.form.hasErrors = false;
            $scope.errorMessage = '';
            $scope.form.chargeAmount = $scope.donationAmount();

            Stripe.setPublishableKey($scope.stripePublishableKey);

            var cardNumber = $scope.card.number ? $scope.card.number.replace(/\D+/g, '') : '';
            var cardExpiryMonth = +$scope.card.expirationMonth;
            var cardExpiryYear = +$scope.card.expirationYear;
            var cardCVC = +$scope.card.cvc;

            //Stripe.card.createToken({
            //    number: cardNumber,
            //    cvc: cardCVC,
            //    exp_month: cardExpiryMonth,
            //    exp_year: cardExpiryYear,
            //    name: $scope.form.donorFirstName + ' ' + $scope.form.donorLastName,
            //    address_line1: $scope.form.donorAddress1,
            //    address_line2: $scope.form.donorAddress2,
            //    address_city: $scope.form.donorCity,
            //    address_state: $scope.form.donorState,
            //    address_zip: $scope.form.donorZip,
            //    address_country: $scope.form.donorCountry
            //}, stripeCallback);
        };

        function stripeCallback(status, response) {
            $scope.$apply(function() {
                if (response.error) {
                    console.dir(response.error);
                    $scope.form.submitting = false;
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
            prefixed.stripeToken = token;

            var request = {
                method: 'POST',
                url: '/donation',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                data: serialize(prefixed)
            };

            $http(request)
                .then(function() {
                    console.warn('donezo');
                })
                .finally(function() {
                    $scope.form.submitting = false;
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
