<table class="table table-striped table-bordered table-hover table-condensed">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Название</th>
        <th>Сроки проведения</th>
    </tr>
{foreach $form->grant->events->getItems() as $event}
    <tr>
        <td>{counter}</td>
        <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить мероприятие {$event->title}')) { location.href='events.php?action=delete&id={$event->id}'; }; return false;"></a></td>
        <td><a href="events.php?action=edit&id={$event->getId()}">{$event->title}</a></td>
        <td>{$event->getTiming()}</td>
    </tr>
{/foreach}
</table>