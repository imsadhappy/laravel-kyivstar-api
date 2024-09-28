[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
# Laravel пакет для роботи з Київстар Open Telecom API

## Офіційна документація:
https://api-gateway.kyivstar.ua/#overview

## Changelog

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
