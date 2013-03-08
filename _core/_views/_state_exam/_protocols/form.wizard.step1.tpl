<form action="protocols.php" method="post">
    {CHtml::hiddenField("action", "wizardStep2")}

    <p>
        {CHtml::label("Учебный год", "year_id")}
        {CHtml::dropDownList("year_id", CTaxonomyManager::getYearsList(), 0, "year_id", "", "onchange='protocols.onYearSelect()'")}
    </p>

    <p>
        {CHtml::label("Дата подписания протокола", "sign_date")}
        {CHtml::textField("sign_date")}
    </p>

    <p id="group_selector" style="display: none;">
        {CHtml::label("Учебная группа", "group_id")}
        {CHtml::dropDownList("group_id", CStaffManager::getAllStudentGroupsList(), 0, "group_id", "", "onchange='protocols.onGroupSelect()'")}
    </p>

    <p id="chariman_selector" style="display: none;">
        {CHtml::label("Председатель ГАК", "chairman_id")}
        {CHtml::dropDownList("chairman_id", CStaffManager::getPersonsList(), 0, "chairman_id", "", "onchange='protocols.onChairmanSelect()'")}
    </p>
        
    <div id="members_selector" style="display: none; ">

        <p>
            {CHtml::label("Председатель комиссии", "master_id")}
            {CHtml::dropDownList("master_id", CStaffManager::getPersonsList())}
        </p>

        <p>
            {CHtml::label("Член комиссии", "member[]")}
            {CHtml::dropDownList("member[]", CStaffManager::getPersonsList())}
        </p>

        <p>
            {CHtml::label("Член комиссии", "member[]")}
            {CHtml::dropDownList("member[]", CStaffManager::getPersonsList())}
        </p>

        <p>
            {CHtml::label("Член комиссии", "member[]")}
            {CHtml::dropDownList("member[]", CStaffManager::getPersonsList())}
        </p>

        <p>
            {CHtml::label("Член комиссии", "member[]")}
            {CHtml::dropDownList("member[]", CStaffManager::getPersonsList())}
        </p>

    </div>

    <p id="next_button" style="display: none;">
        {CHtml::submit("Далее")}
    </p>
</form>