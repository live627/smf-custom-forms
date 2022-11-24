<?php

declare(strict_types=1);

/**
 * @package   Custom Form mod
 * @version   3.1.0
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
$txt['customform_view_title_desc'] = 'Choose the title for the action/page that shows the list of forms to a user (at "index.php?action=form"). Defaults to <b>Custom Forms</b>.';
$txt['customform_view_text'] = 'View Forms Text';
$txt['customform_view_text_desc'] = 'Choose the explanation text for the action/page that shows the list of forms to a user (at "index.php?action=form").';
$txt['customform_view_perms'] = 'Form View Page Permissions';
$txt['customform_view_perms_desc'] = 'Restrict the member groups that can see the list of forms. Individual forms run independent of this, there is no permission inheritance.';

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
$txt['customform_subject'] = 'Form Subject';
$txt['customform_subject_desc'] = 'Subject of final message. Supports macros for field names.';
$txt['customform_output'] = 'Form Output';
$txt['customform_output_desc'] = 'To actually display data a user enters in the forum post you will need to enter the identifier of the field between a set of braces <code>{{ name }}</code>.';
$txt['customform_board'] = 'Board to post to';
$txt['customform_board_desc'] = 'Select the board that the form will post to. The target board can be hidden.';
$txt['customform_template_function'] = 'Custom Template Function';
$txt['customform_exit'] = 'Submit Redirect';

//  Redirection Text
$txt['customform_redirect_forum'] = 'Forum';
$txt['customform_redirect_topic'] = 'Topic';
$txt['customform_redirect_board'] = 'Board (default)';
$txt['customform_redirect_thanks'] = 'Thank You';
$txt['customform_redirect_list'] = 'Form List';

//  Text for the thankyou page
$txt['customform_thankyou'] = '
	<p>The information you entered has been submited.</p>
	<p>Thank you for taking the time to complete this form.</p>
	<p>You may now return to the forum or view the form list, if available.</p>';

//	Options for the field edit page.
$txt['customform_character_warning'] = 'This field\'s identifier contains some illegal characters.';
$txt['customform_current_identifier'] = 'Your identifier is %s';
$txt['customform_suggested_identifier'] = 'Suggested identifier is %s';

$txt['customform_identifier'] = 'Identifier';
$txt['customform_text'] = 'Description';
$txt['customform_text_desc'] = 'A description of the field, shown to the user when they enter the information.';
$txt['customform_type'] = 'Type';
$txt['customform_type_vars'] = 'Extra Type Parameters';
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
$txt['whoallow_form'] = 'Viewing the <a href="%s?action=form">list of forms</a>.';
// argument(s): $scripturl, $id_form, $title
$txt['customform_who'] = 'Viewing <a href="%s?action=form;n=%s">%s</a>.';

//	Help text for the edit form page.
$helptxt['customform_output'] = '
	<p>This is the the format in which the data a users enters in the form will be displayed in the forum post after they submit the form.</p>
	<p>To actually display data a user enters in the forum post you will need to enter the title of the field between braces { }.</p>
	<p><b>Example</b>: If the field was called \'name\' then <code>{{ name }}</code> would be replaced with what the user entered in the field of the form.</p>
	<p><code class="tfacode">My name is {{ name }}.</code></p>
	<p>Then if the user were to enter "Bob" in the corresponding form field, the forum post would display "My name is Bob".</p>
	';
$helptxt['customform_submit_exit'] = '
	<p>This setting allows you to select where the user is sent after the form is successfully completed.</p>
	<p>Several macros are listed below that you can use.</p>
	<ul class="normallist">
		<li>
			<b>board</b>
			&nbsp;- will redirect the user to the board where the form is posted (this is also the default if the field is left empty).
		</li>
		<li><b>forum</b>
			&nbsp;- will redirect the user to the Forum Index page.
		</li>
		<li><b>form</b>
			&nbsp;- will redirect the user to the list of forms available form them to fill out, if they have permission to view it.
		</li>
		<li><b>thanks</b>
			&nbsp;- will redirect the user to a simple page letting them know that form has been completed correctly and thanking them for completing the form.
		</li>
	</ul>
	<p>forum, form, and thanks, are useful if the form posts to a board the user does not have access to view.</p>
	<p>You can also enter a URL like <code>https://www.eample.com/</code> and the user will be directed to that URL. This can be useful to redirect users to a custom thank you page, another specific form, a specific forum post, or anyplace else on the internet.</p>
	';
$helptxt['customform_template_function'] = '
	<p>Custom template functions can be added to <code>./themes/default/CustomForm.template.php</code>. Please note that the template function which will be used has to be named with the format, <code>template_{value for this setting}</code>, otherwise the default <code>template_form()</code> template function will be used.</p>
	<p>You can then use the documentation from that function to see how information is passed to it by the Mod, allowing you to change it for your purposes.</p>
	<p>For a brief example of what you can change in a template enter "example" in the Custom Template Function of a form then view that form. You will see several places with the text <span style="color:red">"Example of something"</span>, these are good places for you to add information to your form template without affecting the functions of the form itself.</p>
	<p><b><span style="color:red">Important</span></b>: You should have basic working knowledge of HTML and PHP before you do anything too drastic.</p>
	<p class="strong alert">Improper coding will cause the Custom Form Mod and possibly your forum to break! Therefore, backup your form or at least <code>./themes/default/CustomForm.template.php</code> before making any changes.</p>
	';

//	Help text for the edit field page.
$helptxt['customform_field_title'] = '
	<p>This is the identifier that will be used by the mod to access the user input from the form. It is not displayed in the form or the final forum post.</p>
	<p>Note that the identifier is only seen by admins and not the users, it is used to designate where the users input is displayed in the form and when a submitted form is posted in the forum. For best results keep the titles short, all lower case, and do not use special characters like # & * @ [ etc.</p>
	<p>Example: name, username, and user_name all work fine, but "User Name" will not.</p>
	<p>An incorect identifier will cause your form to not work properly. For instance the information the user types in the form will not be displayed in the forum post or the form may not appear to users at all.</p>
	';
$helptxt['customform_type'] = '
	<p>This setting allows you to set the type of field that this is displayed. Thus restricting the input that a user can submit.</p>
	<ul class="normallist">
		<li>
			<b>Text</b> adds a small input box allowing the user to type anything.
		</li>
		<li>
			<b>Large Text</b> adds a large input box allowing the user to type anything with multiple lines allowed.
		</li>
		<li>
			<b>Checkbox</b> will post whether the box was checked or not once the form is submitted to the forum. By default it will post <b>yes</b> if checked and <b>no</b> if not checked.
		</li>
		<li>
			<b>Select Box</b> will allow the user to chose from various items. Enter the list of items you want separated by commas in the <b>Extra Type Parameters</b> field. The first option will be preselected unless the user selects something else.
		</li>
		<li>
			<b>Radio Buttons</b> like a Select Box will allow the user to chose from various items. Enter the list of items you want separated by commas in the <b>Extra Type Parameters</b> field. None of the items will be preselected.
		</li>
		<li>
			<b>Information</b> allows you to display text throughout the form without requireing any user input.
		</li>
	</ul>
	';
$helptxt['customform_type_vars'] = '
	<p>This field allows you to set any necessary extra paramaters to change the behavior of the field in the form and forum post depending on the type field.</p>
	<h3 class="largetext">Parameters for any field</h3>
	<ul class="normallist">
		<li>
			<p><b>default=(str)</b> This allows you to set default text that will display in the forum post if the user fails to fill out the entry.</p>
			<p>Example: If you enter "default=User did not enter any data in this field.", in the Extra Type Parameters field and the user does not enter any input in the filed when filling out the form then "User did not enter any data in this field." will automatically be displayed in the forum post.</p>
		</li>
		<li>
			<p><b>required</b> You can also enter "required" in the Extra Type Parameters field which will force the user to enter valid data for this field before the form will submit.</p>
			<p>The fields are denoted in the form by an * and a note by the submit button stating * Required Fields. If the user fails enter data in those fields the form will return with the <b><span style="color:red">*</span></b>\'s displayed in <b><span style="color:red">red</span></b>, reminding the user that those fields must be filled out in order for them to be able to submit the form.</p>
		</li>
	</ul>
	<h3 class="largetext">Text Box Parameters</h3>
	<ul class="normallist">
		<li>
			<p><b>size=(int)</b> This will restrict the number of characters a user can type in their entry.</p>
			<p>Example: If you were to enter size=8 in the field then the users entry would be limited to 8 characters. So if the user types 1234567890 in the field, only 12345678 will be displayed in the forum post.</p>
		</li>
	</ul>
	<h3 class="largetext">Select Box or Radio Buttons</h3>
	<ul class="normallist">
		<li>
			<p>A Select Box or Radio Buttons, will allow you to put a series of options (separated by commas \' ), for the user to select.</p>
			<p>Example: Item 1, Item 2, Item 3, Item 4, and so on.</p>
			<p>To Require a user to use a Select Box or Radio Buttons enter "required" as the first selection. Entering "required" elsewhere in the series of options may cause your form not to work properly.</p>
			<p>Example: required, Item 1, Item 2, Item 3, Item 4 </p>
		</li>
	</ul>
	<h3 class="largetext">Checkox</h3>
	<ul class="normallist">
		<li>
			<p>By default if you leave the Extra Type Parameters field empty, a check box will post <b>Yes</b> if the box is checked in the form or <b>No</b> if it was not.</p>
			<p>Alternatively a checkbox will allow you to put two strings, separated by a comma, the first string will be shown if the checkbox is checked while the second will be shown if the checkbox is not.</p>
			<p>Example: The checkbox was checked.,The checkbox was not checked.</p>
			<p>You may also use the "required" parameter to force the user to check the box before they submit a form. By default , if you just enter "required" in the Extra Type Parameters field, the checkbox will simply display <b>required</b>, in the forum post. You may also have it display something of your choosing in the forum post.</p>
			<p>Example: I was required to check this box.,required</p>
		</li>
	</ul>';
