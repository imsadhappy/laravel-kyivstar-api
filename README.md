[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/imsadhappy/laravel-kyivstar-api.svg?style=flat-square)](https://packagist.org/packages/imsadhappy/laravel-kyivstar-api)
[![Total Downloads](https://img.shields.io/packagist/dt/imsadhappy/laravel-kyivstar-api.svg?style=flat-square)](https://packagist.org/packages/imsadhappy/laravel-kyivstar-api)
# Laravel пакет для роботи з Київстар Open Telecom API

## Офіційна документація:
https://api-gateway.kyivstar.ua/#overview

## Конфіг .env:

```ini
KYIVSTAR_API_CLIENT_ID="*Ваш Client ID"
KYIVSTAR_API_CLIENT_SECRET="*Ваш Client Secret"
KYIVSTAR_API_VERSION="Необов'язково, буде використана остання доступна"
KYIVSTAR_API_SERVER="Необов'язково, mock, sandbox, або production"
KYIVSTAR_API_ALPHA_NAME="Необов'язково, можна передати параметром в сервіс"
```

## Робота з SMS:

```php
/** 
 * Відправити SMS
 * 
 * @param string $to - номер отримувача
 * @param string $text - повідомлення
 * @returns string $msgId - ідентифікатор відправленого SMS 
 */
app(KyivstarApi::class)->Sms()->send('+380670000202', 'message text');

/** 
 * Перевірити статус відправки SMS
 * 
 * @param string $msgId - ідентифікатор відправленого SMS 
 * @returns string $status - accepted|delivered|seen
 */
app(KyivstarApi::class)->Sms()->status($msgId);
```

## Changelog

#### Version 0.1.5
- added feature tests: AuthenticationServiceTest
- refactoring: TestCase, ConfigValidatorTest, HasAlphaNameTest, HttpValidatorTest
- made JsonHttpService & AuthenticationService future-proof in case of endpoint changes

#### Version 0.1.4
- added 404 NotFoundHttpException to HttpValidator and respective test (+ minor refactoring of test)

#### Version 0.1.3
- added unit tests (for DTOs & traits): ConfigValidatorTest, HasAlphaNameTest, HttpValidatorTest, ObjectToArrayTest, ValueValidatorTest, SmsTest, ViberPromotionTest
- added supportedVersions list & exception codes to ConfigValidator trait

#### Version 0.1.2
- refactoring: AuthenticationService

#### Version 0.1.1
- refactoring: exposed get|post|put in JsonHttpService instead of try

#### Version 0.1.0
- alpha release

#### Version 0.0.4
- new traits: HttpValidator (for JsonHttpService & AuthenticationService) & ConfigValidator
- moved isValidConfig from ValueValidator to ConfigValidator
- removed excessive use declarations 

#### Version 0.0.3
- added traits: ObjectToArray (for Message & Viber/ContentExtended DTOs)
- minor refactoring

#### Version 0.0.2
- AuthenticationService nolonger extends HttpService
- DTOs props array removed in favor of direct properties
- added config validation
- new method: ValueValidator:isValidConfig
- new exceptions: ConfigException, ValueIsNotAllowedException
- renamed: HttpService -> JsonHttpService, config.php -> kyivstar-api.php
- removed: PropsIterator

#### Version 0.0.1
- initial commit: Facade, ServiceProvider
- new services: HttpService, AuthenticationService, SmsService, ViberService
- new traits: HasAlphaName, PropsIterator, ValueValidator
- new DTOs: Message, Sms, Viber/Transaction, Viber/Promotion, Viber/ContentExtended
- new exceptions: ValueException, ValueIsEmptyException, ValueNotUrlException, ValueTooLongException, ValueTooShortException, ValueNotBetweenException
