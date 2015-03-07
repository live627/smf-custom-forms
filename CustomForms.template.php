<?php

function template_view_form()
{
	global $context, $txt;

	echo '
	<style>
		li.padding
		{
			padding: 0.4em 0.7em !important;
		}
		li.padding a
		{
			font-size: 1.1em;
		}
		li.padding div.d
		{
			font-style: italic;
			opacity: 0.4;
			padding: 0.4em 0.7em;
		}
	</style>

	<div class="cat_bar">
		<h3 class="catbg">
			', $context['page_title'], '
		</h3>
	</div>
		<div class="pagesection">
			<div class="pagelinks floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>
		</div>
		<ul class="reset">';

		foreach ($context['forms'] as $item)
		{
			$col = empty($col) ? 2 : '';
			$color_class = 'windowbg' . $col;

			echo '
			<li class="padding ', $color_class, '">
				', $item['link'], '';

			if (!empty($item['description']))
				echo '
				<div class="d">', $item['description'], '</div>';

			echo '
			</li>';
		}

		echo '
		</ul>
		<div class="pagesection">
			<div class="pagelinks floatleft">', $txt['pages'], ': ', $context['page_index'], '</div>
		</div>';
}

function template_view_form()
{
	global $context, $txt, $scripturl;

	echo '
	<div id="leaguescenter">
		<form action="', $scripturl, '?action=league;area=l;sa=', $_GET['sa'] == 'a' ? 'a' : 'profileedit', ';lid=', $context['lid'], ';', $context['session_var'], '=', $context['session_id'], '" method="post" accept-charset="', $context['character_set'], '">
			<div class="cat_bar">
				<h3 class="catbg">
					', $context['page_title'], '
				</h3>
			</div>
			<div class="windowbg">
				<span class="topslice"><span></span></span>
				<div class="content">';

	if (!empty($context['post_errors']))
	{
		echo '
					<div class="errorbox">
						<h4>There were some errors</h4>';

		foreach ($context['post_errors'] as $error)
			echo '
						<span>', $error, '</span>';

		echo '
					</div>';
	}

	echo '
						<dl class="settings">';

	if (!empty($context['fields']))
		foreach ($context['fields'] as $field)
			echo '
							<dt>
								<strong>', $field['name'], ': </strong>
								<dfn>', $field['description'], '</dfn>
							</dt>
							<dd>
								', $field['input_html'], '
							</dd>';

	echo '
						</dl>
					<div class="righttext">
						<input type="submit" name="save" value="', $txt['save'], '" class="button_submit" />
					</div>
				</div>
				<span class="botslice"><span></span></span>
			</div>
			<input type="hidden" name="', $context['session_var'], '" value="', $context['session_id'], '" />
		</form>
	</div>';
}