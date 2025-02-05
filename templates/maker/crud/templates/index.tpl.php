{% extends 'layout.html.twig' %}

{% block title %}<?= ucfirst($entity_twig_var_plural) ?>{% endblock %}

{% block content %}
    <header>
        <h1>{{ block('title') }}</h1>

        <ul>
            <li><twig:btn href="{{ path('app_u_new') }}" type="new" /></li>
        </ul>
    </header>

    <table class="table">
        <thead>
            <tr>
<?php foreach ($entity_fields as $field): ?>
                <th><?= ucfirst($field['fieldName']) ?></th>
<?php endforeach; ?>
                <th><!-- Actions --></th>
            </tr>
        </thead>
        <tbody>
        {% for <?= $entity_twig_var_singular ?> in <?= $entity_twig_var_plural ?> %}
            <tr>
<?php foreach ($entity_fields as $field): ?>
                <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
<?php endforeach; ?>
                <td>
                    <twig:btn href="{{ path('<?= $route_name ?>_show', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" type="show" />
                    <twig:btn href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" type="edit" modal />
                </td>
            </tr>
        {% else %}
            <tr>
                <td colspan="<?= (count($entity_fields) + 1) ?>">no records found</td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% endblock %}
