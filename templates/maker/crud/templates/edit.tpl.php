{% extends 'layout.html.twig' %}

{% block title %}{{ <?= $entity_twig_var_singular ?> }}{% endblock %}

{% block content %}
    <header>
        <h1>{{ block('title') }}</h1>

        {% if not turbo.requestFromModal %}
        <ul>
            <li><twig:btn href="{{ path('<?= $route_name ?>_index') }}" type="back" /></li>
            <li>{{ include('@KibaticUX/_delete_form.html.twig', {'route': '<?= $route_name ?>_delete', 'entity': <?= $entity_twig_var_singular ?>}) }}</li>
        </ul>
        {% endif %}
    </header>

    <section>
        {{ include('<?= $templates_path ?>/_form.html.twig', {'button_label': 'Update'}) }}
    </section>
{% endblock %}
