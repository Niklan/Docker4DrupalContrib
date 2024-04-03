# Docker4DrupalContrib

Creating a development environment for Drupal is tedious. This project is an 
attempt to make it a bit easier.

## Tools included

- [Docker4Drupal][d4d] as a foundation with a small adjustments. All 
  documentation from D4D is applicable for this project.
- [drupalrc][drupalrc] for simplified interaction with Drupal related tools from
  the CLI.

## How to use

> [!TIP]
> This workflow is intended to be used for each contrib module separately.
> This means all these steps should be repeated for each individual contrib
> module.

- Clone this repository:
  ```bash
  git clone git@github.com:Niklan/Docker4DrupalContrib.git DRUPAL_PROJECT_NAME
  ```
- Adjust `.env` file: drupal core version, project name, PHP version etc.
- Clone contrib module into `/project`:
  ```bash
  git clone git@git.drupal.org:project/DRUPAL_PROJECT_NAME.git project
  ```
- Run the containers and enjoy!
   ```bash
  docker compose up -d
  ```

**Example with `pathauto` module:**

```bash
git clone git@github.com:Niklan/Docker4DrupalContrib.git pathauto
cd pathauto
git clone git@git.drupal.org:project/pathauto.git project
sed -i 's/DRUPAL_PROJECT_NAME=/DRUPAL_PROJECT_NAME=pathauto/g' .env
sed -i 's/PROJECT_NAME=/PROJECT_NAME=pathauto/g' .env
docker compose up -d
```

## FAQ

### How to rebuild containers from scratch?

```bash
docker compose down -v
docker compose build --no-cache
docker compose up -d
```

### How to rebuild just `composer.json` file?

1. Enter to `php` container shell:
  ```bash
  docker compose exec php bash
  ```
2. Run:
  ```bash
  build-composer-json
  ```

### Hot to run tool X?

This is where [drupalrc][drupalrc] will come to rescue.

First, you have to enter into shell (note that it should `bash`, not `sh`):

```bash
docker compose exec php bash
```

Then you can use [drupalrc][drupalrc]:

- Navigate to module sources (you can type it manually instead of using env):
  ```bash
  dcd $DRUPAL_PROJECT_NAME
  ```
- PHPCS
  ```bash
  drupalcs .
  ```
- PHPUnit
  ```bash
  dunit .
  ```

[d4d]: https://github.com/wodby/docker4drupal
[drupalrc]: https://github.com/Chi-teck/drupalrc
