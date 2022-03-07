# Overview

This bundle offers functionality to leverage cleanup sending via a CRON job.

# Installation

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    OHMedia\CleanupBundle\OHMediaCleanupBundle::class => ['all' => true],
];
```

Make and run the migration:

```bash
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Create the CRON job:

```bash
* * * * * /path/to/php /path/to/symfony/bin/console ohmedia:cleanup:send
```

# Configuration

Create `config/packages/oh_media_cleanup.yml` with the following contents:

```yaml
oh_media_cleanup:
    cleanup: '-1 year' # this is the default
    from:
        cleanup: no-reply@website.com # required
        name: Website.com # required
    subject_prefix: '[WEBSITE.COM]' # optional
```

The value of `cleanup` should be a string to pass to `new DateTime()`. Cleanups
older than this DateTime will be deleted.

The values of `from.cleanup` and `from.name` will be used to create an instance of
`Util\CleanupAddress`. This value will be passed to `setFrom()` on all cleanups.

The value of `subject_prefix` will be prepended to the subject of every Cleanup.

# Creating Cleanups

Simply populate and save an Cleanup entity:

```php
use OHMedia\CleanupBundle\Entity\Cleanup;
use OHMedia\CleanupBundle\Util\CleanupAddress;
use OHMedia\CleanupBundle\Util\CleanupAttachment;

$recipient = new CleanupAddress('justin@ohmedia.ca', 'Justin Hoffman');

$formUserCleanup = new CleanupAddress($form->get('cleanup'), $form->get('name'));

$cleanup = new Cleanup();
$cleanup
    ->setSubject('Confirmation Cleanup')
    ->setTemplate($template, $params)
    ->setTo($recipient)
    ->setReplyTo($formUserCleanup)
;

$attachment = new CleanupAttachment('/absolute/path/to/file.txt', 'Notes');

$cleanup->setAttachments($attachment);

$em->persist($cleanup);
$em->flush();
```

Don't bother using `setFrom()`. The value will get overridden. You can use
`setHtml` or `setTemplate` to populate the cleanup content.

Various functions on this class are variadic (https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list).

The new Cleanup will get sent the next time CRON runs.

# Cleanup Styles

Cleanup styles need to be applied inline. Create a file called
`templates/bundles/OHMediaCleanupBundle/inline-css.html.twig`.

The contents of that file can be:

```twig
{% apply inline_css %}
    <style>
        {# here, define your CSS styles as usual #}
    </style>

    {{ html|raw }}
{% endapply %}
```

or 

```twig
{% apply inline_css(source('@styles/cleanup.css')) %}
    {{ html|raw }}
{% endapply %}
```

The path to the cleanup styles can be whatever it needs to be.

_*Note:* It's recommended to have a separate set of styles for your cleanups. These
styles should be as simple as possible. They need to work in all sorts of cleanup
programs!_
