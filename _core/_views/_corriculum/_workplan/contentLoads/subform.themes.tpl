{if (CRequest::getInt("showDeleted") == 1)}
	{$loadTopics = $load->topics}
{else}
	{$loadTopics = $load->topicsDisplay}
{/if}
<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_themes" object=$section}</th>
    <th colspan="3" bgcolor="lightblue">Темы</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_themes"}
{foreach $loadTopics as $topic}
    {sf_showByDefault bean=$bean element="topic_load_{$topic->getId()}"}
    {sf_showIfVisible bean=$bean element="topic_load_{$topic->getId()}"}
        <tr>
            <td>{sf_toggleDelete object=$section bean=$bean model=$topic element="topic_load_{$topic->getId()}" address='workplancontentloads.php'}</td>
            <td width="16">#</td>
            <td width="16">{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="topic_load_{$topic->getId()}" object=$section}</td>
            <td width="16">&nbsp;</td>
            <td colspan="2" bgcolor="whitesmoke">{sf_text model=$topic attribute='title'}</td>
            <td colspan="2" bgcolor="wheat">{sf_text model=$topic attribute='value'}</td>
        </tr>
    {/sf_showIfVisible}
    {sf_showIfEditable bean=$bean element="topic_load_{$topic->getId()}"}
        {sf_hidden bean=$bean model=$topic element="topic_load_{$topic->getId()}" attribute='load_id'}
        {sf_hidden bean=$bean model=$topic element="topic_load_{$topic->getId()}" attribute='id'}
        <tr>
            <td>&nbsp;</td>
            <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="topic_load_{$topic->getId()}" object=$section}</td>
            <td>{sf_submit bean=$bean element="topic_load_{$topic->getId()}"}</td>
            <td>&nbsp;</td>
            <td colspan="2">{sf_input bean=$bean model=$topic element="topic_load_{$topic->getId()}" attribute='title' class='span12'}</td>
            <td>{sf_input bean=$bean model=$topic element="topic_load_{$topic->getId()}" attribute='value' class='span12'}</td>
        </tr>
    {/sf_showIfEditable}
{/foreach}
{sf_showIfVisible bean=$bean element="topic_load_{$load->getId()}_new"}
    <tr>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean load_id="{$load->getId()}" element="topic_load_{$load->getId()}_new" object=$section}</td>
        <td colspan="6">
            Добавить тему
        </td>
    </tr>
{/sf_showIfVisible}
{sf_showIfEditable bean=$bean element="topic_load_{$load->getId()}_new"}
    {sf_hidden bean=$bean model=$newTopic element="topic_load_{$load->getId()}_new" attribute='load_id'}
    <tr>
        <td>&nbsp;</td>
        <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="topic_load_{$load->getId()}_new" object=$section}</td>
        <td>{sf_submit bean=$bean element="topic_load_{$load->getId()}_new"}</td>
        <td>&nbsp;</td>
        <td colspan="2">{sf_input bean=$bean model=$newTopic element="topic_load_{$load->getId()}_new" attribute='title' class='span12'}</td>
        <td>{sf_input bean=$bean model=$newTopic element="topic_load_{$load->getId()}_new" attribute='value' class='span12'}</td>
    </tr>
{/sf_showIfEditable}
{/sf_showIfVisible}