# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
This project adheres to [Semantic Versioning](http://semver.org/) as of v1.8.0.

## [5.0.0](https://github.com/live627/smf-custom-forms/compare/v4.0.3...v5.0.0) (2023-04-04)


### ⚠ BREAKING CHANGES

* Field descriptions no longer accept raw HTML as part of the security fix (BBC accepted instead)
* Requires PHP 8 or newer
* Drop support for SMF 2.0.x
* Require PHP 7.4

### Features

* Ability to directly upgrade ([efb3b57](https://github.com/live627/smf-custom-forms/commit/efb3b571ae3963006e3ba24dc86c07a169a480c7))
* Add meta descriptions to forms for SERPs ([7cc30b4](https://github.com/live627/smf-custom-forms/commit/7cc30b47372991fa54d9c9fa81dcf62e487b2b5e))
* Add support for SMF 2.1 ([3a7f063](https://github.com/live627/smf-custom-forms/commit/3a7f063a6d4ea1eb130827aab977f98638994f6e))
* Add update notice for admins ([8d88ff6](https://github.com/live627/smf-custom-forms/commit/8d88ff6dc3c107e053dd98eca7a81df6cc516f46))
* Allow whitespace in template vars ([18297e7](https://github.com/live627/smf-custom-forms/commit/18297e738706b33cb6df6bcb8c8d5ff719cea41c))
* Build linktree in forms ([03ad761](https://github.com/live627/smf-custom-forms/commit/03ad7618c7d5a7abff9233d6633acc4d7fd44c7f))
* Check session when users submit forms ([bf7139a](https://github.com/live627/smf-custom-forms/commit/bf7139acba01a059108923c75bd7dfda3da9f04c))
* Finally uses integration functions instead of file edits ([b76ed2f](https://github.com/live627/smf-custom-forms/commit/b76ed2f64c5b387a121fc1ac1c384cf09ce92201))
* Form edit box now includes a button to add field macros ([445473d](https://github.com/live627/smf-custom-forms/commit/445473de818f7fd55aac44e67965e3d26c8c6dc5))
* Form grid is now responsive ([334ade3](https://github.com/live627/smf-custom-forms/commit/334ade3848b38de8e9bf4146781c1b7974eb3292))
* integrate_customform_classlist ([bd054cb](https://github.com/live627/smf-custom-forms/commit/bd054cbc81ae69d8e0b697c33e54a08eeb854f73))
* Make target board selection a list ([4926bf6](https://github.com/live627/smf-custom-forms/commit/4926bf61799a222d1b843933d16f72063ead006b))
* Make the input box for the field text a textarea ([2690700](https://github.com/live627/smf-custom-forms/commit/269070036bce87d2a4b16ab27faf5fe20620fcb9))
* Request search engines to not index the thank you page ([f55392f](https://github.com/live627/smf-custom-forms/commit/f55392f1c03868a63e533220103cf64b1c80304a))
* Show a list of errors if a form is submitted with invalid data ([facb90a](https://github.com/live627/smf-custom-forms/commit/facb90ab77ade2a90ad58f439308753c1a034e12))
* Show form activity in the who's online page ([76ca0ea](https://github.com/live627/smf-custom-forms/commit/76ca0ea98da05cb55f720f8333ad47b941f1952b))


### Bug Fixes

* Actually show help text when clicking on icon ([dfc59fa](https://github.com/live627/smf-custom-forms/commit/dfc59fae66275264f240ba9984fd7a2abe05533b))
* AssertionError ([d76de71](https://github.com/live627/smf-custom-forms/commit/d76de7128ad00f5f4b2fa189bc1cca45a6e8e952))
* Be sure to post the values from large text ([7887555](https://github.com/live627/smf-custom-forms/commit/7887555082cf057a8b8c00b4459e071468f3916c))
* Checkbox validation didn't work at all ([8aecc9e](https://github.com/live627/smf-custom-forms/commit/8aecc9e4c0bc84dc854e0d5590104e4d1a6b4c9f))
* Do not define a default value for BLOB/TEXT column 'output' ([4a4f8f7](https://github.com/live627/smf-custom-forms/commit/4a4f8f7e55ffae5bb54118391a87b826698ca324))
* Do not define a hook for integrate_menu_buttons that leads to nowhere ([4b310da](https://github.com/live627/smf-custom-forms/commit/4b310da0c8f57689f88cbb1086831ab73e40c4a2))
* Don't write the global $board because it confuses SMF into looking up undefined variables (parent_boards) ([3c074ab](https://github.com/live627/smf-custom-forms/commit/3c074ab46d3ebd1277d2c3951e1d04ef3fedc446))
* End usage of the deprecated function `create_function()` ([a764537](https://github.com/live627/smf-custom-forms/commit/a764537daea4e4d254931c2e6f12ce2f337bb57c))
* Hook order ([b3e6fa6](https://github.com/live627/smf-custom-forms/commit/b3e6fa61e011694e57b5963aff1f9e45349d9e3a))
* Load language file for the Who's Online page ([81ab2b0](https://github.com/live627/smf-custom-forms/commit/81ab2b081dcbf2cfbdbe03e4a5a8179be38f5310))
* Make info fields show correctly ([cfdb62c](https://github.com/live627/smf-custom-forms/commit/cfdb62c8e96b32195bf35f20efd4a0c137527a85))
* Make select boxes validate if no value provided and default is set ([05605a2](https://github.com/live627/smf-custom-forms/commit/05605a217d0adee02c1c3efd103935e9a8090383))
* Naming mixup on Who's Online page ([2acb35b](https://github.com/live627/smf-custom-forms/commit/2acb35bfde331a115774e4ce4cbd0e555c8fd5eb))
* Naming mixup on Whv's Online page ([bb65db8](https://github.com/live627/smf-custom-forms/commit/bb65db8df84100b684ca61c379a41cdf8b81211d))
* Null fields cause problems when used in string functions ([a464946](https://github.com/live627/smf-custom-forms/commit/a464946176dbd23161801c3f272dded2b9237eb5))
* Parse error ([dfd9bd0](https://github.com/live627/smf-custom-forms/commit/dfd9bd0405f7423e14eece9f0baa97ad01f0ab5f))
* Parse error ([9517398](https://github.com/live627/smf-custom-forms/commit/9517398e46ce0470b0a24e7400897c75ffe3d3f6))
* Populate field types when editing or adding fields ([2d4e575](https://github.com/live627/smf-custom-forms/commit/2d4e5752407272c10fdb11d3cb78d0b99f5ff750))
* Radio button values needed to be set in HTML ([5fc912d](https://github.com/live627/smf-custom-forms/commit/5fc912da3d6bc92531d46a330cdfa87e4ae0515f))
* Select correct board value for dropdown ([ef9d571](https://github.com/live627/smf-custom-forms/commit/ef9d5712c1dd1fed469db506ed4ebf33f012022c))
* Send correct field names when submitting a form ([0542987](https://github.com/live627/smf-custom-forms/commit/0542987c58230692d1e7ad495a0d9c2ab670abcf))
* Show missing description on form list ([cca43c8](https://github.com/live627/smf-custom-forms/commit/cca43c81966cdc1881d10d52948e29446a1125a3))
* Solved PHP fatal error (Redefinition of parameter `$nul` ([5a48748](https://github.com/live627/smf-custom-forms/commit/5a48748746e704a73ed7d974b26f55ab74e86d87))
* Textual database fields now default to null ([183eb35](https://github.com/live627/smf-custom-forms/commit/183eb351bbc8ca33810185afad59755abff3be64))
* Thankyou page was blank and generated errors ([a0333a4](https://github.com/live627/smf-custom-forms/commit/a0333a4384331b09ff22ea947ea54afe976488e8))
* Tighten security by encoding HTML special characters ([430f1f7](https://github.com/live627/smf-custom-forms/commit/430f1f7b35583d35a0ba02ccd275ca24781f70c4))
* Translate old field names so they can actually be used ([867076e](https://github.com/live627/smf-custom-forms/commit/867076ef5450fe18252274f9cda1578e7cf00f86))
* Typo in the help text for the tittle setting ([4943ec7](https://github.com/live627/smf-custom-forms/commit/4943ec71da021001e9ab11f561f4530c11a975b9))
* Undefined $board ([877c242](https://github.com/live627/smf-custom-forms/commit/877c2422353f05e886a0bec738ab2b1b3a0db362))
* Undefined index ([2caecb3](https://github.com/live627/smf-custom-forms/commit/2caecb311b66ca5678e36ae3ef05afa84f772f6c))
* Undefined variable $true ([fd61441](https://github.com/live627/smf-custom-forms/commit/fd614414947648cf2ea6da49b5f857a2a616d956))
* Version check should replace placeholders ([9a78c4c](https://github.com/live627/smf-custom-forms/commit/9a78c4cc6427e57e04e230f91ec3fbd74abd5a5a))


### Code Refactoring

* Drop support for SMF 2.0.x ([867edf1](https://github.com/live627/smf-custom-forms/commit/867edf1bf1cdae7c1e250b6748856e439d74b8a5))
* move to namespace classes ([3289cd8](https://github.com/live627/smf-custom-forms/commit/3289cd80e925a338b33fb3313d141a672a6b0f59))
* Require PHP 7.4 ([4abc062](https://github.com/live627/smf-custom-forms/commit/4abc0626992605187fde7e148c28bfdf62587d2d))


### Miscellaneous Chores

* **master:** release 4.0.0 ([#30](https://github.com/live627/smf-custom-forms/issues/30)) ([cf3ee13](https://github.com/live627/smf-custom-forms/commit/cf3ee13deff06cadd2f56496192c13d86a831357))
* **master:** release 4.0.1 ([#32](https://github.com/live627/smf-custom-forms/issues/32)) ([06e91b6](https://github.com/live627/smf-custom-forms/commit/06e91b69eb83fe3a03483ac7b08ae6d69a4d4de4))
* **master:** release 4.0.2 ([#34](https://github.com/live627/smf-custom-forms/issues/34)) ([fcf9990](https://github.com/live627/smf-custom-forms/commit/fcf99902e2e7b79e61ac6cf83d315d8ec17e5324))
* **master:** release 4.0.3 ([#36](https://github.com/live627/smf-custom-forms/issues/36)) ([d2cdbfa](https://github.com/live627/smf-custom-forms/commit/d2cdbfaab160ecdef0f42fa5e39d82d603dfc4da))
* release 1.8.0 ([d189a1f](https://github.com/live627/smf-custom-forms/commit/d189a1f25bc1a3b83dd791ebba7e7241737ea21b))
* release 2.0.0 ([#6](https://github.com/live627/smf-custom-forms/issues/6)) ([cfa084a](https://github.com/live627/smf-custom-forms/commit/cfa084a2db8f52377860386a519abde296a1e0e8))
* release 2.0.1 ([#9](https://github.com/live627/smf-custom-forms/issues/9)) ([f309d01](https://github.com/live627/smf-custom-forms/commit/f309d01525de5942faba2ce17db293b01c3f40f6))
* release 2.0.2 ([#13](https://github.com/live627/smf-custom-forms/issues/13)) ([3f39f0b](https://github.com/live627/smf-custom-forms/commit/3f39f0b95ef083c6f15ffa1e1a6e66a9840917de))
* release 2.0.3 ([#14](https://github.com/live627/smf-custom-forms/issues/14)) ([0a7408a](https://github.com/live627/smf-custom-forms/commit/0a7408a53d0d4fcf61278495e13e76ae33ac9bb4))
* release 2.1.0 ([#15](https://github.com/live627/smf-custom-forms/issues/15)) ([a08f729](https://github.com/live627/smf-custom-forms/commit/a08f729f1511dbba4200a9465ded26e038bb308a))
* release 2.1.1 ([#16](https://github.com/live627/smf-custom-forms/issues/16)) ([83d42ec](https://github.com/live627/smf-custom-forms/commit/83d42ec5b46656abfb255b6ea69b910a50e3f0ac))
* release 2.2.0 ([#17](https://github.com/live627/smf-custom-forms/issues/17)) ([12173b2](https://github.com/live627/smf-custom-forms/commit/12173b25dae9a43b85c536c6c752dc5923f57284))
* release 2.2.1 ([#18](https://github.com/live627/smf-custom-forms/issues/18)) ([c4cef1a](https://github.com/live627/smf-custom-forms/commit/c4cef1acea7c5fda5e5fe09d2a4064b54662dd1b))
* release 2.2.2 ([#19](https://github.com/live627/smf-custom-forms/issues/19)) ([c938039](https://github.com/live627/smf-custom-forms/commit/c9380396e7809ecd73b68cd8e5f1f10db5536ca6))
* release 2.2.3 ([#20](https://github.com/live627/smf-custom-forms/issues/20)) ([d23f098](https://github.com/live627/smf-custom-forms/commit/d23f0984398c86e087dfdb335489c6aaec78635c))
* release 2.2.4 ([#26](https://github.com/live627/smf-custom-forms/issues/26)) ([dbf3908](https://github.com/live627/smf-custom-forms/commit/dbf39089dbbe06203cd57361461275d8af29d118))
* release 3.0.0 ([#27](https://github.com/live627/smf-custom-forms/issues/27)) ([f0e1ba9](https://github.com/live627/smf-custom-forms/commit/f0e1ba913d373e3620228412e9b33ba46c10e1d0))
* release 3.1.0 ([#28](https://github.com/live627/smf-custom-forms/issues/28)) ([f5a4052](https://github.com/live627/smf-custom-forms/commit/f5a4052a0dc377de4b59e312c49e3fe3eeb93e20))

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
