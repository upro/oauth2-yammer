# Contributing

Contributions are **welcome** and will be fully **credited**.

We accept contributions via Pull Requests on [Github](https://github.com/upro/oauth2-yammer).

## Pull Request Guidelines

Before you submit a pull request, check that it meets these guidelines:

1. The pull request should include **tests**.
2. Follow our coding standards, as defined by the [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) coding style guide. [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) will be ran against all contributions to ensure that code follows this standard.
3. Commit your changes and push your branch to GitHub with [GitFlow](http://nvie.com/posts/a-successful-git-branching-model/).

## Running tests

``` bash
$ ./vendor/bin/phpunit
```

### Running PHP Code Sniffer

``` bash
$ ./vendor/bin/phpcs src --standard=psr2
```

## Reporting Bugs

Report bugs at https://github.com/upro/oauth2-yammer/issues.

If you are reporting a bug, please include:

* Your operating system name and version.
* Any details about your local setup that might be helpful in troubleshooting.
* Detailed steps to reproduce the bug.
