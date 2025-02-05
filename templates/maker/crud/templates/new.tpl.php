{% extends 'layout.html.twig' %}

{% block title %}Création d'un <?= $entity_class_name ?>{% endblock %}

{% block content %}
    <header>
        <h1>{{ block('title') }}</h1>

        {% if not turbo.requestFromModal %}
        <ul>
            <li><twig:btn href="{{ path('<?= $route_name ?>_index') }}" type="back" /></li>
        </ul>
        {% endif %}
    </header>

    <section>
        {{ include('<?= $templates_path ?>/_form.html.twig') }}
    </section>
{% endblock %}
