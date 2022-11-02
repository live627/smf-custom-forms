# Changelog

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
* Typo in the help text for the tittle setting ([4943ec7](https://www.github.com/live627/smf-custom-forms/commit/4943ec71da021001e9ab11f561f4530c11a975b9))

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
