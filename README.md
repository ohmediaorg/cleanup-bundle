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

Create the daily CRON job:

```bash
0 0 * * * /path/to/php /path/to/symfony/bin/console ohmedia:cleanup
```

# Leverage the Daily CRON Job

Each thing that needs to be cleaned up can be done so via a service tagged with
`ohmedia_cleanup.cleaner`:

```yaml
services:
    mybundle.cleaner:
        class: App\Cleanup\BlogPostCleaner
        tags:
            - { name: ohmedia_cleanup.cleaner }
```

Your service should implement the magic function `__invoke()` with no
parameters. All your dependancies can be injected as usual via the
`__construct()` function (you may need to provide `arguments` to your service
definition).

```php
<?php

namespace App\Cleanup;

use App\Repository\BlogPostRepository;

class BlogPostCleaner
{
    private $blogPostRepository;
    private $em;
    
    public function __construct(
        BlogPostRepository $blogPostRepository,
        EntityManager $em
    )
    {
        $this->blogPostRepository = $blogPostRepository;
        $this->em = $em;
    }
    
    public function __invoke(): void
    {
        $blogPosts = $this->blogPostRepository->getOldBlogPosts();
        
        foreach ($blogPosts as $blogPost) {
            $this->em->remove($blogPost);
        }
        
        $this->em->flush();
    }
}
```
