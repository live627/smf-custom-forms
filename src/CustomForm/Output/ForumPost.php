<?php

declare(strict_types=1);

namespace CustomForm\Output;

use CustomForm\OutputInterface;

class ForumPost implements OutputInterface
{
	public function send(string $subject, string $output, array $form_data): void
	{
		global $smcFunc, $sourcedir, $user_info;

		require_once $sourcedir . '/Subs-Post.php';
		$msgOptions = [
			'id' => 0,
			'subject' => $smcFunc['htmlspecialchars']($subject),
			'icon' => $form_data['icon'],
			'body' => $smcFunc['htmlspecialchars']($output),
			'smileys_enabled' => true,
		];
		$topicOptions = [
			'id' => 0,
			'board' => $form_data['id_board'],
			'mark_as_read' => true,
		];
		$posterOptions = [
			'id' => $user_info['id'],
		];

		createPost($msgOptions, $topicOptions, $posterOptions);
	}
}
