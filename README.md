# Remote Access

Configurable repository downloader & installer for Nette Framework 

## Installation

The best way to install 68publishers/package-installer is using Composer:

```bash
composer require 68publishers/package-installer
```

then you can register extension into DIC:

```yaml
extensions:
    package_installer: SixtyEightPublishers\PackageInstaller\DI\PackageInstallerExtension
```

## Configuration

```yaml
package_installer:
    paths:
        cwd: %appDir%/../ # default
        repository: temp/packages/ # default, relatively from cwd
        dist: www/packages/ # default, relatively from cwd

    packages:
        my_awesome_theme:
            url: https://github.com/user/package
            installers:
                - SixtyEightPublishers\PackageInstaller\Installer\ClearRepository
                - SixtyEightPublishers\PackageInstaller\Installer\GitClone
                - SixtyEightPublishers\PackageInstaller\Installer\ComposerInstall
                - SixtyEightPublishers\PackageInstaller\Installer\SetPermissions

        my_foo_package:
            url: https://github.com/foo/bar
            branch: dev # download specific branch
            installers:
                - SixtyEightPublishers\PackageInstaller\Installer\ClearRepository
                - SixtyEightPublishers\PackageInstaller\Installer\GitClone
                - SixtyEightPublishers\PackageInstaller\Installer\SetPermissions
```

## Contributing

Before committing any changes, don't forget to run

```bash
vendor/bin/php-cs-fixer fix --config=.php_cs.dist -v --dry-run
```

and

```bash
vendor/bin/tester ./tests
```
