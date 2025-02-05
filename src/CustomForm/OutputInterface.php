<?php

declare(strict_types=1);

namespace CustomForm;

interface OutputInterface
{
	public function send(string $subject, string $output, array $form_data): void;
}
