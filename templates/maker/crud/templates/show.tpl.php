{% extends 'layout.html.twig' %}

{% block title %}{{ <?= $entity_twig_var_singular ?> }}{% endblock %}

{% block content %}
    <header>
        <h1>{{ block('title') }}</h1>

        {% if not turbo.requestFromModal %}
        <ul>
            <li><twig:btn href="{{ path('<?= $route_name ?>_index') }}" type="back" /></li>
            <li><twig:action route="<?= $route_name ?>_delete" :entity="<?= $entity_twig_var_singular ?>" icon="bi:trash-fill" btnClass="btn btn-outline-danger" label="{{ 'Delete'|trans }}" confirm /></li>
            <li><twig:btn href="{{ path('<?= $route_name ?>_edit', {'<?= $entity_identifier ?>': <?= $entity_twig_var_singular ?>.<?= $entity_identifier ?>}) }}" type="edit" modal /></li>
        </ul>
        {% endif %}
    </header>

    <section>
        <table class="table table-show">
            <tbody>
    <?php foreach ($entity_fields as $field): ?>
                <tr>
                    <th><?= ucfirst($field['fieldName']) ?> :</th>
                    <td>{{ <?= $helper->getEntityFieldPrintCode($entity_twig_var_singular, $field) ?> }}</td>
                </tr>
    <?php endforeach; ?>
            </tbody>
        </table>
    </section>
{% endblock %}
