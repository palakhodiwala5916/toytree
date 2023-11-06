<?php

namespace App\DependencyInjection\Framework;

use Symfony\Contracts\Translation\TranslatorInterface;

trait TranslatorDI
{
    protected TranslatorInterface $translator;

    #[\Symfony\Contracts\Service\Attribute\Required]
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}
