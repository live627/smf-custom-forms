# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased][unreleased]

##V1.7:
Updated: Due to conflicts with mod_security, the url syntax "index.php?action=form;id=#" has been changed to, "index.php?action=form;n=#" Thanks to Galatea and Arantor for the fix.
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
## v1.1:
- Added SMF 1.1.5 version.
- Added the new custom template stuff.
- Some minor bugfixes.
## v1.0:
- Original Mod release.

[unreleased]: https://github.com/live627/smf-custom-forms/compare/develop
