/**
 * Created by aleksandr on 16.11.14.
 */
var application = angular.module("asuApplication", [
    "ngResource",
    "angular-loading-bar",
    "ui.select",
    "ngSanitize"
])
    .config(function($interpolateProvider){
        // $interpolateProvider.startSymbol('[[');
        // $interpolateProvider.endSymbol(']]');
    });