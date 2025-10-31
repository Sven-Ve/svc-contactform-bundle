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
- **Controller**: `src/Controller/ContactController.php` - Handles form display and submission
- **Form Type**: `src/Form/ContactType.php` - Defines contact form structure
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
- Utility: `svc/util-bundle` (internal dependency)
- Optional: `karser/karser-recaptcha3-bundle` for CAPTCHA functionality
- CSS: Bootstrap (not included, must be provided by consuming application)

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