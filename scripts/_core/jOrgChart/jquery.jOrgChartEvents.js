/**
 * Created by JetBrains PhpStorm.
 * User: TERRAN
 * Date: 06.05.12
 * Time: 12:39
 * To change this template use File | Settings | File Templates.
 */
var jOrgChartEvents = {};
jOrgChartEvents.onDrop = function (source, destination) {
    jQuery.ajax({
        url: "",
        type: "GET",
        data: {
            source: source[0].id,
            destination: destination[0].id,
            action: "ajaxUpdate"
        }
    }).done(function(){

    });
}