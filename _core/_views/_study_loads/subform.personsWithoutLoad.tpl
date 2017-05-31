<form action="index.php" method="post" id="withoutLoadView">
    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th>#</th>
            <th>ФИО преподавателя</th>
        </tr>
        {counter start=0 print=false}
        {foreach $persons->getItems() as $person}
        <tr>
            <td>{counter}</td>
            <td><a href="?action=add&kadri_id={$person->getId()}&year_id={$selectedYear}" title="Добавить" target="_blank">{$person->getName()}</a></td>
        </tr>
        {/foreach}
    </table>
</form>