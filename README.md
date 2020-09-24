# Hipertintorero.com en DrupalCommerce 2.x

Use [Composer](https://getcomposer.org/) to get Drupal + Commerce 2.x with all dependencies.

Based on [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).

## Usage



After that you can create the project:

```
composer create-project drupalcommerce/project-base some-dir --stability dev --no-interaction
```

Done! Use `composer require ...` to download additional modules and themes:

```
cd some-dir
composer require "drupal/devel:1.x-dev"
```

The `composer create-project` command passes ownership of all files to the
project that is created. You should create a new git repository, and commit
all files not excluded by the .gitignore file.
