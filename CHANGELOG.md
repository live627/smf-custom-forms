# Changelog

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
