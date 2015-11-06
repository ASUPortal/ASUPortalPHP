{CHtml::hiddenField("action", "save")}
{CHtml::activeHiddenField("id", $editSectionLoad)}
{CHtml::activeHiddenField("section_id", $editSectionLoad)}
<tr class="hide-required-star">
    <td>
        <a href="workplancontentloads.php?id={$section->getId()}" class="btn btn-danger"><i class="icon-remove"></i></a>
    </td>
    <td colspan="2">
        <button type="submit" class="btn btn-success">
            <i class="icon-ok"></i>
        </button>
    </td>
    <td>
        {CHtml::activeLookup("load_type_id", $editSectionLoad, "corriculum_labor_types", false, ["class" => "span12"])}
    </td>
    <td>
        {CHtml::activeLookup("term_id", $editSectionLoad, "class.CSearchCatalogWorkPlanTerms", false, ["plan_id" => $section->category->plan_id, "class" => "span12 hide-required-star"])}
    </td>
    <td width="150">
        {CHtml::activeTextField("value", $editSectionLoad, "", "span12")}
    </td>
</tr>