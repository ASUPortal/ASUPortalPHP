{if $object->work_type == "1"}
    <p>
        <a href="load.php?action=view&id={$object->getLoad()->person_id}">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
                Назад
            </center></a>
    </p>
{else}
    <p>
        <a href="load.php?action=view&id={$object->load->person_id}">
            <center>
                <img src="{$web_root}images/{$icon_theme}/32x32/actions/edit-undo.png"><br>
                Назад
            </center></a>
    </p>
{/if}