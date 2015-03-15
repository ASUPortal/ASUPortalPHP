/**
 * Created by abarmin on 13.03.15.
 */
application
    .controller("WorkPlanController", [
        '$scope',
        'WorkPlan',
    function($scope, workPlanFactory){
        $scope.workplan;
        $scope.workplan = {
            profiles: []
        };
        $scope.init = function($id){
            workPlanFactory.get({id: $id}, function(data){
                $scope.workplan = data;
            });
        };

        $scope.save = function(){
            $scope.workplan.$save();
        }
    }]);

application
    .factory('WorkPlan', function($resource){
        return $resource(web_root + "_modules/_corriculum/workplans.php", {
            model: "CWorkPlan",
            type: "json"
        }, {
            get: {
                method: "GET",
                params: {
                    id: '@id',
                    action: "get"
                }
            }
        });
    });