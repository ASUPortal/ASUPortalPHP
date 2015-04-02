/**
 * Created by abarmin on 14.03.15.
 */
application
    .factory("LookupCatalog", function($resource, $cacheFactory){
        var lookupCache = $cacheFactory("LookupCatalog");
        return $resource(web_root + "_modules/_search/index.php", {

        }, {
            query: {
                method: "POST",
                isArray: true,
                cache: lookupCache,
                params: {
                    catalog: '@catalog',
                    action: "NgLookupViewData",
                    properties: '@properties'
                }
            }
        });
    })
    .controller("LookupController", [
        '$scope',
        'LookupCatalog',
        function ($scope, lookupCatalog) {
            $scope.items;
            $scope.itemsPlain;

            this.initLookup = function (glossary) {
                properties = [];
                if (glossary in lookupCatalogProperties) {
                    properties = lookupCatalogProperties[glossary];
                }
                if (glossary == "emptyGlossary") {
                    $scope.items = [];
                    $scope.itemsPlain = [];
                } else {
                    lookupCatalog.query({catalog: glossary, properties: properties}, function(data) {
                        $scope.items = data;
                        $scope.itemsPlain = [];
                        for (var i = 0; i < data.length; i++) {
                            $scope.itemsPlain[$scope.itemsPlain.length] = data[i].name;
                        }
                    });
                }
            }
        }
    ]);