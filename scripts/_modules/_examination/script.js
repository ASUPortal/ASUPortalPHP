jQuery(document).ready(function(){
    // счетчик добавленных дисциплин
    var disciplinesAppended = 0;

    // в зависимости от измененного выбиратора скрываем
    // нижележащие, чтобы пользователь снова мог сделать выбор
    function hideBlocks(level) {
        var selectors = new Array();
        selectors[0] = "#course_group";
        selectors[1] = "#year_group";
        for (var i = level; i <= selectors.length; i++) {
            jQuery(selectors[i]).css("display", "none");
        }
        // при любом изменении обнуляем выбранные дисциплины
        for (i = 0; i <= disciplinesAppended; i++) {
            if (jQuery("#discpline_group_" + i).length > 0) {
                jQuery("#discpline_group_" + i).hide("slow", function(){
                    jQuery(this).remove();
                });
            }
            if (jQuery("#category_group_" + i).length > 0) {
                jQuery("#category_group_" + i).hide("slow", function(){
                    jQuery(this).remove();
                });
            }
        }
    }
    // при выборе специальности загрузаем курсы, по которым
    // есть вопросы
    jQuery("#speciality_id").change(function() {
        hideBlocks(1);
        // загружаем данные
        jQuery.ajax({
            url: web_root + "_modules/_json_service",
            dataType: "json",
            cache: false,
            data: {
                controller: "examination",
                action: "getCources",
                speciality: jQuery(this).val()
            },
            success: function (cources) {
                jQuery("#course_id").empty();
                var length = 0;
                jQuery.each(cources, function (key, value) {
                    jQuery("#course_id").append('<option value="' + value + '">' + value + '</option>');
                    length++;
                });
                // если у специальности только один курс, то сразу загружаем
                // данные за годы
                if (length == 1) {
                    jQuery("#course_id").change();
                }
            }
        });
        jQuery("#course_group").css("display", "block");
    });
    // при выборе курса выбираем год, для которого нам нужны билеты
    jQuery("#course_id").change(function(){
        hideBlocks(2);
        // загружаем данные по выбранной специальности и
        // выбранному курсу
        jQuery.getJSON(
            web_root + "_modules/_json_service",
            {
                controller: "examination",
                action: "getYears",
                speciality: jQuery("#speciality_id").val(),
                course: jQuery(this).val()
            },
            function(years) {
                jQuery("#year_id").empty();
                var length = 0;
                jQuery.each(years, function(key, value) {
                    jQuery("#year_id").append('<option value="' + key + '">' + value + '</option>');
                    length++;
                });
                // если только один год, то сразу показываем дисциплины
                if (length == 1) {
                    jQuery("#year_id").change();
                }
            }
        );
        jQuery("#year_group").css("display", "block");
    });
    // при выборе учебного года загружаем дисциплины с категориями
    jQuery("#year_id").change(function(){
        hideBlocks(4);
        jQuery("#disciplines_placeholder").css("display", "block");
    });
    // при нажатии на кнопку добавления дисциплины
    jQuery("#disciplines_adder").click(function(){
        // клонируем шаблон дисциплины
        var newBlock = jQuery("#discipline_group").clone(true).removeAttr("id").appendTo("#disciplines_placeholder");
        jQuery(newBlock).attr("id", "discpline_group_" + disciplinesAppended);
        var select = jQuery(newBlock).find("select")[0];
        jQuery(select).addClass("disciplineSelector");
        jQuery(select).attr("id", "discipline_selector_" + disciplinesAppended);
        jQuery(select).attr("name", jQuery(select).attr("name") + "[" + disciplinesAppended + "]");
        jQuery(newBlock).css("display", "block");
        jQuery(select).on("change", function(event){
            // загружаем список категорий
            jQuery.ajax({
                url: web_root + "_modules/_json_service",
                dataType: "json",
                cache: false,
                data: {
                    controller: "examination",
                    action: "getCategories",
                    speciality: jQuery("#speciality_id").val(),
                    course: jQuery("#course_id").val(),
                    year: jQuery("#year_id").val(),
                    discipline: jQuery(this).val()
                },
                success: function(categories) {
                    var select = jQuery(newBlock).find("select")[0];
                    var num = jQuery(select).parent().parent().attr("id").substr(16);
                    var select = jQuery("#category_selector_" + num);
                    jQuery(select).empty();
                    jQuery.each(categories, function(key, value) {
                        jQuery(select).append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });
        // при добавлении дисциплины заполняем выбиратор данными
        jQuery.getJSON(
            web_root + "_modules/_json_service",
            {
                controller: "examination",
                action: "getDisciplines",
                speciality: jQuery("#speciality_id").val(),
                course: jQuery("#course_id").val(),
                year: jQuery("#year_id").val()
            },
            function(disciplines) {
                var select = jQuery(newBlock).find("select")[0];
                var length = 0;
                jQuery(select).empty();
                jQuery.each(disciplines, function(key, value) {
                    jQuery(select).append('<option value="' + key + '">' + value + '</option>');
                    length++;
                });
                // если дисциплина только одна, то сразу загружаем к ней список категорий
                // если нет, то загрузим для первой
                // if (length == 1) {
                jQuery.getJSON(
                    web_root + "_modules/_json_service",
                    {
                        controller: "examination",
                        action: "getCategories",
                        speciality: jQuery("#speciality_id").val(),
                        course: jQuery("#course_id").val(),
                        year: jQuery("#year_id").val(),
                        discipline: jQuery(select).val()
                    },
                    function(categories) {
                        var select = jQuery(newBlock).find("select")[0];
                        var num = jQuery(select).parent().parent().attr("id").substr(16);
                        var select = jQuery("#category_selector_" + num);
                        jQuery(select).empty();
                        jQuery.each(categories, function(key, value) {
                            jQuery(select).append('<option value="' + key + '">' + value + '</option>');
                        });
                    }
                );
                // }
            }
        );
        // клонируем шаблон категории вопросов для дисциплины
        var newCategory = jQuery("#category_group").clone().removeAttr("id").appendTo("#disciplines_placeholder");
        jQuery(newCategory).attr("id", "category_group_" + disciplinesAppended);
        var select = jQuery(newCategory).find("select")[0];
        jQuery(select).removeAttr("id");
        jQuery(select).attr("id", "category_selector_" + disciplinesAppended);
        jQuery(select).attr("name", jQuery(select).attr("name") + "[" + disciplinesAppended + "]");
        jQuery(newCategory).css("display", "block");
        // увеличиваем количество добавленных дисциплин
        disciplinesAppended++;
    });
    // при выборе дисциплины в выбираторе дисциплин
    jQuery(".disciplineSelector").change(function(){
        alert(123);
    });
    // при нажатии на кнопку удаления дисциплины
    jQuery(".disciplineRemover").click(function(){
        var parent = jQuery(this).parent()[0];
        // получаем порядковый номер родительского элемента чтобы удалить
        var number = jQuery(parent).attr("id").substr(16);
        // скрываем элементы
        jQuery("#discpline_group_" + number).hide("slow", function(){
            jQuery(this).remove();
        });
        jQuery("#category_group_" + number).hide("slow", function(){
            jQuery(this).remove();
        });
    });
});