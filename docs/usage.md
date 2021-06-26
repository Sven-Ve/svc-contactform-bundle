# Usage

## Route
adapt the default url prefix in config/routes/svc_contactform.yaml and enable translation (if you like it)

```yaml
# config/routes/svc_contactform.yaml
_svc_contactform:
    resource: '@SvcContactformBundle/src/Resources/config/routes.xml'
    prefix: /svc-contactform/{_locale}
```

## Config

* Configure in /config/packages/svc_contactform.yaml
  * enable captcha (if installed and configured), default = false
  * send mail address
  * define a route for redirecting after send the mail


```yaml
# config/packages/svc_contactform.yaml
svc_contactform:
    contact_form:
        # Enable captcha for contact form?
        enable_captcha:      false
        # Enable sending a copy of the contact request to me too?
        enable_copy_to_me:  true
        # Email adress for contact mails
        contact_mail:       test@test.com
        # Which route should by called after mail sent
        route_after_send:   index
```

## Call the controller
integrate the contactform controller via path "svc_contact_form"

```twig
<a href="{{ path('svc_contact_form') }}" class='btn btn-sm btn-info'>Contactform</a>
```