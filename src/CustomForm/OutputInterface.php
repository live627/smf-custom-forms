<?php

namespace CustomForm;

interface OutputInterface
{
	public function send(string $output, array $form_data): void;
}