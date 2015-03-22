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
            profiles: [],
            goals: [],
            tasks: [],
            competentions: [],
            disciplinesBefore: [],
            disciplinesAfter: []
        };
        $scope.init = function($id){
            workPlanFactory.get({id: $id}, function(data){
                $scope.workplan = data;
            });
        };

        $scope.addCompetention = function(){
            $scope.workplan.competentions[$scope.workplan.competentions.length] = {
                skills: [],
                knowledges: [],
                experiences: []
            };
        };

        $scope.removeCompetention = function(index){
            if (confirm("Вы действительно хотите удалить компетенцию?")) {
                $scope.workplan.competentions.splice(index, 1);
            }
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