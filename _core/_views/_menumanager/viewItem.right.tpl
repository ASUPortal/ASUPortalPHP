<p>
    <a href="?action=view&id={$item->getMenu()->getId()}"><center>
        <img src="{$web_root}images/tango/32x32/actions/edit-undo.png"><br>
        К меню
    </center></a>
</p>

<p>
    <a href="?action=editItem&id={$item->getId()}"><center>
        <img src="{$web_root}images/tango/32x32/actions/edit-find-replace.png"><br>
        Правка
    </center></a>
</p>

<p>
    <a href="?action=removeItem&id={$item->getId()}"><center>
        <img src="{$web_root}images/tango/32x32/actions/edit-delete.png">
        Удалить пункт
    </center></a>
</p>