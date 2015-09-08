'use strict';

angular.module('stap')
    .directive('scrollToFixed', function ($timeout) {

        return {
            restrict: 'A',
            link: function (scope, el) {
                var $el = $(el);
                var hidden = false;

                $(window).scroll(_.debounce(calculateVisibility, 30));

                function calculateVisibility() {
                    var scroll = $(window).scrollTop();

                    if (!hidden && scroll > 140) {
                        $el.css({ top: 0 });
                        hidden = !hidden;
                    } else if(hidden && scroll <= 140) {
                        $el.css({ top: -130 });
                        hidden = !hidden;
                    }
                }

                $timeout(calculateVisibility, 20);
            }
        };

    });
