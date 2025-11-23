# Tests

This directory contains comprehensive tests for the SvcContactformBundle.

## Test Structure

```
tests/
├── Controller/
│   ├── ContactControllerTest.php  # 15 functional tests for form submission
│   └── IndexController.php        # Helper controller for redirect tests
└── SvcContactformTestingKernel.php # Custom kernel with test configuration
```

## Running Tests

```bash
# Run all tests
composer test

# Run specific test file
vendor/bin/phpunit tests/Controller/ContactControllerTest.php

# Run specific test method
vendor/bin/phpunit --filter testSubmitContactFormWithValidData

# Run all honeypot tests
vendor/bin/phpunit --filter Honeypot

# Verbose output with test descriptions
vendor/bin/phpunit --testdox
```

## Test Coverage

### 15 Tests Total (37 Assertions)

**GET Request Tests (5 tests):**
- Standard form display (English)
- Standard form display (German)
- Modal form display
- Form content verification
- Modal Turbo Frame structure

**POST Request Tests with Email Verification (7 tests):**
- ✅ Successful submission → Email sent (`assertEmailCount(1)`)
- ✅ Modal submission → Email sent + Turbo response
- ✅ Honeypot protection (standard) → No email (`assertEmailCount(0)`)
- ✅ Honeypot protection (modal) → No email + fake success
- ✅ Empty field validation → HTTP 422
- ✅ Invalid email validation → HTTP 422
- ✅ Whitespace-only validation → HTTP 422

**Feature-Specific Tests (3 tests):**
- ✅ CopyToMe checkbox → CC header in email
- ✅ CAPTCHA disabled for modal mode
- ✅ Honeypot field hidden via CSS

## Key Testing Patterns

### Form Submission

```php
// ✅ CORRECT - Use submitForm()
$crawler = $client->request('GET', '/contactform/en/contact/');
$csrfToken = $this->extractCsrfToken($crawler);
$client->submitForm('contact[Send]', $this->getValidFormData($csrfToken));

// ❌ WRONG - Don't use request('POST')
$client->request('POST', '/contactform/en/contact/', $formData);
```

### Checkbox Handling

```php
// ✅ CORRECT - Omit unchecked checkboxes
$formData = [
    'contact[name]' => 'John Doe',
    // 'contact[copyToMe]' is omitted = unchecked
];

// ❌ WRONG - Don't send '0' for unchecked
$formData = [
    'contact[copyToMe]' => '0',  // Throws exception!
];
```

### Email Assertions

```php
// Check email was sent
$this->assertEmailCount(1);

// Get email details
$email = $this->getMailerMessage();
$this->assertEmailHasHeader($email, 'To');
$this->assertEmailHeaderSame($email, 'To', 'test@example.com');

// Verify no email (honeypot)
$this->assertEmailCount(0);
```

## Test Configuration

### Mailer Setup

```php
// SvcContactformTestingKernel.php
'mailer' => [
    'dsn' => 'null://default',  // CRITICAL: Use 'default', not 'null'
],
```

### Required Dependencies

```json
{
    "require-dev": {
        "symfony/security-csrf": "^6.3|^7",
        "symfony/css-selector": "^6.3|^7"
    }
}
```

## Common Issues

### Issue: "Input 'contact[copyToMe]' cannot take '0' as a value"
**Solution:** Omit checkbox fields from `$formData` instead of sending `'0'`.

### Issue: Form re-displayed instead of redirect
**Solution:** Use `$client->submitForm()` instead of `$client->request('POST')`.

### Issue: "assertEmailCount() not found"
**Solution:** Ensure using `KernelTestCase` (not `WebTestCase`) and mailer DSN is `null://default`.

### Issue: HTTP 422 instead of 200
**Expected:** Symfony returns HTTP 422 (Unprocessable Entity) for validation errors. Tests accept both 200 and 422.

## CI/CD Integration

Tests are designed to run in CI environments:
- No external dependencies (null mailer transport)
- No network calls
- Fast execution (~0.3 seconds for all 15 tests)
- Deterministic results

```yaml
# Example GitHub Actions
- name: Run Tests
  run: composer test
```

## Debugging Tests

```bash
# Debug single test with verbose output
vendor/bin/phpunit --filter testSubmitContactFormWithValidData --debug

# Check for deprecations
vendor/bin/phpunit --display-deprecations

# Generate code coverage (requires xdebug)
XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html coverage/
```
