[center][b][color=blue][size=14pt]Custom Form Mod Version 1.7[/size][/color][/b][/center] 
[center]By [b][url=http://custom.simplemachines.org/mods/index.php?action=profile;u=150164]Nathaniel[/url][/b] and [b][url=http://custom.simplemachines.org/mods/index.php?action=profile;u=60251]Garou[/url][/b][/center]
[hr]
[center][url=http://www.simplemachines.org/community/index.php?topic=248871.0]Support topic[/url] | [url=http://custom.simplemachines.org/mods/index.php?mod=1279]Link to Mod[/url] | [url=https://sites.google.com/a/balancegames.org/smf/mods/forms/tutorial]Tutorial[/url][/center]
[hr]
[color=red][size=14pt]Overview:[/size][/color]
This Mod allows you to create custom forms for your forum which your users can access at "index.php?action=form", these forms are essentially a structured way of allowing your users to submit posts to certain boards, meaning that you can get them to enter certain information into a form and then you can chose how you want that information to be presented within the final outputted post.

You can access a list of your forms and the links to the actual forms themselves by going to "index.php?action=form".

Possible uses for this Mod could include, Support Forms, Staff Application Forms, Error Reporting Forms and etc.

[b][u]Settings are found in the Admin panel:[/u][/b]
[b]For SMF 1.1.x: [/b]"Features and Options" ->"Custom Forms" or index.php?action=featuresettings;sa=customform;

[b]For SMF 2.x: [/b]"Configuration"-> "Modifications" -> "Custom Form"  or index.php?action=admin;area=modsettings;sa=customform

[color=red][size=14pt]Features Explained:[/size][/color]

[u][b]Creating/Editing Forms:[/b][/u]
This is done in the settings area for this mod, you just need to create a new form by clicking on "Add New Form", and then set up the information for that form making sure that you have entered in the relevant data for each setting, you will also need to add some fields to the form, otherwise it will be ignored.

[u][b]Viewing a list of forms that you can post:[/b][/u]
This is done at the '"form" action "index.php?action=form", you will be able to view a list of forms that you can view and post, as well as a link to the board they belong to and etc.

[u][b]Posting a form:[/b][/u]
This is done at the '"form" action "index.php?action=form;n=#", Replace # with the actual number of the form. Once the user has filled out the form and saved/submitted, the results will be posted to the appropriate board. You can change the look of this page by creating a Custom Template Function.

[u][b]Custom Template Functions:[/b][/u]
This is a feature allows you to create your own custom template function for each form, to do this we suggest that you make a duplicate of the "form_template_example()" function within the 'CustomForm.template.php' file. You can then use the documentation from that function to see how information is passed to it by the Mod, allowing you to change it for your purposes.

Please remember that you have to name the new template function in this format "form_template_{Custom Template Name}", and you will have to put the correct value from "{Custom Template Name}" into the "Custom Template Function" setting for the form that you wish to use you new template. Further explanation for custom templates can be found in the 'CustomForm.template.php'.

Three Custom Templates are included with the mod; example, left, and center. Enter the appropriate name into the "Custom Template Function" field, click save, then go to your form and see how the look of your form changes.

[b][u]CAPTCHA Visual Verification:[/u][/b]
Visual verification often referred to as CAPTCHA and used by SMF for registration and posting, is included in the mod to help prevent against bots from using forms as a way to post your forum. Visual verification requires that the user type letters or digits from a distorted image that appears on the screen in order for a form to submit correctly.

By default all forms require visual verification when being filled out by guests and can not be turned off. Alternatively registered members will also be required to use visual verification based on existing settings in SMF.

To access verification settings in for [b]SMF 1.1.x:[/b] go to Admin => Registration => Settings or index.php?action=regcenter;sa=settings

To access verification settings in for [b]SMF 2.x:[/b] go to Admin => Configuration => Security and Moderation => Anti-Spam or ?action=admin;area=securitysettings;sa=spam.

Further explanation of a settings functionality can be found by clicking on the [?] help button next to each setting.

[color=red][size=14pt]Tutorial:[/size][/color]

Balance Games has provided a step by step tutorial on how to use the Custom Form Mod.
http://smf.balancegames.org/mods/forms/tutorial

[color=red][size=14pt]Compatibility:[/size][/color]

While it is highly recommended that you update to the most current versions of SMF... 
[b]CustomFormMod_v1.7_SMF1.1.x:[/b] Supports most versions of SMF 1.1.
[b]CustomFormMod_v1.7_SMF2.x:[/b] Supports most SMF 2.0 versions after 2.0 RC2.

[color=red][size=14pt]Version Changes:[/size][/color]
[b]V1.7:[/b]
Updated: Due to conflicts with mod_security, the url syntax "index.php?action=form;id=#" has been changed to, "index.php?action=form;n=#" Thanks to [url=http://www.simplemachines.org/community/index.php?action=profile;u=17752]Galatea[/url] and [url=http://www.simplemachines.org/community/index.php?action=profile;u=265135]Arantor[/url] for the fix.
Added: All posts created by a form are now attributed to a users post count. Unfortunately there is no way for a form not to be counted, however this one fix also fixes several other bugs. Thanks to [url=http://www.simplemachines.org/community/index.php?action=profile;u=214356]????????[/url] and [url=http://www.simplemachines.org/community/index.php?action=profile;u=94593]FragaCampos[/url].
Bugfix: Posts created by the form should now properly display under the "Show Posts" and "Board Notifications" sections of SMF.
Added: Selection and Radio boxes can now use the required option. When using the required option for a selection box, be sure to use "required" the first selection. Thanks to [url=http://www.simplemachines.org/community/index.php?action=profile;u=61036]Tunga[/url].
Added: an option to hide sections of the "Form Output". If a form field is not required and the user decides not to enter anything in the related field. Example: {My name is {name}}. The hidden sections will be replaced with a blank line. Thanks to  [url=http://www.simplemachines.org/community/index.php?action=profile;u=168713]alfzer0[/url]
Added: two new custom templates. "left" displays the input boxes on the left. "center" displays the description text above the input boxes, centered on the screen.
Revised: the Custom Template "example" to include features from the last several updates, as well as highlighting the example text in red. 
Revised: the default template, the * for required fields now display on the right hand side of the input boxes rather then below them.
Added: a standard thank you page that can be used as an optional "Submit Redirect" option.
Updated: Documentation
Removed: compatibility for SMF 2.0 versions prior to RC2
[b]V1.6:[/b]
Bugfix: users can now use $ in thier answers, thanks to Ingolme from W3 Schools
Bugfix: check boxes that are required, now require properly, thanks to [url=http://www.simplemachines.org/community/index.php?action=profile;u=74528]nathan42100[/url]
Added: CAPTCHA visual verification code to be displayed on forms to help prevent against bots filling out the forms.
Added: a Submit Redirect field added to the form settings which allows the admin to choose where a form will redirect after submitting.
Added: the ability for the admin to choose the Message icon that displays in the forum post.
[b]V1.5:[/b]
Replaced: Board Id entries in the admin settings with a select box containing the actual boards on your forum.
Added: New Form Type Info Box.
Added: New Form Type Radio Box. Thanks to [url=http://www.simplemachines.org/community/index.php?action=profile;u=181176]mang[/url] for supplying the code to make this possible.
[b]V1.4:[/b]
Updated: Documentation
Some minor bugfixes.
[b]V1.3:[/b]
Added SMF 1.1.8 version.
Added SMF 2.0 RC1 version.
Some minor bugfixes.
Garou added as an additional author.
[b]v1.2:[/b]
Added SMF 1.1.6 version.
Added SMF 2.0 Beta 3 version.
[b]v1.1:[/b]
Added SMF 1.1.5 version.
Added the new custom template stuff.
Some minor bugfixes.
[b]v1.0:[/b]
Original Mod release. 