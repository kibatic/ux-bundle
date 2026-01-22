{{ form_start(form, {'attr': {'class': 'crud'}}) }}
    {{ form_widget(form) }}

    <button class="btn btn-success">
        <twig:ux:icon name="bi:check-circle" /> {{ button_label|default('Enregistrer') }}
    </button>
{{ form_end(form) }}
