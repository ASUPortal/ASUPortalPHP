<tr>
    <th width="16">&nbsp;</th>
    <th width="16">#</th>
    <th width="16">&nbsp;</th>
    <th width="16">{sf_toggleVisible address="workplancontentloads.php" bean=$bean element="load_{$load->getId()}_themes" object=$section}</th>
    <th colspan="3">Темы</th>
</tr>
{sf_showIfVisible bean=$bean element="load_{$load->getId()}_themes"}
{foreach $load->topics as $topic}
    {sf_showByDefault bean=$bean element="topic_{$topic->getId()}"}
    {sf_showIfVisible bean=$bean element="topic_{$topic->getId()}"}
        <tr>
            <td widtd="16">&nbsp;</td>
            <td widtd="16">#</td>
            <td widtd="16">{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="topic_{$topic->getId()}" object=$section}</td>
            <td widtd="16">&nbsp;</td>
            <td colspan="2">{$topic->title}</td>
            <td colspan="2">{$topic->value}</td>
        </tr>
    {/sf_showIfVisible}
    {sf_showIfEditable bean=$bean element="topic_{$topic->getId()}"}
        <tr>
            <td>{sf_toggleEdit address='workplancontentloads.php' bean=$bean element="topic_{$topic->getId()}" object=$section}</td>
            <td colspan="6">Я тоже форма</td>
        </tr>
    {/sf_showIfEditable}
{/foreach}
{/sf_showIfVisible}