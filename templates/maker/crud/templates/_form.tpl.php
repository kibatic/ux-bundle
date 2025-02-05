{{ form_start(form, {'attr': {'class': 'crud'}}) }}
    {{ form_widget(form) }}

    <button class="btn btn-success">
        <i class="bi bi-check-circle"></i> {{ button_label|default('Enregistrer') }}
    </button>
{{ form_end(form) }}
