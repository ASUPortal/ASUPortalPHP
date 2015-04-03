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
            disciplinesAfter: [],
            sections: []
        };
        $scope.init = function($id){
            workPlanFactory.get({id: $id}, function(data){
                $scope.workplan = data;
            });
        };

        $scope.addTask = function(){
            $scope.workplan.tasks[$scope.workplan.tasks.length] = {
                plan_id: $scope.workplan.id
            };
        };

        $scope.removeTask = function(index){
            if (confirm("Вы действительно хотите удалить задачу?")) {
                $scope.workplan.tasks.splice(index, 1);
            }
        };

        $scope.addGoal = function(){
            $scope.workplan.goals[$scope.workplan.goals.length] = {
                plan_id: $scope.workplan.id
            };
        };

        $scope.removeGoal = function(index){
            if (confirm("Вы действительно хотите удалить цель?")) {
                $scope.workplan.goals.splice(index, 1);
            }
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

        $scope.addSection = function(){
            $scope.workplan.sections[$scope.workplan.sections.length] = {
                plan_id: $scope.workplan.id,
                sectionIndex: $scope.workplan.sections.length + 1,
                lectures: [],
                controls: []
            };
            // сохраним, чтобы присвоился id раздела
            // он там много где нужен
            $scope.save();
        };

        $scope.removeSection = function(index){
            if (confirm("Вы действительно хотите удалить раздел?")) {
                $scope.workplan.sections.splice(index, 1);
            }
        };

        $scope.addLecture = function(index){
            var section = $scope.workplan.sections[index];
            section.lectures[section.lectures.length] = {
                section_id: section.id
            };
        };

        $scope.removeLecture = function(section, index){
            if (confirm("Вы действительно хотите удалить содержимое раздела?")) {
                var section = $scope.workplan.sections[section];
                section.lectures.splice(index, 1);
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