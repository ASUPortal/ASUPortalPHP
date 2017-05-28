<tr>
    <td valign="top" width="80%">
        <p align="center">ИНДИВИДУАЛЬНЫЙ УЧЕБНЫЙ ПЛАН</p>
        <p align="center">подготовки {if $corriculum->qualification !== null}{$corriculum->qualification->getValue()}а{/if}</p>
    </td>
    <td valign="top">
        <p>УТВЕРЖДАЮ</p>
    </td>
</tr>
<tr>
    <td valign="top">
        <p>Направление: {if $corriculum->direction !== null}{$corriculum->direction->getValue()}{/if}</p>
        <p>Профиль: {if $corriculum->profile !== null}{$corriculum->profile->getValue()}{/if}</p>
    </td>
    <td valign="top">
        <p>Квалификация выпускника: {if $corriculum->qualification !== null}{$corriculum->qualification->getValue()}{/if}</p>
        <p>Срок обучения: {$corriculum->duration}</p>
        <p>Форма обучения: {if $corriculum->educationForm !== null}{$corriculum->educationForm->getValue()}{/if}</p>
        <p>Базовое образование: {$corriculum->basic_education}</p>
    </td>
</tr>