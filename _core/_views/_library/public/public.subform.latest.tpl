{if ($showLatest)}
    <h5>Последние файлы в разделе "Учеба"</h5>
    <table width="100%" border="0" cellpadding="5" cellspacing="0" class="tableBlank" style="border: 1px solid black; ">
        {foreach CLibraryManager::getLatestDocuments()->getItems() as $file}
            <tr>
                <td width="32">
                    {if ($file->getIconImagePath() !== "")}
                        <img src="{$file->getIconImagePath()}">
                    {/if}
                </td>
                <td>
                    <p><b><a href="{$file->getDownloadLink()}" target="_blank">{$file->browserFile}</a></b></p>
                </td>
                <td>
                    <p>
                        {if (!is_null($file->document))}
                            {if !is_null($file->document->subject)}
                                <a href="?action=index&filter=subject:{$file->document->subject->getId()}">{$file->document->subject->getValue()}</a>
                            {/if}
                        {/if}
                    </p>
                </td>
                <td>
                    <p>
                        {if $file->getAuthorName() !== ""}
                            <a href="?action=index&filter=author:{$file->getAuthorId()}">{$file->getAuthorName()}</a>
                        {/if}
                    </p>
                </td>
            </tr>
        {/foreach}
    </table>
    <br>
{/if}