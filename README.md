# Overview

This bundle offers a consistent way for pieces of code to hook into a daily
CRON job for the purposes of data cleanup.

# Installation

Update `composer.json` by adding this to the `repositories` array:

```json
{
    "type": "vcs",
    "url": "https://github.com/ohmediaorg/cleanup-bundle"
}
```

Then run `composer require ohmediaorg/cleanup-bundle:dev-main`.

Create the daily CRON job:

```bash
0 0 * * * /path/to/php /path/to/symfony/bin/console ohmedia:cleanup
```

# Leverage the Daily CRON Job

Your service should implement `CleanerInterface`. All your dependancies can be
injected as usual via the `__construct()` function. (You may need to explicitly
provide `arguments` to your service definition.)

```php
<?php

namespace App\Cleanup;

use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManager;
use OHMedia\CleanupBundle\Interfaces\CleanerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlogPostCleaner implements CleanerInterface
{
    public function __construct(
        private BlogPostRepository $blogPostRepository
    ) {
        $this->blogPostRepository = $blogPostRepository;
    }

    public function __invoke(OutputInterface $output): void
    {
        $blogPosts = $this->blogPostRepository->getOldBlogPosts();

        foreach ($blogPosts as $blogPost) {
            $this->blogPostRepository->remove($blogPost, true);
        }

        $this->em->flush();

        // (optionally) give some feeback via the output interface
        $output->writeln('Blog posts deleted');
    }
}
```
