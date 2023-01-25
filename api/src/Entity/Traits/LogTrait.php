<?php

namespace App\Entity\Traits;

trait LogTrait
{
    private bool $trackActivities = true;

    public function ignoreProperties(): array
    {
        return [
            'createdAt', 'updatedAt'
        ];
    }

    public function setTrackActivities(bool $is): self
    {
        $this->trackActivities = $is;

        return $this;
    }

    public function trackActivities(): bool
    {
        return $this->trackActivities;
    }
}