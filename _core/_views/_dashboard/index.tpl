{extends file="_core.3col.tpl"}

{block name="asu_center"}
	<ul class="nav nav-tabs" id="myTab">
	{foreach $dashboards as $title=>$items}
		{if $items->getCount()> 0}
			<li {if $items@index eq 0} class="active" {/if}><a data-toggle="tab" href="#tab-{$items@index}">{$title}</a></li>
		{/if}
	{/foreach}
	</ul>
	<div class="tab-content">
		{assign var=counter value=1}
	{foreach $dashboards as $title=>$items}
		{if $items->getCount()> 0}
			<div class="tab-pane {if $items@index eq 0} active {/if}" id="tab-{$items@index}">
				{include file="_dashboard/subform.dashboard.tpl"}
			</div>
		{/if}
	{/foreach}
	</div>
{/block}

{block name="asu_right"}
{include file="_dashboard/index.right.tpl"}
{/block}