[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
# Laravel пакет для роботи з Київстар Open Telecom API

Робота з SMS:

```php
/** 
 * Відправити SMS
 * 
 * @param string $to - номер отримувача
 * @param string $text - повідомлення
 * @returns string $msgId - ідентифікатор відправленого SMS 
 */
app(KyivstarApi::class)->Sms()->send('+380670000202', 'message text')

/** 
 * Перевірити статус відправки SMS
 * 
 * @param string $msgId - ідентифікатор відправленого SMS 
 * @returns string $status - accepted|delivered|seen
 */
app(KyivstarApi::class)->Sms()->status($msgId);
```

## Офіційна документація:
https://api-gateway.kyivstar.ua/#overview

## Changelog

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
