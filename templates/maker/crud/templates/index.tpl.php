{% extends 'layout.html.twig' %}

{% block title %}<?= ucfirst($entity_twig_var_plural) ?>{% endblock %}

{% block content %}
    <header>
        <h1>{{ block('title') }}</h1>

        <ul>
            <li><twig:btn href="{{ path('<?= $route_name ?>_new') }}" type="new" /></li>
        </ul>
    </header>

    <section>
        <twig:datagrid-filters :form="form" :grid="grid" />
    </section>

    <section>
        <twig:datagrid :grid="grid" />
    </section>
{% endblock %}
