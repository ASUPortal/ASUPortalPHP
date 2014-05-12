{extends file="_core.3col.tpl"}

{block name="asu_center"}
<h2>Редактирование приказа</h2>

    <ul class="nav nav-tabs">
        <li class="active"><a data-toggle="tab" href="#tab-common">Общие сведения</a></li>
        <li><a data-toggle="tab" href="#tab-news">Новости</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab-common">
            {include file="_orders_usatu/form.tpl"}
        </div>
        <div class="tab-pane" id="tab-news">
            {include file="_orders_usatu/subform.news.tpl"}
        </div>
    </div>
{/block}

{block name="asu_right"}
{include file="_orders_usatu/edit.right.tpl"}
{/block}