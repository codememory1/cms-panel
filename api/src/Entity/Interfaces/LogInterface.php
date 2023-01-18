<?php

namespace App\Entity\Interfaces;

interface LogInterface
{
    public function ignoreProperties(): array;

    public function setTrackActivities(bool $is): self;

    public function trackActivities(): bool;
}