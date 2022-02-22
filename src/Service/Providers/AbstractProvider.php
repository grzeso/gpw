<?php

namespace App\Service\Providers;

use App\Helper\Users\AbstractUser;
use DateTime;

abstract class AbstractProvider
{
    protected AbstractUser $user;
    protected DateTime $date;
    protected array $specialData;

    abstract public function execute();

    abstract public function getAttachment();

    public function setUser(AbstractUser $user): void
    {
        $this->user = $user;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
    }

    public function setSpecialData(array $specialData)
    {
        $this->specialData = $specialData;
    }

    public function getAttachmentName(): string
    {
        return 'GPW_'.$this->date->format('Y-m-d');
    }
}
