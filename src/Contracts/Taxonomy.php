<?php

namespace Syndicate\Taxonomist\Contracts;

use BackedEnum;
use Filament\Support\Contracts\HasLabel;

interface Taxonomy extends BackedEnum, HasLabel
{
    public static function getId(): string;

    public static function getName(): string;

    public function getParent(): ?Taxonomy;
}
