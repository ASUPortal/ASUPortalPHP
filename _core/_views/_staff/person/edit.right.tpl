<p>
    <a href="?action=index"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
            Назад
        </center></a>
</p>

<p>
    <a href="children.php?action=add&parent_id={$form->person->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/contact-new.png"><br>
            Добавить ребенка
        </center></a>
</p>

<p>
    <a href="diploms.php?action=add&id={$form->person->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/address-book-new.png"><br>
            Добавить высшее образование
        </center></a>
</p>

<p>
    <a href="courses.php?action=add&id={$form->person->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/address-book-new.png"><br>
            Добавить курсы повышения квалификации
        </center></a>
</p>

<p>
    <a href="papers.php?action=add&id={$form->person->getId()}&type=1"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/address-book-new.png"><br>
            Добавить кандидатскую диссертацию
        </center></a>
</p>

<p>
    <a href="orderssab.php?action=add&id={$form->person->getId()}"><center>
            <img src="{$web_root}images/{$icon_theme}/32x32/actions/bookmark-new.png"><br>
            Добавить приказ ГЭК
        </center></a>
</p>

<p>
    <a href="#" onclick="jQuery('#dialog').dialog(); return false; ">
        <center>
            <img src="{$web_root}images/{$icon_theme}/32x32/devices/printer.png" /><br>
            Печать по шаблону
        </center></a>
</p>

<div id="dialog" style="display: none; " title="Выберите шаблон для печати">
    {CHtml::printOnTemplate("formset_person")}
</div>