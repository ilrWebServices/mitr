# MITR (Minimal ILR Task Runner)

This repo contains a minimal Drupal install and miscellaneous scripts for various small ILR web and compute tasks. It runs as a development project on platform.sh, which means that there is no official URL for the site.

## Tasks

### CollegeNet to Salesforce importer

This site runs a CollegeNet to Salesforce importer for the eCornell Salesforce instance for EMHRM applicants. This is mainly configured in the `config/sync/collegenet2sf.settings.yml` file and runs via a cron command in `.platform.app.yaml`.

## Drupal maintenance

To update Drupal core:

```
$ composer update drupal/core 'drupal/core-*' --with-all-dependencies
```
