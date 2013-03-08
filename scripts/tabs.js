//-------------------------------------------------------------------------
var onMouseStyle="forms_under_border" ;
var clickStyle="light_color_max";

function newColor(idCell) {
    if ($('#'+idCell).hasClass(clickStyle).toString()=='false') {        $('#'+idCell).addClass(clickStyle);}
}

function backColor(idCell) {
 if ($('#'+idCell).hasClass(clickStyle).toString()=='true') {    $('#'+idCell).removeClass(clickStyle);    }
}

function Click_color(idCell,tabs_cnt) {
var i;

$('#'+idCell).addClass(onMouseStyle);
    for (i=1;i<=tabs_cnt;i++)   if (i!=idCell[1]) {       $('#c'+i).removeClass(onMouseStyle);    }
}
//-------------------------------------------------------------------------

