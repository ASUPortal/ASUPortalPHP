{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Пользователи</h2>

    {CHtml::helpForCurrentPage()}

    {include file="_core.searchLocal.tpl"}

    <table class="table table-striped table-bordered table-hover table-condensed">
        <tr>
            <th></th>
            <th>#</th>
            <th width="100"><i class="icon-camera"></i></th>
            <th>{CHtml::tableOrder("FIO", $users->getFirstItem())}</th>
            <th>{CHtml::tableOrder("login", $users->getFirstItem())}</th>
            <th>{CHtml::tableOrder("kadri_id", $users->getFirstItem())}</th>
            <th>Группы</th>
            <th>Комментарий</th>
        </tr>
        {counter start=($paginator->getRecordSet()->getPageSize() * ($paginator->getCurrentPageNumber() - 1)) print=false}
        {foreach $users->getItems() as $user}
        <tr>
            <td><a class="icon-trash" href="#" onclick="if (confirm('Действительно удалить пользователя {$user->getName()}')) { location.href='?action=delete&id={$user->getId()}'; }; return false;"></a></td>
            <td>{counter}</td>
            <td>
                {CHtml::activeAttachPreview("photo", $user, 100)}
            </td>
            <td><a href="?action=edit&id={$user->getId()}">{$user->getName()}</a></td>
            <td>{$user->getLogin()}</td>
            <td>
                {if !is_null($user->getPerson())}
                    {$user->getPerson()->getName()}
                {/if}
            </td>
            <td>
            	<ul>
					{foreach $user->getGroups()->getItems() as $group}
						<li><a href="{$web_root}_modules/_users/groups.php?action=edit&id={$group->getId()}"><font color="{$group->color_mark}">{$group->comment}</font></a></li>
					{/foreach}
				</ul>
			</td>
            <td>{$user->comment}</td>
        </tr>
        {/foreach}
    </table>

    {CHtml::paginator($paginator, "?action=index")}
{/block}

{block name="asu_right"}
{include file="_users/users/index.right.tpl"}
{/block}