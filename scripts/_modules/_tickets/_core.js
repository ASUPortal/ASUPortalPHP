var ticket = {};
ticket._disciplines = null;
ticket._questions = null;

ticket.addQuestion = function() {
    var qCnt = $("#ticket_questions").children().length;
    if (qCnt == 4) {
        $("#ticket_questions_adder").remove();
    }

    var tP = jQuery("<p/>");
    var tLabel = jQuery("<label/>", {
        "for": "questions[" + qCnt + "]"
    });
    tLabel.append("Вопрос " + (qCnt + 1));
    tP.append(tLabel);
    var tSelect = jQuery("<select/>", {
        name: "discipline_id"
    });
    // добавляем событие по выбору вопроса
    tSelect.change(function(data) {
        var tParent = $(this).parent();
        var nSelect = jQuery("<select/>", {
            name: "question[]"
        });
        nSelect.append("<option disabled selected>Выберите вопрос</option>");
        $.each(ticket.getQuestions(this.value), function(i, item) {
            var tOption = jQuery("<option/>", {
                value: item.id
            });
            tOption.append(item.value);
            nSelect.append(tOption);
        });
        tParent.append(nSelect);
        $(this).remove();
    });
    // добавляем все дисциплины
    tSelect.append("<option disabled selected>Выберите дисциплину</option>");
    $.each(ticket.getDisciplines(), function(i, item) {
        var tOption = jQuery("<option/>", {
            value: item.id
        });
        tOption.append(item.value);
        tSelect.append(tOption);
    });
    tP.append(tSelect);
    var tDiv = jQuery("<div/>", {
        class: "ticket_question",
        id: "question_" + qCnt
    });
    tDiv.append(tP);
    $("#ticket_questions").append(tDiv);
}
ticket.getDisciplines = function() {
    if (ticket._disciplines == null) {
        ticket._disciplines = new Array();

        $.ajax({
            url: "questions.php?action=getDisciplinesJSON",
            dataType: "json",
            async: false
        }).done(function(data) {
            $.each(data, function(i, val) {
                ticket._disciplines[ticket._disciplines.length] = val;
            });
        });
    }
    return ticket._disciplines;
}
/**
 * Глобальный кэш вопросов, чтобы сократить количество запросов на сервер
 */
ticket.getCacheQuestions = function() {
    if (ticket._questions == null) {
        ticket._questions = new Array();
    }
    return ticket._questions;
}
/**
 * Вопросы по какой-либо дисциплине
 * @param id
 */
ticket.getQuestions = function(id) {
    if (!(id in ticket.getCacheQuestions())) {
        $.ajax({
            url: "questions.php?action=getQuestionsJSON",
            data: {
                id: id
            },
            dataType: "json",
            async: false
        }).done(function(data) {
            var tArr = new Array();
            $.each(data, function(i, val) {
                tArr[tArr.length] = val;
            });
            ticket.getCacheQuestions()[id] = tArr;
        });
    }
    return ticket.getCacheQuestions()[id];
}