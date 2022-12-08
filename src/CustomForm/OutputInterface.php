<?php

namespace CustomForm;

interface OutputInterface
{
	public function send(string $subject, string $output, array $form_data): void;
}