## Tutorial
### Custom template functions
This is a feature allows you to create your own custom template function for each form, to do this we suggest that you make a duplicate of the `form_template_example()` function within the `CustomForm.template.php` file. You can then use the documentation from that function to see how information is passed to it by the Mod, allowing you to change it for your purposes.

Please remember that you have to name the new template function in this format `form_template_{Custom Template Name}`, and you will have to put the correct value from `{Custom Template Name}` into the `Custom Template Function` setting for the form that you wish to use you new template. Further explanation for custom templates can be found in the `CustomForm.template.php`.
