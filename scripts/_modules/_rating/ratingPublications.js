
var personList = null;
var personSelector = null;
jQuery(document).ready(function(){
    /**
     * Включаем автодополнения
     */
    jQuery("#years_list").tagit({
        itemName: "data",
        fieldName: "years",

        select: true,
        sortable: 'hande',
        allowNewTags: false,
        triggerKeys: "comma",

        tagSource: function(request, response) {
            jQuery.getJSON(
                web_root + "_modules/_json_service/index.php",
                {
                    controller: "ratingPublications",
                    action: "getYears",
                    data: request
                },
                response
            );
        },

        afterTagAdded: function (event, tag) {
            showRedrawMessage();
        },
        afterTagRemoved: function (event, tag) {
            showRedrawMessage();
        }
    });
    jQuery("#person_list").tagit({
        itemName: "data",
        fieldName: "persons",

        select: true,
        sortable: 'hande',
        allowNewTags: false,
        triggerKeys: "comma",

        tagSource: function(request, response) {
            var sendData = new Object();
            var years = new Array;
            jQuery('input[name="data[years][]"]').each(function(key, value) {
                years[years.length] = jQuery(value).val();
            });
            sendData["years"] = years;
            sendData["term"] = request.term;

            jQuery.getJSON(
                web_root + "_modules/_json_service/index.php",
                {
                    controller: "ratingPublications",
                    action: "getPersons",
                    data: sendData
                },
                response
            );
        },

        afterTagAdded: function (event, tag) {
            showRedrawMessage();
        },
        afterTagRemoved: function (event, tag) {
            showRedrawMessage();
        }
    });
    jQuery("#index_list").tagit({
        itemName: "data",
        fieldName: "indexes",

        select: true,
        sortable: 'hande',
        allowNewTags: false,
        triggerKeys: "comma",

        tagSource: function(request, response) {
            jQuery.getJSON(
                web_root + "_modules/_json_service/index.php",
                {
                    controller: "ratingPublications",
                    action: "getIndexes",
                    data: request
                },
                response
            );
        },

        afterTagAdded: function (event, tag) {
            showRedrawMessage();
        },
        afterTagRemoved: function (event, tag) {
            showRedrawMessage();
        }
    });
    /**
     * Вместо рисования диаграммы показываем сообщение о необходимости обновления
     */
    showRedrawMessage();
    /**
     * Обработчик событий при постановке курсора в поле выбора преподавателя
     */
    jQuery("#person_list").find("input").mousedown(function(e) {
        if (personSelector == null) {
            var selector = jQuery('<div style="border: 1px solid black; ' +
                'background: white; ' +
                'position: absolute; ' +
                'top: ' + e.pageY + 'px;' +
                'left: '+ e.pageX + 'px; ' +
                'width: 300px; ' +
                'height: 250px;' +
                'padding: 0px; " id="person_selector">');
            var allSelector = jQuery('<p><input type="checkbox" value="selectAll" checked>Все</p>');
            jQuery(selector).append(allSelector);
            var blockPersonSelector = jQuery('<div style="overflow-y: scroll; ' +
                'border: 1px solid #c0c0c0;' +
                'margin: 5px; ' +
                'height: 200px; ' +
                'width: 290px; ' +
                'background-image: url(' + web_root + 'images/design/load.gif); ' +
                'background-repeat: no-repeat;' +
                'background-position: center center; ">');
            jQuery(selector).append(blockPersonSelector);
            jQuery("body").append(selector);
            personSelector = jQuery("#person_selector");
            // запускаем аякс на первоначальное заполнение массива с людишками
            jQuery.ajax({
                url: web_root + "_modules/_json_service",
                data: {
                    controller: "staff",
                    action: "getAllStaff"
                },
                dataType: "json"
            }).done(function (persons) {
                personList = new Array();
                jQuery(blockPersonSelector).css("background-image", "none");
                jQuery.each(persons, function(key, value) {
                    var person = jQuery('<p style="padding: 0; margin: 0;"><input type="checkbox" checked value="' + key + '">' + value + '</p>');
                    personList[key] = value;
                    jQuery(blockPersonSelector).append(person);
                });
            });
            /**
             * Обработчик на кнопку все
             */
            allSelector.find("input").on("click", function() {
                var allSelectorChecked = jQuery(this).attr("checked");
                jQuery.each(blockPersonSelector.find("input"), function(key, input) {
                    if (allSelectorChecked) {
                        jQuery(input).attr("checked", true);
                    } else {
                        jQuery(input).removeAttr("checked");
                    }
                });
            });
            /**
             * Блок фильтров
             */
            var filterImage = jQuery('<img src="' + web_root +'images/filter.gif" style="' +
                'position: absolute;' +
                'top: 10px; ' +
                'left: 280px; ' +
                'cursor: pointer; " id="person_filter_image">');
            selector.append(filterImage);
            /**
             * Показ блока фильтров
             */
            filterImage.on("click", function() {
                selector.animate({
                    width: "510px"
                });
                filterImage.css("display", "none");
                // добавляем сам блок
                var filterBlock = jQuery('<div style="' +
                    'border: 1px solid #c0c0c0; ' +
                    'width: 200px;' +
                    'height: 200px; ' +
                    'position: absolute;' +
                    'top: 43px;' +
                    'left: 300px; ' +
                    'overflow-y: scroll; ">');
                selector.append(filterBlock);
                // грузим в него список ролей
                jQuery.ajax({
                    url: web_root + "_modules/_json_service/",
                    dataType: "json",
                    data: {
                        controller: "staff",
                        action: "getStaffRoles"
                    }
                }).done(function(roles) {
                    jQuery.each(roles, function(key, value) {
                        var role = jQuery('<p style="margin: 0; padding: 0; "><input type="checkbox" checked value="' + key + '">' + value + '</p>')
                        filterBlock.append(role);
                        // обработчик выбора типа человека
                        role.children("input").on("click", function(){
                            // получаем список поставленных галочек
                            var types = new Array();
                            var selectors = filterBlock.children("p").children("input:checked");
                            jQuery.each(selectors, function(index, value) {
                                types[types.length] = jQuery(value).attr("value");
                            });
                            // прогрессор
                            blockPersonSelector.children("p").remove();
                            blockPersonSelector.css("background-image", 'url(' + web_root + 'images/design/load.gif)');
                            // запрашиваем сервер
                            jQuery.ajax({
                                url: web_root + "_modules/_json_service/index.php",
                                type: "POST",
                                dataType: "JSON",
                                data: {
                                    controller: "staff",
                                    action: "getStaffWithRoles",
                                    roles: types.join(",")
                                }
                            }).done(function(persons) {
                                jQuery.each(persons, function(key, value) {
                                    var person = jQuery('<p style="padding: 0; margin: 0;"><input type="checkbox" checked value="' + key + '">' + value + '</p>');
                                    jQuery(blockPersonSelector).append(person);
                                });
                                blockPersonSelector.css("background-image", "none");
                            });
                        });
                    });        
                });
            });
            /**
             * Кнопка добавления выбранных в список людей
             */
            var button = jQuery('<input type="button" value="Добавить" style="' +
                'position: absolute;' +
                'top: 8px;' +
                'left: 50px;">');
            selector.append(button);
            /**
             * Обработчик нажатия на кнопку
             */
            button.on("click", function() {
                jQuery("#person_list").children("li.tagit-choice").remove();
                jQuery.each(blockPersonSelector.find("input:checked"), function(key, value) {
                    jQuery("#person_list").tagit("createTag", personList[jQuery(value).val()]);
                });
            });
            /**
             * Кнопка скрытия выбиратора
             */
            var closeButton = jQuery('<input type="button" value="Закрыть" style="' +
                'position: absolute;' +
                'top: 8px;' +
                'left: 125px; ">');
            selector.append(closeButton);
            /**
             * Обработчик нажатия на кнопку
             */
            closeButton.on("click", function() {
                selector.css("display", "none");
            });
        }
        personSelector.css("display", "block");
        personSelector.css("top", e.pageY);
        personSelector.css("left", e.pageX);
        // создаем чекбоксы
    });
});
function showRedrawMessage() {
    if (jQuery("#needUpdate").css("display") == "none") {
        jQuery("#needUpdate").css("display", "block");
    }
}
function hideRedrawMessage() {
    if (jQuery("#needUpdate").css("display") == "block") {
        jQuery("#needUpdate").css("display", "none");
    }
}
/**
 * Перерисовка диаграммы
 */
function redrawChart() {
    var sendData = new Object();
    var years = new Array;
    var persons = new Array;
    var indexes = new Array;

    hideRedrawMessage();
    jQuery("#graphContainer").html("Идет обновление диаграммы").css("border", "3px solid red").css("text-align", "center").css("margin", "10px").css("padding", "10px");

    jQuery('input[name="data[years][]"]').each(function(key, value) {
        years[years.length] = jQuery(value).val();
    });
    jQuery('input[name="data[persons][]"]').each(function(key, value) {
        persons[persons.length] = jQuery(value).val();
    });
    jQuery('input[name="data[indexes][]"]').each(function(key, value) {
        indexes[indexes.length] = jQuery(value).val();
    });
    sendData["years"] = years;
    sendData["persons"] = persons;
    sendData["indexes"] = indexes;

    jQuery.ajax({
        url: web_root + "_modules/_json_service/index.php",
        data: {
            controller: "ratingPublications",
            action: "getDataForChart",
            data: sendData
        },
        dataType: "json",
        type: "post"
    }).done(function(data) {
            var axis = new Array();
            // подписи к горизонтальной оси - люди
            jQuery.each(data.axis, function(key, value){
                axis[axis.length] = value;
            });

            var chartOptions = {

                chart: {
                    renderTo: 'graphContainer',
                    type: 'column'
                },

                title: {
                    text: 'Рейтинг преподавателей по публикациям'
                },

                xAxis: {
                    categories: axis
                },

                yAxis: {
                    allowDecimals: false,
                    title: {
                        text: 'Рейтинг'
                    }
                },

                tooltip: {
                    formatter: function() {
                        return '<b>'+ this.x +'</b><br/>'+
                            this.series.name +': '+ this.y;
                    }
                },

                plotOptions: {
                    column: {
                        stacking: 'normal',
                        dataLabels: {
                            enabled: true
                        }
                    }
                },

                series: data.series

            };

            jQuery("#graphContainer").css("border", "none");

            var chart;
            chart = new Highcharts.Chart(chartOptions);
        });
}