<div class="asu_char_select">
    {foreach CLibraryManager::getSubjectAlphabetically()->getItems() as $key=>$value}
        <span><a href="?action=index&filter=char:{$key}">{$value}</a></span>
    {/foreach}
    <br><br><a href="index.php">все</a><br>
</div>