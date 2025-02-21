<?php

declare(strict_types=1);

namespace CustomForm\Output;

use CustomForm\{Form, OutputInterface};
use SMF\{Msg, User, Utils};

class ForumPost implements OutputInterface
{
	public function send(string $subject, string $output, Form $form): void
	{
		$msgOptions = [
			'id' => 0,
			'subject' => Utils::htmlspecialchars($subject),
			'icon' => $form->icon,
			'body' => Utils::htmlspecialchars($output),
			'smileys_enabled' => true,
		];
		$topicOptions = [
			'id' => 0,
			'board' => $form->board_id,
			'mark_as_read' => true,
		];
		$posterOptions = [
			'id' => User::$me->id,
		];

		Msg::create($msgOptions, $topicOptions, $posterOptions);
	}
}
