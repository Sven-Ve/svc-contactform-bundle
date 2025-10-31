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

## Call the controller
integrate the contactform controller via path "svc_contact_form"

```twig
<a href="{{ path('svc_contact_form') }}" class='btn btn-sm btn-info'>Contactform</a>
```
