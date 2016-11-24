# Yammer Provider for OAuth 2.0 Client
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://img.shields.io/travis/upro/oauth2-yammer/master.svg?style=flat-square)](https://travis-ci.org/upro/oauth2-yammer)

This package provides Yammer OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

### Requirements

* PHP version 5.5 or higher.

### Installing via Composer

The preferred method of installing this library is through the [Composer](http://getcomposer.org) dependency management tool.

Add the following dependency to your `composer.json`:

```json
{
  "require": {
    "upro/oauth2-yammer": "~0.1"
  }
}
```

Or simply run the following command line to install the latest stable version:

```bash
$ composer require upro/oauth2-yammer
```

Then, require the `vendor/autoload.php` file to enable the autoloading mechanism provided by Composer. Otherwise, your application won't be able to find the classes of this library.

## Usage

Usage is the same as The League's OAuth client, using `\UPro\OAuth2\Client\Provider\Yammer` as the provider.

### Authorization Code Flow

```php
require __DIR__.'/vendor/autoload.php';

$provider = new UPro\OAuth2\Client\Provider\Yammer([
    'clientId'          => '{yammer-client-id}',
    'clientSecret'      => '{yammer-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {
    $authorizationUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getName());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

## Contributing

Please see [CONTRIBUTING](https://github.com/upro/oauth2-yammer/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Contributors](https://github.com/upro/oauth2-yammer/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/upro/oauth2-yammer/blob/master/LICENSE) for more information.
