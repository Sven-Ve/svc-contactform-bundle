# Modal Dialog Example

This example demonstrates how to integrate the contact form as a modal dialog in your application.

## Prerequisites

- `svc/util-bundle` ^8.0.1 or higher
- Turbo (Hotwired Turbo) - usually included with Symfony UX

## Complete Working Example

```twig
{# In your base template or any other template #}

{# Button to trigger the modal #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'lg'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">
        {% trans %}Contact Us{% endtrans %}
    </button>
</span>
```

## How It Works

1. **Stimulus Controller**: The `svc--util-bundle--modal` controller handles:
   - Creating a native `<dialog>` element dynamically
   - Fetching the form content from `svc_contact_form_modal` route
   - Displaying the dialog with proper accessibility features
   - Closing the dialog on ESC key or close button click

2. **Route**: The `svc_contact_form_modal` route returns the form wrapped in a Turbo Frame (no base template).

3. **Form Validation**:
   - HTML5 validation works normally within the modal
   - Server-side Symfony validation errors are displayed within the modal
   - The modal stays open when there are validation errors
   - Users can correct errors without the modal closing

4. **Form Submission**: When the form is submitted successfully:
   - The dialog closes automatically
   - Turbo navigates to the configured `route_after_send`
   - Success flash message is displayed on the next page

## Different Modal Sizes

You can customize the modal size:

```twig
{# Small modal #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'sm'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">Contact</button>
</span>

{# Large modal (recommended) #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'lg'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">Contact</button>
</span>

{# Extra large modal #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'xl'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">Contact</button>
</span>

{# Fullscreen modal #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'fullscreen'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">Contact</button>
</span>
```

## Navigation Bar Example

Integrate the contact form button in your navigation:

```twig
{# templates/base.html.twig #}
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        {# ... other nav items ... #}

        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <span
                    {{ stimulus_controller('svc--util-bundle--modal', {
                        'url': path('svc_contact_form_modal'),
                        'title': 'Contact'|trans,
                        'size': 'lg'
                    }) }}
                    data-action="click->svc--util-bundle--modal#show"
                >
                    <a href="#" class="nav-link">
                        {% trans %}Contact{% endtrans %}
                    </a>
                </span>
            </li>
        </ul>
    </div>
</nav>
```

## Features

- ✅ **Native Dialog**: Uses HTML5 `<dialog>` element for better performance and accessibility
- ✅ **AJAX Loading**: Form content is loaded dynamically without page reload
- ✅ **Dark Mode**: Automatically supports Bootstrap 5.3+ dark mode
- ✅ **Keyboard Navigation**: ESC key closes the dialog
- ✅ **Focus Management**: Automatic focus trap when dialog is open
- ✅ **Mobile Friendly**: Responsive design works on all screen sizes
