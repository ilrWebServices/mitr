# MITR (Minimal ILR Task Runner)

This repo contains a minimal Drupal install and miscellaneous scripts for various small ILR web and compute tasks. It runs as a development project on platform.sh, which means that there is no official URL for the site.

## Tasks

### EMHRM

This Drupal multi-site install runs a CollegeNet to Salesforce importer for the eCornell Salesforce instance. This is mainly configured in the `config/sync/emhrm/collegenet2sf.settings.yml` file and runs via a cron command in `.platform.app.yaml`.

### ILR People Profile Feed Generator

This is a straight up PHP script at `scripts/ilr-profiles-data-pull/pull_people_data.php` that also runs via a cron command in `.platform.app.yaml`. On production, the `OUTPUT_DIR` env var (also set in `.platform.app.yaml`) is set to save the generated XML file to a public URL, and so is accessible to the feeds importer on the D7 marketing site.

`scripts/ilr-profiles-data-pull` is a git submodule, with its own [official repo](https://github.com/ilrWebServices/ilr-profile-data-pull).

## Drupal maintenance

To update Drupal core:

```
$ composer update drupal/core 'drupal/core-*' --with-all-dependencies
```
