//var $ = require('jquery');
var angular = require('angular');

var randomProperty = function (obj) {
    var keys = Object.keys(obj);
    return obj[keys[keys.length * Math.random() << 0]];
};

var myAppModule = angular.module('myApp', [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

myAppModule.filter('greet', function() {
    return function(name) {
        return 'Hé bé, ' + name + '!';
    };
});

myAppModule.controller('WelcomeController', function WelcomeController($scope) {
    $scope.users = webzine.users;
    $scope.user = randomProperty($scope.users);
});