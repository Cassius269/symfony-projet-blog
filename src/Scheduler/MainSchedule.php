<?php

namespace App\Scheduler;

use Symfony\Component\Scheduler\Schedule;
use App\Scheduler\Message\InitArticlesDate;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('RenewDateArticles')]
final class MainSchedule implements ScheduleProviderInterface
{
    public function __construct(
        private CacheInterface $cache
    ) {}

    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                // Réinitilialiser la date de création des articles tous les 7 jours
                RecurringMessage::every('1 week', new InitArticlesDate()),
            )
            ->stateful($this->cache)
        ;
    }
}
