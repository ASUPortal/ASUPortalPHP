/**
 * Created by abarmin on 14.03.15.
 */
application
    .factory("LookupCatalog", function($resource){
        return $resource(web_root + "_modules/_search/", {

        }, {
            query: {
                method: "GET",
                isArray: true,
                params: {
                    catalog: '@catalog',
                    action: "NgLookupViewData"
                }
            }
        });
    })
    .controller("LookupController", [
        '$scope',
        'LookupCatalog',
        function ($scope, lookupCatalog) {
            $scope.items;

            this.initLookup = function (glossary) {
                lookupCatalog.query({catalog: glossary}, function(data) {
                    $scope.items = data;

                    console.log($scope.items);
                });
            }
        }
    ]);