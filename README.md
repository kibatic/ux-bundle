# Kibatic UX Bundle

## Installation

```bash
composer require kibatic/ux-bundle
yarn install --force
```

To be able to use the modal stayOnSuccess option, add this to your app.js :

```
import TurboHelper from "@kibatic/ux-bundle/dist/turbo-helper";
```

// TODO: next step should be done auto with flex

```
// assets/controllers.json
{
    "controllers": {
        // ...
        "@kibatic/ux-bundle": {
            "modal": {
                "enabled": true,
                "fetch": "eager"
            },
            "global-modal-stack": {
                "enabled": true,
                "fetch": "eager"
            },
            "global-modal-trigger": {
                "enabled": true,
                "fetch": "eager"
            },
            "alert": {
                "enabled": true,
                "fetch": "eager"
            }
        }
    }
    // ...
}
```

Then build your assets.

// TODO: document using the simpler twig components instead of using includes

# How to use
## Opening a global modal

### 1 - Link

```
<twig:a href="{{ path('my_route') }}" modal>My link</twig:a>
```
or 
```php
{% include '@KibaticUX/_turbo-modal-link.html.twig' with {
    'link_url': path('my_route', {'key': 'value'}),
    'link_text': 'My link',
    'link_class': 'nice-link'
    'frame_target_top': false,
    'close_on_success': false,
} %}
```

- `frame_target_top` : (default: `true`) Si `false`, les liens et redirection venant de la frame de  la modal seront suivies dans celle-ci.

(Plus d'infos sur les options plus bas)

### 2 - Bouton

```
<twig:btn href="{{ path('app_user_edit', {'id': 123}) }}" modal size="large" icon="edit">Edit</twig:a>
```
or
```php
{% include '@KibaticUX/_turbo-offcanvas-btn.html.twig' with {
    'btn_url': path('app_user_edit', {'id': user.id}),
    'btn_text': 'Edit',
    'btn_icon': 'edit',
    'btn_size': 'large',
    'frame_target_top': false,
    'close_on_success': false,
} %}
```

- `btn_text'`: (default: `Show`)
- `btn_class`: (default: `btn btn-{btn_size}`)
- `btn_size`: (default: `medium`)

(Plus d'infos sur les options plus bas)

## Ouvrir une modal "locale"

Contrairement à la modal global qui est plutôt pensée pour charger une turbo-frame via une url (et surtout plusieurs en même temps), la modal locale permet de mettre une partie du html d'une page dans une modal.
Par exemple une partie d'un formulaire.

```php
<button type="button" data-bs-toggle="modal" data-bs-target="#ma-modal">Plus de préférences</button>

{% embed 'common/stimulus-modal.html.twig' with {modal_id: 'ma-modal'} %}
    {% set modal_title %}Mes préférences{% endset %}
    {% set modal_content %}
        {{ form_row(form.sports) }}
        {{ form_row(form.films) }}
        {{ form_row(form.plats) }}
    {% endset %}
{% endembed %}
```

## Le principe des frames "page-content"

Via le layout du site, englobez le contenu de toutes les pages par une turbo-frame ayant pour `id` "page-content".
Cela permet de facilement d'insérer une page dans une autre via une turbo-frame en lui donnant ce même id et l'url de la page en `src`.

C'est l'id par défaut que cherche une modal turbo-frame via l'url cible.

## Les différentes options et comportements

### 1 - Fermeture à la soumission réussie depuis la modal

Avec l'option `close_on_success` (**<u>true</u>**/false), lorsqu'un formulaire est soumis depuis une modal et que la soumission est réussie (donc pas en status 40x/50x), celle-ci se ferme automatiquement.

Si vous voulez utiliser cette option toute en excluant ce comportement sur un formulaire en particulier (exemple : formulaire de filtres d'une liste présente dans la modal), vous pouvez ajouter l'attribut `data-ignored-by-modal="true"` sur le formulaire.

### 2 - Redirection ou non à la soumission réussie (stay/follow)

Lorsqu'un canvas s'ouvre (global ou non) et contient une turbo-frame, s'il n'est pas la première modal à s'ouvrir, on considère qu'il est imbriqué (ouvert depuis une autre modal, ou "nested" dans le code).

Lorsqu'une modal globale imbriquée contenant une turbo-frame est affichée, les formulaire qu'il contient sont marqués automatiquement avec l'attribut `data-turbo-on-success="stay"`.

La première modal globale à s'ouvrir quant à elle aura l'attribut `data-turbo-on-success="follow"`.

Cet attribut a pour effet de rajouter un header HTTP `Turbo-On-Success` dans les requêtes des formulaires, ce header est ensuite pris en compte par le back pour décider si en cas de succès, la réponse sera une redirection (`follow`) où un turbo-stream contenant les éventuels flash messages (`stay`).
Ce comportement est géré par méthode `Kibatic\UX\Controller\AbstractController::render`.

### 3 - Mise à jour ciblée d'une modal depuis une autre modal

**CAS 1 : Le plus courant**

Le plus souvent, vous voudrez mettre à jour le contenu de la page principale après avoir fait une action depuis une modal.
Par exemple ici, vous êtes sur la page `/user/list`, vous voulez ouvrir une modal pour modifier l'un des utilisateurs de la liste et qu'en cas de succès du formulaire de la modal mettre à jour le bloc "page-content" pour que vos modifs soit reflété dans la liste :

```
<twig:btn
    href="{{ path('app_user_edit', {'id': user.id}) }}"
    size="large"
    icon="bi-pencil"
    modal
    :modalRelatedTurboFrames="['#custom-frame']"
>
    Edit
</twig:btn>
```
or
```php
{% include 'common/_turbo-modal-btn.html.twig' with {
    'btn_url': path('app_user_edit', {'id': user.id}),
    'btn_text': 'Edit',
    'btn_icon': 'edit',
    'related_turbo_frames': ['#page-content', '#header-user']
} %}
```

Si votre page contient plusieurs turbo frame, par exemple un dashboard avec une liste d'utilisateur, une liste de commandes, etc., vous pouvez également ajouter un selecteur plus précis que `#page-content` (qui est la valeur par défaut) comme ici avec "custom-frame", et cibler précisément la turbo frame en question.

Il existe une problématique lié à l'utilisation (expliqué plus haut) de l'id "page-content" dans le cas où vous utilisez la fonctionnalité "relatedTurboFrames".
Étant donné que les modals se base par défaut sur cet id pour isoler le contenu de l'url cible, si vous en ouvrez plusieurs et que vous souhaitez en mettre à jour une en particulier, vous n'aurez que l'id "page-content" de disponible pour cibler votre modal, or cet id va correspondre à plusieurs frame dans le page ce qui mettra donc à jour plus de chose que souhaité.
C'est en général pas très gênant mais c'est à garder en tête si vous avez un "page-content" très conteux dans le tat.

**CAS 2 : Live component**

Ce cas est le plus avancé, dans le live component de la facture, pour chaque ligne de facture on peut choisir un produit.
Ce choix se fait via un select présent dans le formulaire de l'invoice, mais du fait du fonctionnement du live component et de sa mise à jour automatique,
on ne peut pas simplement mettre le champ dans une modal et le présenter à l'utilisateur car à chaque fois que le live component se mettre à jour, il va écraser son DOM et la modal serait réinitialisée à son état fermé.
À l'avenir le live component devrait gérer de manière plus intéligente ce genre de situation où l'ont veut préserver les modifications d'une partie du DOM qui entoure un DOM qu'on veut lui maintenir à jour.
Pour contourner ce problème, le JS du composant duplique le champ select (updateProductFieldProxy) et c'est ce "proxy" qui est mis dans la modal. On peut ainsi exclure tout le dom à partir de la modal (via l'attribut "data-live-ignore").
Ainsi, le vrai champ de formulaire peut continuer à être mis à jour par le live component sans impacter la modal.
Ensuite, à chaque fois qu'il y a un refresh du live component, le JS de billing-line se charge de mettre à jour le proxy et la modal est préservée.

C'est d'autant plus utile lorsqu'un nouveau produit est ajouté depuis une modal (ouverte à partir du choix de produit).
De manière générale sur le live component de billing, on déclanche "manuellement" un refresh du live component à chaque fois qu'une modal se ferme (via `<span data-action="modal.closed@window->live#$render"></span>`).
Et comme la modal de création de produit est en "close-on-success=true", une fois le formulaire soumis avec succès, la modal se ferme.
Cela déclanche donc la mise à jour du live comonent et le vrai champ (caché) de choix de produit se retrouve donc mis à jour avec le nouveau choix du produit qui vient d'être créé, cette nouvelle option est ensuite recopiée vers le proxy (toujours grâce au JS de billing-line, comme décrit précédement).
