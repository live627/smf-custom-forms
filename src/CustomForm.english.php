<?php

/**
 * @package   Custom Form mod
 * @version   2.2.3
 * @author    John Rayes <live627@gmail.com>
 * @copyright Copyright (c) 2014, John Rayes
 * @license   http://opensource.org/licenses/MIT MIT
 */

global $helptxt;

//	Header and General text for the Custom Form Mod settings area.
$txt['customform_generalsettings_heading'] = 'Custom Form Mod Settings'; 
$txt['customform_tabheader'] = 'Custom Forms';
$txt['customform_form'] = 'Form';
$txt['customform_field'] = 'Field';
$txt['customform_view_title'] = 'View Forms Title';
$txt['customform_view_text'] = 'View Forms Text';
$txt['customform_view_perms'] = 'Form View Page Permissions';

//	Stuff for the forms action
$txt['customform_submit'] = 'Submit Form';
$txt['customform_required'] = 'Fields marked with an asterisk (*) are required';

//	Junk for the list areas.
$txt['customform_listheading_fields'] = 'Form Fields';
$txt['customform_add_form'] = 'Add New Form';
$txt['customform_add_field'] = 'Add New Field';
$txt['customform_edit'] = 'Edit';
$txt['customform_delete_warning'] = 'Are you sure you want to delete this?';
$txt['customform_list_noelements'] = 'This list is currently empty.';
$txt['customform_moveup'] = 'Move Up';
$txt['customform_movedown'] = 'Move Down';

//	Text for the settings pages.
$txt['customform_text'] = 'Text';
$txt['customform_type'] = 'Type';
$txt['customform_type_vars'] = 'Extra Type Parameters';
$txt['customform_output'] = 'Form Output';
$txt['customform_board_id'] = 'Board';
$txt['customform_template_function'] = 'Custom Template Function';
$txt['customform_exit'] = 'Submit Redirect';

//  Redirection Text
$txt['customform_forum'] = 'Forum';
$txt['customform_topic'] = 'Topic';
$txt['customform_board'] = 'Board (default)';
$txt['customform_thanks'] = 'Thank You';
$txt['customform_list'] = 'Form List';

//  Text for the thankyou page
$txt['customform_thankyou'] =
	'The information you entered has been submited.<br /><br />Thank you for taking the time to complete this form.<br /><br />You may now return to the Forum or view the Form List, if available.';

//	Options for the selectbox on the field edit page.
$txt['customform_type_text'] = 'Text';
$txt['customform_type_textarea'] = 'Large Text';
$txt['customform_type_select'] = 'Select Box';
$txt['customform_type_radio'] = 'Radio Buttons';
$txt['customform_type_check'] = 'Checkbox';
$txt['customform_type_info'] = 'Information';
$txt['customform_max_length'] = 'Maximum Length';
$txt['customform_max_length_desc'] = '(0 for no limit)';
$txt['customform_dimension'] = 'Dimensions';
$txt['customform_dimension_row'] = 'Rows';
$txt['customform_dimension_col'] = 'Columns';
$txt['customform_size'] = 'Maximum number of characters';
$txt['customform_bbc'] = 'Allow BBC';
$txt['customform_options'] = 'Options';
$txt['customform_options_desc'] = 'Leave option box blank to remove. Radio button selects default option.';
$txt['customform_options_more'] = 'More';
$txt['customform_default'] = 'Default State';
$txt['customform_active'] = 'Active';
$txt['customform_active_desc'] = 'This field will be turned off if unchecked.';
$txt['customform_mask'] = 'Input Mask';
$txt['customform_mask_desc'] = 'This validates the input supplied by the user.';
$txt['customform_mask_number'] = 'Whole number (Scientific notation not allowed)';
$txt['customform_mask_float'] = 'Floating point integer (Decimals allowed)';
$txt['customform_mask_email'] = 'Email (Must be shorter than 255 characters)';
$txt['customform_mask_regex'] = 'Regular Expression (Experts only!)';
$txt['customform_regex'] = 'Regex';
$txt['customform_regex_desc'] = 'Validate your own way.';

// Validation errors
$txt['customform_error_title'] = 'Oops, there were errors!';
$txt['customform_invalid_value'] = 'The value you chose for %1$s is invalid.';

// argument(s): $scripturl
$txt['whoall_form'] = 'Viewing the <a href="%s?action=form">list of forms</a>.';
// argument(s): $scripturl, $id_form, $title
$txt['customform_who'] = 'Viewing <a href="%s?action=form;n=%s">%s</a>.';

//	Help text for the general settings page.
$helptxt['customform_view_perms'] =
	'This setting allows you to restrict the member groups that can see the list of the forms which they can access (At &quot;index.php?action=form&quot;). Note that even if they can see they list, they cannot see or use any forms which they do not have the permissions for.';
$helptxt['customform_view_title'] =
	'This setting allows you to chose the title for the action/page that shows the list of forms to a user (at &quot;index.php?action=form&quot;).  By default \'Custom Forms\' will be the title.';
$helptxt['customform_view_text'] =
	'This setting allows you to chose the explanation text shown for the action/page that shows the list of forms to a user (at &quot;index.php?action=form&quot;). By default nothing will be shown.';

//	Help text for the edit form page.
$helptxt['customform_form_title'] =
	'This is the title of the current form, it will be displayed when a user is filling out the form and when they are viewing the list of forms.';
$helptxt['customform_board_id'] = 'This drop-down menu to select the board that the form will post to. ';
$helptxt['customform_icon'] =
	'This drop-down menu allows you to change the default icon for the subject line to something matching the mood or purpose of your post. ';
$helptxt['customform_permissions'] =
	'This is the permissions setting for this form, to be able to view or submit a form, a user must be in one of the member groups selected.';
$helptxt['customform_subject'] =
	'This is the subject/title of the post created by the form, like the Form Output area, it can contain values from the form itself.<br /><br />Example:<br />Form name: {field_name}';
$helptxt['customform_output'] =
	'This is the the format in which the data a users enters in the form will be displayed in the forum post after they submit the form.<br/><br/>To actually display data a user enters in the forum post you will need to enter the title for the field between braces { }.<br /><br />Example: If the field was called \'name\' then {name} would be replaced with what the user entered in the field of the form.<br />My name is {name}.<br />Then if the user were to enter &quot;Bob&quot; in the corresponding form field, the forum post would display &quot;My name is Bob&quot;.<br /><br />If Bob chose not to enter his name into the field and it was not required then your forum post would display &quot;My name is&quot; You can also choose tho hide some forum output, by adding a second set of {} to the entire section of the form.<br />Example: {My name is {name}}<br />Instead of the forum displaying &quot;My name is&quot; it would be replaced with a blank line.';
$helptxt['customform_submit_exit'] =
	'This setting allows you to select where the user is sent after the form is successfully completed.<br /><br />You can enter the following options...<br /><br /><b>board</b>: will redirect the user to the board where the form is posted (this is also the default if the field is left empty).<br /><b>forum</b>: will redirect the user to the Forum Index page.<br /><b>form</b>: will redirect the user to the list of forms available form them to fill out, if they have permission to view it.<br /><b>thanks</b>: will redirect the user to a simple page letting them know that form has been completed correctly and thanking them for completing the form.<br /><br />forum, form, and thanks, are useful if the form posts to a board the user does not have access to view.<br /><br />Alternatively you can also enter a URL like http://www.simplemachines.org and the user will be directed to that URL. This can be useful to redirect users to a custom thank you page, another specific form, a specific forum post, or anyplace else on the internet.';
$helptxt['customform_template_function'] =
	'This setting allows you to chose which template function will be used for this particular form when a user is submitting it.<br /><br />There are four included template options.<br />Leave the field blank for the default option where user input boxes are on the right of the description text or enter...<br /><br /><b>left</b> will display the input boxes to the left of the description text.<br /><b>center</b> displays input boxes in the center of the page with the description text above the input boxes.<br /> <b>example</b> displays an example of how you can create customized templates for your own uses.<br /><br /><b>Attention!</b> You only need to create custom templates to change the over all look of your form submit pages.<br /><br />Custom templates can be added to the &quot;/themes/{current_theme}/CustomForm.template.php&quot; file. Please note that the template function which will be used has to be named with the format, &quot;form_template_{value for this setting}&quot;, otherwise the default &quot;form_template_submit_form()&quot; template function will be used.<br /><br />For a brief example of what you can change in a template enter &quot;example&quot; in the Custom Template Function of a form then view that form. You will see several places with the text <span style="color:red">&quot;Example of something...&quot;</span>, these are good places for you to add information to your form template without effecting the functions of the form itself.<br /><br />If you are trying to create a new template then open the &quot;CustomForm.template.php&quot; file and find &quot;Example: How to edit the custom forms template.&quot; and follow the instructions there.<br /><br /><b><span style="color:red">Warning</span></b> - It is recommended that you have a decent knowledge of HTML, XML, and PHP before you do anything too drastic.<br /><b><span style="color:red">Improper codeing will cause the Custom Form Mod and possibly your forum to break!</span></b><br />Be sure to backup your form or at least the <b>CustomForm.template.php</b> file before making any changes';

//	Help text for the edit field page.
$helptxt['customform_field_title'] =
	'This is the title that will be used by the mod to access the user input from the form. It is not displayed in the form or the final forum post.<br /><br />Note that the &quot;title&quot; field is only seen by admins and not the users, it is used to designate where the users input is displayed in the form and when a submitted form is posted in the forum. For best results keep the titles short, all lower case, and do not use special characters like # & * @ [ etc.<br /><br />Example: name, username, and user_name will work fine. The &quot;title&quot; &quot;User Name&quot; will not work. Do not use numerals as the first character of a &quot;title&quot; question_1, q_1,or the word &quot;one&quot; will work. 1, 2, 3, 1st, 2nd, 3rd will not work.<br /><br />An incorect &quot;title&quot; will cause your form to not work properly. For instance the information the user types in the form will not be displayed in the forum post or the form may not appear to users at all.';
$helptxt['customform_text'] =
	'This setting allows you to change the text which a user will see when they are filling out the form.<br /><br />You can also use many standard HTML tags in the text field, <ins><a href="http://www.w3schools.com/html/html_quick.asp">W3 Schools</a></ins> provides some good tutorials on how to use HTML tags. Most of these are similar to the BBC tags you can use in forum posts except that you have to enter them between &lt; &gt; rather than [ ]';
$helptxt['customform_type'] =
	'This setting allows you to set the type of field that this is displayed. Thus restricting the input that a user can submit.<br /><br /><b>Text Box (String)</b> adds a small input box allowing the user to type anything..<br /><br /><b>Large (Multiline) Text Box</b> adds a large input box allowing the user to type anything.<br /><br /><b>Text Box (Integer)</b> Will only allow the user to type whole numbers. The user can not type text, decimals, or fractions in this box. For instance if the user were to type 12.345 in the box, only 12 would displayed in their post.<br /><br /></b><b>Text Box (Float)</b> allows a user to enter <a href=http://en.wikipedia.org/wiki/Floating_point>Floating Point Values</a>. In most cases this will be used when you expect the user to enter numbers with decimals in the form.<br /><br /><b>Check box</b> will post whether the boxed was checked or not once the form is submitted to the forum. By default it will post <b>yes</b> if checked and <b>no</b> if not checked.<br /><br /><b>Selection Box</b> will allow the user to chose from various items. Enter the list of items you want separated by commas in the <b>Extra Type Parameters</b> field. The first option will be preselected unless the user selects something else.<br /><br /><b>Radio Box</b> like a Selection Box will allow the user to chose from various items. Enter the list of items you want separated by commas in the <b>Extra Type Parameters</b> field. None of the items will be preselected.<br /><br /><b>Information Box</b> allows you to display text throughout the form without requireing any user input.';
$helptxt['customform_type_vars'] =
	'This field allows you to set any necessary extra paramaters to change the behavior of the field in the form and forum post depending on the type field.<br /><br /><b>Text Box Parameters</b><br /><br /><b>parse_bbc</b> In order for your users to use <ins><a href="http://wiki.simplemachines.org/smf/Basic_Bulletin_Board_Codes" target="_blank">BBCodes</a></ins> in their entry you must enter parse_bbc in the Extra Type Parameters field, otherwise BBC will not display properly in the forum post.<br /><br /><b>size=(int)</b> This will restrict the number of characters a user can type in their entry.<br /><br />Example: If you were to enter size=8 in the field then the users entry would be limited to 8 characters. So if the user types 1234567890 in the field, only 12345678 will be displayed in the forum post.<br /><br /><b>default=(str)</b> This allows you to set default text that will display in the forum post if the user fails to fill out the entry.<br /><br />Example: If you enter &quot;default=User did not enter any data in this field.&quot;, in the Extra Type Parameters field and the user does not enter any input in the filed when filling out the form then &quot;User did not enter any data in this field.&quot; will automatically be displayed in the forum post.<br /><br /><b>required</b> You can also enter &quot;required&quot; in the Extra Type Parameters field which will force the user to enter valid data for this field before the form will submit.<br /><br />The fields are denoted in the form by an * and a note by the submit button stating * Required Fields. If the user fails enter data in those fields the form will return with the <b><span style="color:red">*</span></b>\'s displayed in <b><span style="color:red">red</span></b>, reminding the user that those fields must be filled out in order for them to be able to submit the form.<br /><br /><b>Select Box or Radio Box</b><br /><br />A Select Box or Radio Box, will allow you to put a series of options (separated by commas \' ), for the user to select.<br /><br />Example: Item 1, Item 2, Item 3, Item 4, and so on.<br /><br />To Require a user to use a Selection Box or Radio Box enter &quot;required&quot; as the first selection. Entering &quot;required&quot; elsewhere in the series of options may cause your form not to work properly.<br /><br />Example: required, Item 1, Item 2, Item 3, Item 4 <br /><br /><b>Check Box</b><br /><br />By default if you leave the Extra Type Parameters field empty, a check box will post <b>Yes</b> if the box is checked in the form or <b>No</b> if it was not.<br /><br />Alternatively a check box will allow you to put two strings, separated by a comma, the first string will be shown if the check box is checked while the second will be shown if the check box is not.<br /><br />Example: The check box was checked.,The check box was not checked.<br /><br />You may also use the &quot;required&quot; parameter to force the user to check the box before they submit a form. By default , if you just enter &quot;required&quot; in the Extra Type Parameters field, the check box will simply display <b>required</b>, in the forum post. You may also have it display something of your choosing in the forum post.<br /><br />Example: I was required to check this box.,required';

?>
