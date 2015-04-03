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
            sections: [],
            terms: []
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
                experiences: [],
                plan_id: $scope.workplan.id
            };
            // мне надо знать id компетенции
            $scope.save();
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

        $scope.onCompetentionChildSelect = function($item, $model, $index){
            // при выборе ЗУНа в объект надо дописать id компетенции
            // иначе ломается ограничение целостности
            $model.competention_id = $scope.workplan.competentions[$index].id;
        };

        $scope.onProfileSelect = function($item, $model, $index){
            // при выборе профиля указать план
            $model.plan_id = $scope.workplan.id;
        };

        $scope.save = function(){
            return $scope.workplan.$save();
        }
    }]);

application
    .controller("WorkPlanTermsController", [
        '$scope',

    function($scope){
        $scope.addTerm = function(){
            $scope.workplan.terms[$scope.workplan.terms.length] = {
                plan_id: $scope.workplan.id,
                types: [],
                sections: []
            };
            // сохраним, а потом скопируем виды нагрузки
            $scope.save().then(function(){
                // если у нас уже есть семестры, то из первого копируем виды нагрузки
                if ($scope.workplan.terms.length > 1) {
                    var first = $scope.workplan.terms[0];
                    var last = $scope.workplan.terms[$scope.workplan.terms.length - 1];
                    for (var i = 0; i < first.types.length; i++) {
                        last.types[last.types.length] = {
                            term_id: last.id,
                            type_id: first.types[i].type_id
                        };
                    }
                }
            });
        };

        $scope.removeTerm = function(index){
            // удаление семестра
            if (confirm("Вы действительно хотите удалить семестр?")) {
                $scope.workplan.terms.splice(index, 1);
            }
        };

        $scope.addTermLoad = function(){
            // добавим во все семестры вид нагрузки
            for (var i = 0; i < $scope.workplan.terms.length; i++) {
                var term = $scope.workplan.terms[i];
                term.types[term.types.length] = {
                    term_id: term.id
                };
            }
        };

        $scope.onTypeSelect = function($item, $model, $index) {
            // укажем вид занятия во всех семестрах
            for (var i = 0; i < $scope.workplan.terms.length; i++) {
                var term = $scope.workplan.terms[i];
                term.types[$index].type_id = $model;
            }
        };

        $scope.addTermSection = function($index){
            // добавление раздела в семестр
            var term = $scope.workplan.terms[$index];
            term.sections[term.sections.length] = {
                term_id: term.id
            };
        };
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