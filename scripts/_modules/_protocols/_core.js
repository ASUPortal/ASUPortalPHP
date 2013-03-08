var protocols = {};

protocols.onYearSelect = function() {
    // показываем выбиратор групп, если он еще не показан
    var tGroupP = $("#group_selector");
    if (tGroupP.css("display") == "none") {
        tGroupP.css("display", "block");
    }

    // загружаем в него список студенческих групп указанного года
    $.ajax({
        url: "protocols.php?action=StudentGroupsByYearJSON",
        data: {
            id: $("#year_id").val()
        },
        dataType: "json",
        async: false
    }).done(function(data) {
        var tGroupSelector = $("#group_id");
        tGroupSelector.children().remove();
        $.each(data, function(i, val) {
            tOption = jQuery("<option/>", {
                value: val.id
            });
            tOption.append(val.value);
            tGroupSelector.append(tOption);
        });
    });
}

protocols.onGroupSelect = function() {
    // показываем выбиратор председателя ГАК
    var tChairmanP = $("#chariman_selector");
    if (tChairmanP.css("display") == "none") {
        tChairmanP.css("display", "block");
    }
}

protocols.onChairmanSelect = function() {
    // показываем выбиратор членов ГАК
    var tMembersP = $("#members_selector");
    if (tMembersP.css("display") == "none") {
        tMembersP.css("display", "block");
    }

    // и кнопку далее
    var tNextButton = $("#next_button");
    if (tNextButton.css("display") == "none") {
        tNextButton.css("display", "block");
    }
}