{extends file="_core.3col.tpl"}

{block name="asu_center"}
<div id="dashboard">
	{foreach $items->getItems() as $item}
		<div class="dashboard_item item-{$item->getId()}">
			<div class="item_icon">
				{if $item->icon !== ""}
					<img src="{$web_root}images/{$icon_theme}/64x64/{$item->icon}">
				{/if}
			</div>
			<div class="item_content">
				<h2>
					{if $item->link !== ""}
						<a href="{$item->link}">{$item->title}</a>
					{else}
						{$item->title}
					{/if}
				</h2>
				{if ($item->children->getCount() > 0)}
					<ul>
						{foreach $item->children->getItems() as $child}
							<li>
								{if $child->link !== ""}
									<a href="{$child->link}">{$child->title}</a>
								{else}
									{$child->title}
								{/if}							
							</li>
						{/foreach}
					</ul>
				{/if}
			</div>
			<div style="clear: both;"></div>
		</div>
	{/foreach}
</div>
{/block}

{block name="asu_right"}
{include file="_dashboard/index.right.tpl"}
{/block}