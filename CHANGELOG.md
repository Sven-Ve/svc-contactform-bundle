# Changelog

## Version v1.0.0
*Sat, 26 Jun 2021 20:57:08 +0000*
- first public version


## Version v1.0.1
*Sat, 26 Jun 2021 21:14:40 +0000*
- changed yaml config format


## Version v1.1.0
*Sun, 27 Jun 2021 07:32:38 +0000*
- include and describe scss file


## Version v1.1.1
*Sun, 27 Jun 2021 10:31:22 +0000*
- added suggestions in composer.json
- added badges in README


## Version v1.1.2
*Sun, 27 Jun 2021 21:04:07 +0000*
- added form test


## Version v1.1.3
*Mon, 28 Jun 2021 14:51:59 +0000*
- If user is logged in, email and name will be prefilled (if fields exist in entity User)


## Version v1.1.4
*Mon, 28 Jun 2021 15:45:31 +0000*
- Fix exception, if security bundle not exists


## Version v1.1.5
*Tue, 03 Aug 2021 10:42:30 +0000*
- added static code analysis (phpstan)


## Version v1.1.6
*Wed, 04 Aug 2021 08:52:19 +0000*
- removed creation of config file beacause we have a recipe now


## Version v1.2.0
*Fri, 28 Jan 2022 21:29:08 +0000*
- works with SvcUtilBundle 2.0


## Version v1.2.1
*Sun, 03 Apr 2022 15:50:03 +0000*
- Fixed phpstan errors


## Version v1.3.0
*Wed, 27 Apr 2022 15:57:27 +0000*
- ready for symfony 5.4 and 6.0


## Version v3.0.0
*Sat, 30 Apr 2022 20:01:54 +0000*
- runs only with symfony 5.4 and >6 und php8


## Version v3.0.1
*Sun, 15 May 2022 08:14:34 +0000*
- format code


## Version v3.0.2
*Sun, 15 May 2022 08:16:10 +0000*
- change render to renderForm


## Version 4.0.0
*Sun, 17 Jul 2022 19:20:38 +0000*
- build with Symfony 6.1 bundle features, runs only with symfony 6.1


## Version 4.0.1
*Thu, 21 Jul 2022 18:38:13 +0000*
- licence year update


## Version 4.1.0
*Thu, 01 Dec 2022 21:13:12 +0000*
- tested for symfony 6.2


## Version 5.0.0
*Sat, 16 Dec 2023 16:29:24 +0000*
- ready for symfony 6.4 and 7


## Version 5.0.1
*Sun, 17 Dec 2023 18:16:35 +0000*
- ready for symfony 6.4 and 7 - fixed tests


## Version 5.1.0
*Sun, 24 Dec 2023 20:56:18 +0000*
- switch to karser/karser-recaptcha3-bundle


## Version 5.1.1
*Tue, 13 Feb 2024 20:19:16 +0000*
- switch to phpunit 11


## Version 5.2.0
*Fri, 05 Jul 2024 19:54:31 +0000*
- better testing kernel, phpstan now on level 8


## Version 5.3.0
*Sun, 14 Sep 2025 09:50:14 +0000*
- breaking change, now it use php as route configuration. 
- You have to import the routes in your project manually. See docs for more information.


## Version 5.3.1
*Sun, 14 Sep 2025 14:38:17 +0000*
- Fix translation for contact form heading in Twig template


## Version 5.3.2
*Wed, 29 Oct 2025 20:39:14 +0000*
- Update ContactType form constraints to use named arguments syntax for Symfony 7.3+
- add strict types declaration.


## Version 5.4.0
*Fri, 31 Oct 2025 15:32:37 +0000*
- Update default values in the configuration; Enhance documentation and configuration examples for SvcContactformBundle: clarify required parameters, provide minimal and full configuration examples.


## Version 5.5.0
*Wed, 19 Nov 2025 15:28:42 +0000*
- Tested with svc-utilbundle 7.x too.
