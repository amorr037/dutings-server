/**
 * Created by javier on 3/5/16.
 */
(function(){
    angular.module('dutings', [
        'ui.router'
    ]);

    angular.module('dutings').config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/draw');

        $stateProvider.state('app', {
            url: '/draw',
            templateUrl: "/web/pages/draw.html"
        })
    }]);
})();