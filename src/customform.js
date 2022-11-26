(function (w) {
	'use strict';

	w.textareaLengthCheck = (textarea, num) =>
	{
		const el = document.createElement('div');
		const fn = () =>
		{
			const charactersLeft = num - textarea.value.length;
			el.innerHTML = 'Max characters: <b>' + num + '</b>; characters remaining: <b>' + charactersLeft + '</b>';
			if (charactersLeft < 0)
			{
				el.className = 'error';
				textarea.style.border = '1px solid red';
			}
			else
			{
				el.className = '';
				textarea.style.border = '';
			}
		};

		el.className = 'smalltext';
		textarea.parentNode.appendChild(el);
		textarea.addEventListener('change', fn);

		fn(num);
	};
})(window);
