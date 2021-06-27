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