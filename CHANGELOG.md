# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
This project adheres to [Semantic Versioning](http://semver.org/) as of v1.8.0.

## [4.1.0](https://github.com/live627/smf-custom-forms/compare/v4.0.7...v4.1.0) (2025-11-16)


### Features

* Add both Spanish and German translations ([e6518aa](https://github.com/live627/smf-custom-forms/commit/e6518aa68e6c5386e1b6a5f95f7c572c7936cc9e))
* Show current version in admin page ([68f58d3](https://github.com/live627/smf-custom-forms/commit/68f58d3ecc506090474b6bd07861efbabb630a4e))


### Bug Fixes

* Ensure that custom template functions actually load ([93fe3e2](https://github.com/live627/smf-custom-forms/commit/93fe3e2ea0f3fb2ebc8f2f27f61ecb64353947a0))

## [4.0.7](https://github.com/live627/smf-custom-forms/compare/v4.0.6...v4.0.7) (2024-11-24)


### Bug Fixes

* New version warning incorrectly showed when using the latest ([1b980bb](https://github.com/live627/smf-custom-forms/commit/1b980bb3948de4bd6751b3b6d58c71fa705aeb8b))

## [4.0.6](https://github.com/live627/smf-custom-forms/compare/v4.0.5...v4.0.6) (2023-10-04)


### Bug Fixes

* pass backwards compatible field types to the template ([d8adf63](https://github.com/live627/smf-custom-forms/commit/d8adf6358abcc7eb13ec1a0a7f5f2d07ac8f49c0)), closes [#43](https://github.com/live627/smf-custom-forms/issues/43)

## [4.0.5](https://github.com/live627/smf-custom-forms/compare/v4.0.4...v4.0.5) (2023-05-04)


### Bug Fixes

* Add missing input for field description ([9a38c54](https://github.com/live627/smf-custom-forms/commit/9a38c5493969cbc6f458243f6b10f791e512beff)), closes [#40](https://github.com/live627/smf-custom-forms/issues/40)

## [4.0.4](https://github.com/live627/smf-custom-forms/compare/v4.0.3...v4.0.4) (2023-04-04)


### Bug Fixes

* Add upgrade instructions ([54c9574](https://github.com/live627/smf-custom-forms/commit/54c9574b277e37e72533f5bc55aa3466114bc0c8))

## [4.0.3](https://github.com/live627/smf-custom-forms/compare/v4.0.2...v4.0.3) (2023-04-04)


### Bug Fixes

* Null fields cause problems when used in string functions ([a464946](https://github.com/live627/smf-custom-forms/commit/a464946176dbd23161801c3f272dded2b9237eb5))

## [4.0.2](https://github.com/live627/smf-custom-forms/compare/v4.0.1...v4.0.2) (2023-01-12)


### Bug Fixes

* Hook order ([b3e6fa6](https://github.com/live627/smf-custom-forms/commit/b3e6fa61e011694e57b5963aff1f9e45349d9e3a))

## [4.0.1](https://github.com/live627/smf-custom-forms/compare/v4.0.0...v4.0.1) (2022-12-08)


### Bug Fixes

* Undefined index ([2caecb3](https://github.com/live627/smf-custom-forms/commit/2caecb311b66ca5678e36ae3ef05afa84f772f6c))

## [4.0.0](https://github.com/live627/smf-custom-forms/compare/v3.1.0...v4.0.0) (2022-12-08)


### ⚠ BREAKING CHANGES

* Field descriptions no longer accept raw HTML as part of the security fix (BBC accepted instead)
* Requires PHP 8 or newer

### Features

* Add update notice for admins ([8d88ff6](https://github.com/live627/smf-custom-forms/commit/8d88ff6dc3c107e053dd98eca7a81df6cc516f46))
* Form edit box now includes a button to add field macros ([445473d](https://github.com/live627/smf-custom-forms/commit/445473de818f7fd55aac44e67965e3d26c8c6dc5))
* integrate_customform_classlist ([bd054cb](https://github.com/live627/smf-custom-forms/commit/bd054cbc81ae69d8e0b697c33e54a08eeb854f73))


### Bug Fixes

* Tighten security by encoding HTML special characters ([430f1f7](https://github.com/live627/smf-custom-forms/commit/430f1f7b35583d35a0ba02ccd275ca24781f70c4))


### Code Refactoring

* move to namespace classes ([3289cd8](https://github.com/live627/smf-custom-forms/commit/3289cd80e925a338b33fb3313d141a672a6b0f59))

## [3.1.0](https://www.github.com/live627/smf-custom-forms/compare/v3.0.0...v3.1.0) (2022-11-21)


### Features

* Ability to directly upgrade ([efb3b57](https://www.github.com/live627/smf-custom-forms/commit/efb3b571ae3963006e3ba24dc86c07a169a480c7))

## [3.0.0](https://www.github.com/live627/smf-custom-forms/compare/v2.2.4...v3.0.0) (2022-11-02)


### ⚠ BREAKING CHANGES

* Drop support for SMF 2.0.x

### Features

* Add meta descriptions to forms for SERPs ([7cc30b4](https://www.github.com/live627/smf-custom-forms/commit/7cc30b47372991fa54d9c9fa81dcf62e487b2b5e))
* Build linktree in forms ([03ad761](https://www.github.com/live627/smf-custom-forms/commit/03ad7618c7d5a7abff9233d6633acc4d7fd44c7f))
* Form grid is now responsive ([334ade3](https://www.github.com/live627/smf-custom-forms/commit/334ade3848b38de8e9bf4146781c1b7974eb3292))
* Make target board selection a list ([4926bf6](https://www.github.com/live627/smf-custom-forms/commit/4926bf61799a222d1b843933d16f72063ead006b))
* Request search engines to not index the thank you page ([f55392f](https://www.github.com/live627/smf-custom-forms/commit/f55392f1c03868a63e533220103cf64b1c80304a))


### Bug Fixes

* AssertionError ([d76de71](https://www.github.com/live627/smf-custom-forms/commit/d76de7128ad00f5f4b2fa189bc1cca45a6e8e952))
* Checkbox validation didn't work at all ([8aecc9e](https://www.github.com/live627/smf-custom-forms/commit/8aecc9e4c0bc84dc854e0d5590104e4d1a6b4c9f))
* Radio button values needed to be set in HTML ([5fc912d](https://www.github.com/live627/smf-custom-forms/commit/5fc912da3d6bc92531d46a330cdfa87e4ae0515f))
* Undefined $board ([877c242](https://www.github.com/live627/smf-custom-forms/commit/877c2422353f05e886a0bec738ab2b1b3a0db362))


### Code Refactoring

* Drop support for SMF 2.0.x ([867edf1](https://www.github.com/live627/smf-custom-forms/commit/867edf1bf1cdae7c1e250b6748856e439d74b8a5))

### [2.2.4](https://www.github.com/live627/smf-custom-forms/compare/v2.2.3...v2.2.4) (2022-05-24)


### Bug Fixes

* Naming mixup on Who's Online page ([2acb35b](https://www.github.com/live627/smf-custom-forms/commit/2acb35bfde331a115774e4ce4cbd0e555c8fd5eb))

### [2.2.3](https://www.github.com/live627/smf-custom-forms/compare/v2.2.2...v2.2.3) (2022-03-20)


### Bug Fixes

* Load language file for the Who's Online page ([81ab2b0](https://www.github.com/live627/smf-custom-forms/commit/81ab2b081dcbf2cfbdbe03e4a5a8179be38f5310))
* Typo in the help text for the tttle setting ([4943ec7](https://www.github.com/live627/smf-custom-forms/commit/4943ec71da021001e9ab11f561f4530c11a975b9))

### [2.2.2](https://www.github.com/live627/smf-custom-forms/compare/v2.2.1...v2.2.2) (2022-03-19)


### Bug Fixes

* Naming mixup on Whv's Online page ([bb65db8](https://www.github.com/live627/smf-custom-forms/commit/bb65db8df84100b684ca61c379a41cdf8b81211d))

### [2.2.1](https://www.github.com/live627/smf-custom-forms/compare/v2.2.0...v2.2.1) (2022-02-28)


### Bug Fixes

* Parse error ([dfd9bd0](https://www.github.com/live627/smf-custom-forms/commit/dfd9bd0405f7423e14eece9f0baa97ad01f0ab5f))
* Show missing description on form list ([cca43c8](https://www.github.com/live627/smf-custom-forms/commit/cca43c81966cdc1881d10d52948e29446a1125a3))

## [2.2.0](https://www.github.com/live627/smf-custom-forms/compare/v2.1.1...v2.2.0) (2022-02-27)


### Features

* Make the input box for the field text a textarea ([2690700](https://www.github.com/live627/smf-custom-forms/commit/269070036bce87d2a4b16ab27faf5fe20620fcb9))
* Show form activity in the who's online page ([76ca0ea](https://www.github.com/live627/smf-custom-forms/commit/76ca0ea98da05cb55f720f8333ad47b941f1952b))


### Bug Fixes

* Actually show help text when clicking on icon ([dfc59fa](https://www.github.com/live627/smf-custom-forms/commit/dfc59fae66275264f240ba9984fd7a2abe05533b))
* Don't write the global $board because it confuses SMF into looking up undefined variables (parent_boards) ([3c074ab](https://www.github.com/live627/smf-custom-forms/commit/3c074ab46d3ebd1277d2c3951e1d04ef3fedc446))
* Parse error ([9517398](https://www.github.com/live627/smf-custom-forms/commit/9517398e46ce0470b0a24e7400897c75ffe3d3f6))
* Select correct board value for dropdown ([ef9d571](https://www.github.com/live627/smf-custom-forms/commit/ef9d5712c1dd1fed469db506ed4ebf33f012022c))
* Thankyou page was blank and generated errors ([a0333a4](https://www.github.com/live627/smf-custom-forms/commit/a0333a4384331b09ff22ea947ea54afe976488e8))
* Undefined variable $true ([fd61441](https://www.github.com/live627/smf-custom-forms/commit/fd614414947648cf2ea6da49b5f857a2a616d956))

### [2.1.1](https://www.github.com/live627/smf-custom-forms/compare/v2.1.0...v2.1.1) (2022-02-26)


### Bug Fixes

* Do not define a default value for BLOB/TEXT column 'output' ([4a4f8f7](https://www.github.com/live627/smf-custom-forms/commit/4a4f8f7e55ffae5bb54118391a87b826698ca324))
* Do not define a hook for integrate_menu_buttons that leads to nowhere ([4b310da](https://www.github.com/live627/smf-custom-forms/commit/4b310da0c8f57689f88cbb1086831ab73e40c4a2))

## [2.1.0](https://www.github.com/live627/smf-custom-forms/compare/v2.0.3...v2.1.0) (2022-02-25)


### Features

* Add support for SMF 2.1 ([3a7f063](https://www.github.com/live627/smf-custom-forms/commit/3a7f063a6d4ea1eb130827aab977f98638994f6e))

### [2.0.3](https://www.github.com/live627/smf-custom-forms/compare/v2.0.2...v2.0.3) (2021-10-20)


### Bug Fixes

* Make info fields show correctly ([cfdb62c](https://www.github.com/live627/smf-custom-forms/commit/cfdb62c8e96b32195bf35f20efd4a0c137527a85))

### [2.0.2](https://www.github.com/live627/smf-custom-forms/compare/v2.0.1...v2.0.2) (2021-10-18)


### Bug Fixes

* Be sure to post the values from large text ([7887555](https://www.github.com/live627/smf-custom-forms/commit/7887555082cf057a8b8c00b4459e071468f3916c))

### [2.0.1](https://www.github.com/live627/smf-custom-forms/compare/v2.0.0...v2.0.1) (2021-06-10)


### Bug Fixes

* Send correct field names when submitting a form ([0542987](https://www.github.com/live627/smf-custom-forms/commit/0542987c58230692d1e7ad495a0d9c2ab670abcf))
* Version check should replace placeholders ([9a78c4c](https://www.github.com/live627/smf-custom-forms/commit/9a78c4cc6427e57e04e230f91ec3fbd74abd5a5a))

## [2.0.0](https://www.github.com/live627/smf-custom-forms/compare/v1.8.0...v2.0.0) (2021-03-25)


### ⚠ BREAKING CHANGES

* Require PHP 7.4

### Features

* Allow whitespace in template vars ([18297e7](https://www.github.com/live627/smf-custom-forms/commit/18297e738706b33cb6df6bcb8c8d5ff719cea41c))
* Check session when users submit forms ([bf7139a](https://www.github.com/live627/smf-custom-forms/commit/bf7139acba01a059108923c75bd7dfda3da9f04c))
* Show a list of errors if a form is submitted with invalid data ([facb90a](https://www.github.com/live627/smf-custom-forms/commit/facb90ab77ade2a90ad58f439308753c1a034e12))


### Bug Fixes

* Make select boxes validate if no value provided and default is set ([05605a2](https://www.github.com/live627/smf-custom-forms/commit/05605a217d0adee02c1c3efd103935e9a8090383))
* Populate field types when editing or adding fields ([2d4e575](https://www.github.com/live627/smf-custom-forms/commit/2d4e5752407272c10fdb11d3cb78d0b99f5ff750))
* Translate old field names so they can actually be used ([867076e](https://www.github.com/live627/smf-custom-forms/commit/867076ef5450fe18252274f9cda1578e7cf00f86))


### Code Refactoring

* Require PHP 7.4 ([4abc062](https://www.github.com/live627/smf-custom-forms/commit/4abc0626992605187fde7e148c28bfdf62587d2d))

## 1.8.0 (2021-03-13)


### Features

* Finally uses integration functions instead of file edits ([b76ed2f](https://www.github.com/live627/smf-custom-forms/commit/b76ed2f64c5b387a121fc1ac1c384cf09ce92201))


### Bug Fixes

* End usage of the deprecated function `create_function()` ([a764537](https://www.github.com/live627/smf-custom-forms/commit/a764537daea4e4d254931c2e6f12ce2f337bb57c))
* Solved PHP fatal error (Redefinition of parameter `$nul` ([5a48748](https://www.github.com/live627/smf-custom-forms/commit/5a48748746e704a73ed7d974b26f55ab74e86d87))
* Textual database fields now default to null ([183eb35](https://www.github.com/live627/smf-custom-forms/commit/183eb351bbc8ca33810185afad59755abff3be64))

## V1.7:
- Updated: Due to conflicts with mod_security, the url syntax `index.php?action=form;id=#` has been changed to, `index.php?action=form;n=#` Thanks to Galatea and Arantor for the fix.
- Added: All posts created by a form are now attributed to a users post count. Unfortunately there is no way for a form not to be counted, however this one fix also fixes several other bugs. Thanks to ŦώεαЖзяŁ and FragaCampos.
- Bugfix: Posts created by the form should now properly display under the "Show Posts" and "Board Notifications" sections of SMF.
- Added: Selection and Radio boxes can now use the required option. When using the required option for a selection box, be sure to use "required" the first selection. Thanks to Tunga.
- Added: an option to hide sections of the "Form Output". If a form field is not required and the user decides not to enter anything in the related field. Example: {My name is {name}}. The hidden sections will be replaced with a blank line. Thanks to  alfzer0
- Added: two new custom templates. "left" displays the input boxes on the left. "center" displays the description text above the input boxes, centered on the screen.
- Revised: the Custom Template "example" to include features from the last several updates, as well as highlighting the example text in red.
- Revised: the default template, the * for required fields now display on the right hand side of the input boxes rather then below them.
- Added: a standard thank you page that can be used as an optional "Submit Redirect" option.
- Updated: Documentation
- Removed: compatibility for SMF 2.0 versions prior to RC2

## V1.6:
- Bugfix: users can now use $ in thier answers, thanks to Ingolme from W3 Schools
- Bugfix: check boxes that are required, now require properly, thanks to nathan42100
- Added: CAPTCHA visual verification code to be displayed on forms to help prevent against bots filling out the forms.
- Added: a Submit Redirect field added to the form settings which allows the admin to choose where a form will redirect after submitting.
- Added: the ability for the admin to choose the Message icon that displays in the forum post.

## V1.5:
- Replaced: Board Id entries in the admin settings with a select box containing the actual boards on your forum.
- Added: New Form Type Info Box.
- Added: New Form Type Radio Box. Thanks to mang for supplying the code to make this possible.

## V1.4:
- Updated: Documentation
- Some minor bugfixes.

## V1.3:
- Added SMF 1.1.8 version.
- Added SMF 2.0 RC1 version.
- Some minor bugfixes.
- Garou added as an additional author.

## v1.2:
- Added SMF 1.1.6 version.
- Added SMF 2.0 Beta 3 version.

## v1.1 (2008-06-22)
- Added SMF 1.1.5 version.
- Added the new custom template stuff.
- Some minor bugfixes.

## v1.0:
- Original Mod release.
