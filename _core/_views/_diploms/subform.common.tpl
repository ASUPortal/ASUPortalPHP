<p>
    {CHtml::activeLabel("dipl_name", $diplom)}
    {CHtml::activeTextBox("dipl_name", $diplom)}
    {CHtml::error("dipl_name", $diplom)}
</p>

<p>
    {CHtml::activeLabel("student_id", $diplom)}
    {CHtml::activeDropDownList("student_id", $diplom, CStaffManager::getAllStudentsThisYearList())}
    {CHtml::error("student_id", $diplom)}
</p>

<p>
    {CHtml::activeLabel("kadri_id", $diplom)}
    {CHtml::activeDropDownList("kadri_id", $diplom, CStaffManager::getPersonsList())}
    {CHtml::error("kadri_id", $diplom)}
</p>

<p>
    {CHtml::activeLabel("diplom_confirm", $diplom)}
    {CHtml::activeDropDownList("diplom_confirm", $diplom, CTaxonomyManager::getCacheDiplomConfirmations()->getItems())}
    {CHtml::error("diplom_confirm", $diplom)}
</p>

<p>
    {CHtml::activeLabel("pract_place_id", $diplom)}
    {CHtml::activeDropDownList("pract_place_id", $diplom, CTaxonomyManager::getPracticePlacesList())}
    {CHtml::error("pract_place_id", $diplom)}
</p>

<p>
    {CHtml::activeLabel("comment", $diplom)}
    {CHtml::activeTextBox("comment", $diplom)}
    {CHtml::error("comment", $diplom)}
</p>