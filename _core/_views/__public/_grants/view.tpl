{extends file="_core.3col.tpl"}

{block name="asu_center"}
    <h2>{$object->title}</h2>

    <p>{$object->description}</p>
{/block}

{block name="asu_right"}
    {include file="__public/_grants/view.right.tpl"}
{/block}