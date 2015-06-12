<div ng-controller="WorkPlanTermsController as wptCtrl">

<h3>4. Содержание и структура дисциплины (модуля)</h3>
<h4>4.1 Содержание разделов дисциплины</h4>

    {CHtml::activeComponent("workplancontentsections.php?plan_id={$plan->getId()}", $plan)}

<h4>4.2. Структура дисциплины</h4>

    {CHtml::activeComponent("workplanterms.php?plan_id={$plan->getId()}", $plan)}

<h4>4.3. Лабораторные работы</h4>

    {CHtml::activeComponent("workplantermlabs.php?plan_id={$plan->getId()}", $plan)}

<h4>4.4. Практические занятия (семинары)</h4>

    {CHtml::activeComponent("workplantermpractices.php?plan_id={$plan->getId()}", $plan)}

</div>