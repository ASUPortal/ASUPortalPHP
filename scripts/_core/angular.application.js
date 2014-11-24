/**
 * Created by aleksandr on 16.11.14.
 */
var application = angular.module("asuApplication", [])
    .config(function($interpolateProvider){
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    });