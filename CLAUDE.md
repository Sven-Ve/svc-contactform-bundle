# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony Bundle (`svc/contactform-bundle`) that provides a contact form with email functionality. It uses the new Bundle Configuration System (requires Symfony >=6.1) and has migrated from YAML to PHP-based configuration.

**Important**: Version 5.3.0+ uses PHP configuration files instead of YAML. Routes must be manually imported in consuming applications.

## Commands

### Testing
```bash
composer test           # Run PHPUnit tests with testdox output
vendor/bin/phpunit      # Direct PHPUnit execution
vendor/bin/phpunit --testdox tests/SpecificTest.php  # Run single test file
```

### Release
```bash
bin/release.php         # Automated release process (runs tests, phpstan, creates git tag)
```

### Static Analysis
```bash
composer phpstan        # Run PHPStan analysis (level 8)
vendor/bin/phpstan analyse -c .phpstan.neon  # Direct PHPStan execution
```

### Code Formatting
```bash
/opt/homebrew/bin/php-cs-fixer fix  # Format code using PHP-CS-Fixer
```

## Architecture

### Bundle Structure
- **Main Bundle Class**: `src/SvcContactformBundle.php` - Uses new Symfony bundle configuration system
- **Controller**: `src/Controller/ContactController.php` - Handles form display and submission (both standard page and modal)
- **Form Type**: `src/Form/ContactType.php` - Defines contact form structure
- **Templates**:
  - `templates/contact/contact.html.twig` - Standard page display with base template
  - `templates/contact/contact_modal.html.twig` - Modal version wrapped in Turbo Frame
- **Testing Kernel**: `tests/SvcContactformTestingKernel.php` - Custom kernel for testing

### Configuration

**Route Configuration (Breaking Change in 5.3.0)**:
Routes are now defined in PHP (`config/routes.php`) and must be manually imported:
```yaml
# config/routes/svc_contactform.yaml
_svc_contactform:
    resource: '@SvcContactformBundle/config/routes.php'
    prefix: /svc-contactform/{_locale}  # Optional: add locale support
```

**Available Routes**:
- `svc_contact_form` - Standard contact form page (path: `/contact/`)
- `svc_contact_form_modal` - Modal version for AJAX loading (path: `/contact/modal`)

**Bundle Configuration**:
The bundle supports these configuration options (defined in `SvcContactformBundle.php:configure()`):
- `contact_mail`: **[REQUIRED]** Email address for receiving contact forms (e.g., 'contact@example.com')
- `route_after_send`: Route to redirect to after successful submission (default: 'index')
- `enable_captcha`: Enable/disable reCAPTCHA integration (default: false)
- `enable_copy_to_me`: Send copy of form to sender (default: true)

**Minimal Configuration Example**:
```yaml
# config/packages/svc_contactform.yaml
svc_contactform:
    contact_mail: contact@example.com  # Required!
```

### Dependencies
- Core: Symfony Framework Bundle, Form, Twig Bundle, Translation
- Utility: `svc/util-bundle` ^8.0.1 (internal dependency)
- Optional: `karser/karser-recaptcha3-bundle` for CAPTCHA functionality
- CSS: Bootstrap (not included, must be provided by consuming application)
- Frontend: Turbo (Hotwired Turbo) - required for modal functionality

### Modal Dialog Feature (Version 6.0.0+)

The bundle provides two display modes:
1. **Standard Page** - Traditional full-page contact form
2. **Modal Dialog** - Contact form in a native `<dialog>` element

**Modal Implementation**:
- Uses `svc/util-bundle` modal Stimulus controller
- Form wrapped in Turbo Frame (`<turbo-frame id="modal-contact-form">`)
- Validation errors stay within modal (modal doesn't close)
- On success: modal closes, Turbo navigates to `route_after_send`
- Template: `templates/contact/contact_modal.html.twig`

**Key Technical Points**:
- HTML5 validation works in modal
- Server-side Symfony validation displays errors in modal without closing it
- Controller method `contactFormModal()` handles modal requests
- Private method `handleContactForm()` shared by both standard and modal routes

### Testing
Uses PHPUnit 12.3 with a custom testing kernel. Tests cover both the form and controller functionality. The bundle uses `phpunit.xml.dist` configuration with a custom `KERNEL_CLASS`.

### Static Analysis
PHPStan configured at level 8 with specific ignore patterns for Symfony and reCAPTCHA bundle classes that may not be available during static analysis.

## Coding Standards

### Symfony Constraints
**Important**: Use named arguments syntax for all Symfony validator constraints (required for Symfony 7.3+):

```php
// ✅ Correct - Named arguments
new NotBlank(message: 'Field cannot be empty')
new Email(message: 'Invalid email address')
new Length(
    min: 5,
    max: 200,
    minMessage: 'Must be at least {{ limit }} characters',
    maxMessage: 'Cannot exceed {{ limit }} characters'
)

// ❌ Deprecated - Array syntax (causes deprecation warnings)
new NotBlank(['message' => 'Field cannot be empty'])
new Email(['message' => 'Invalid email address'])
new Length([
    'min' => 5,
    'max' => 200,
    'minMessage' => 'Must be at least {{ limit }} characters',
    'maxMessage' => 'Cannot exceed {{ limit }} characters',
])
```

This applies to all constraints in `src/Form/ContactType.php` and any future form types.
- nicht changelog.md selbst aktualisieren. dies passiert über das release script bin/release.php. dort jeweils einen release text eintragen und die versionsnummer anpassen