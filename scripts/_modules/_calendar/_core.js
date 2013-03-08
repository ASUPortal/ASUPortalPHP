function onCalendarChange() {
    window.location.href = "?resource_id=" + $("[name='resource_id']").val() + "&calendar_id=" + $("[name='calendar_id']").val();
}