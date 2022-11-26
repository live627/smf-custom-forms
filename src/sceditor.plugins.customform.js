(function (document, sceditor) {
	'use strict';

	sceditor.plugins.customform2 = function()
	{
		let editor;
		this.init = function()
		{
			editor = this;
		};
		this.signalValuechangedEvent = e =>
		{
			editor.updateOriginal();
			document.getElementById(editor.opts.customformel).dispatchEvent(new Event('change', { 'bubbles': true }));
		};
	};

	sceditor.plugins.customform = function()
	{
		let editor;
		this.init = function()
		{
			editor = this;

			const dropDown = (caller, callback) =>
			{
				const content = document.createElement('div');

				for (const fieldName of editor.opts.customformFields)
				{
					const link = document.createElement('a');
					link.textContent = fieldName;
					link.addEventListener('click', function(e)
					{
						callback('{{ ' + this.textContent + ' }}');
						editor.closeDropDown(true);
						e.preventDefault();
					});
					content.appendChild(link);
				}

				editor.createDropDown(caller, 'format customform-fields', content);
			};
			const fn = caller =>
			{
				dropDown(caller, editor.insertText);
			};

			editor.commands.customformFields = {
				exec: fn,
				txtExec: fn,
				tooltip: 'Insert Field'
			};

			editor.opts.customformFields = customformFields;
		};
		this.signalReady = () =>
		{
			document.querySelector('.sceditor-button-customformFields').classList.add('text');
		};
	};
})(document, sceditor);
