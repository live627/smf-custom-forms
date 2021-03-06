[center][b][color=blue][size=14pt]Custom Form Mod Version 2.0.1[/size][/color][/b][/center] 
[center]By [b][url=http://custom.simplemachines.org/mods/index.php?action=profile;u=150164]Nathaniel[/url][/b] and [b][url=http://custom.simplemachines.org/mods/index.php?action=profile;u=60251]Garou[/url][/b][/center]
[hr]
[center][url=http://www.simplemachines.org/community/index.php?topic=248871.0]Support topic[/url] | [url=http://custom.simplemachines.org/mods/index.php?mod=1279]Link to Mod[/url][/center]
[hr]
[center][url=http://opensource.org/licenses/MIT][img]https://camo.githubusercontent.com/d7b0ca6383644d5ac81e234f8d2249b731a1407b/687474703a2f2f696d672e736869656c64732e696f2f62616467652f6c6963656e73652d4d49542d3030393939392e737667[/img][/url] [url=https://www.paypal.me/JohnRayes][img]https://camo.githubusercontent.com/e03e24ac37094afa6d1d089fc32de8027e9b4988/687474703a2f2f696d672e736869656c64732e696f2f62616467652f50617950616c2d242d3030393936362e737667[/img][/url]
[hr][/center]
[color=red][size=14pt]Overview:[/size][/color]
This Mod allows you to create custom forms for your forum which your users can access at "index.php?action=form", these forms are essentially a structured way of allowing your users to submit posts to certain boards, meaning that you can get them to enter certain information into a form and then you can chose how you want that information to be presented within the final outputted post.

You can access a list of your forms and the links to the actual forms themselves by going to "index.php?action=form".

Possible uses for this Mod could include, Support Forms, Staff Application Forms, Error Reporting Forms and etc.

[b][u]Settings are found in the Admin panel:[/u][/b]
"Configuration"-> "Modifications" -> "Custom Form"  or index.php?action=admin;area=modsettings;sa=customform

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

To access verification settings go to Admin => Configuration => Security and Moderation => Anti-Spam or ?action=admin;area=securitysettings;sa=spam.

Further explanation of a settings functionality can be found by clicking on the [?] help button next to each setting.

[color=red][size=14pt]Tutorial:[/size][/color]

Linked below is a step by step tutorial on how to make the 'New Topic' Button re-direct to the form you wish the user to use for posting in that forum.
http://www.simplemachines.org/community/index.php?topic=248871.msg3726297#msg3726297

[color=red][size=14pt]Version Changes:[/size][/color]
https://github.com/live627/smf-custom-forms/blob/development/CHANGELOG.md
