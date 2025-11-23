# Usage

## Route Configuration

**Important:** Since version 5.3.0, routes must be manually imported in your application.

Create the route configuration file:

```yaml
# config/routes/svc_contactform.yaml
_svc_contactform:
    resource: '@SvcContactformBundle/config/routes.php'
    prefix: /svc-contactform/{_locale}  # Optional: add locale support
```

## Bundle Configuration

Configure the bundle in your configuration file.

### Minimal Configuration (Required)

```yaml
# config/packages/svc_contactform.yaml
svc_contactform:
    # Email address where contact form submissions are sent (REQUIRED)
    contact_mail: contact@example.com
```

### Full Configuration (with all options)

```yaml
# config/packages/svc_contactform.yaml
svc_contactform:
    # Email address where contact form submissions are sent (REQUIRED)
    contact_mail: contact@example.com

    # Route to redirect to after successful submission (optional, default: 'index')
    route_after_send: index

    # Enable captcha for contact form (optional, default: false)
    enable_captcha: false

    # Enable sending a copy of the contact request to sender (optional, default: true)
    enable_copy_to_me: true
```

**Important:** The `contact_mail` parameter is mandatory. The bundle will not work without it.

## CSS
You have to include bootstrap in your base template.<br />
We provide a scss file. You have to integrate (call) this file or modify the layout. Feel free to modify the layout :-)

```scss
// assets/styles/layout/_svc-contactform.scss
.svc-contactform {
  width: 100%;
  max-width: 700px;
  padding: 15px;
  margin: auto;
  border-radius: 15px;
	box-shadow: 0 5px 5px rgba(0,0,0,.4);
}
```

## Usage Options

### Standard Page Display

Integrate the contactform controller via path "svc_contact_form":

```twig
<a href="{{ path('svc_contact_form') }}" class='btn btn-sm btn-info'>Contactform</a>
```

### Modal Dialog Display

Since version 6.0.0, you can also display the contact form in a modal dialog using the Svc-Util Bundle's modal Stimulus controller.

**Requirements:**
- `svc/util-bundle` ^8.0.1 or higher
- Turbo (Hotwired Turbo) - usually included with Symfony UX

**Example Usage:**

```twig
{# Button to open the contact form in a modal dialog #}
<span
    {{ stimulus_controller('svc--util-bundle--modal', {
        'url': path('svc_contact_form_modal'),
        'title': 'Contact'|trans,
        'size': 'lg'
    }) }}
    data-action="click->svc--util-bundle--modal#show"
>
    <button type="button" class="btn btn-primary">
        {% trans %}Contact{% endtrans %}
    </button>
</span>
```

**How it works:**
- The `svc_contact_form_modal` route returns the contact form wrapped in a Turbo Frame
- The `modal` Stimulus controller from svc/util-bundle loads and displays the form in a native `<dialog>` element
- Form validation errors are displayed within the modal (the modal stays open)
- Upon successful submission, the modal closes and redirects to the configured `route_after_send`
- Supports automatic dialog size, backdrop, ESC key handling, and dark mode

**Available Modal Sizes:**
- `sm` - Small modal
- Default - Standard modal
- `lg` - Large modal (recommended for contact form)
- `xl` - Extra large modal
- `fullscreen` - Full viewport modal

**Technical Details:**
- The modal template uses Turbo Frames for seamless form validation
- HTML5 validation works in the modal
- Server-side Symfony validation errors are displayed within the modal without closing it
- The form stays in the modal until successfully submitted
