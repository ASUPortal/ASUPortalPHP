<table border="1" width="100%" cellpadding="2" cellspacing="0">
    <tr>
        <th>#</th>
        <th>&nbsp;</th>
        <th>Название</th>
        <th>Сроки проведения</th>
    </tr>
{foreach $form->grant->events->getItems() as $event}
    <tr>
        <td>{counter}</td>
        <td><a href="#" onclick="if (confirm('Действительно удалить мероприятие {$event->title}')) { location.href='events.php?action=delete&id={$event->id}'; }; return false;"><img src="{$web_root}images/todelete.png"></a></td>
        <td><a href="events.php?action=edit&id={$event->getId()}">{$event->title}</a></td>
        <td>{$event->getTiming()}</td>
    </tr>
{/foreach}
</table>