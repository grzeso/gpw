<?php

namespace App\Helper;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DaysWithoutSession;

class DaysWithoutSessionHelper {
    
    private static $daysOfWeekWithoutSession = [6, 0,]; 
    
    /**
     * @var EntityManager
     */
    private static $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager) {
        self::$entityManager = $entityManager;
    }
    
    public static function isDayWithoutSession(): bool
    {
        if (in_array(date('w'), self::$daysOfWeekWithoutSession)) {
            return true;
        }

        if (self::$entityManager->getRepository(DaysWithoutSession::class)-> findOneBy(['day' => date("Y-m-d")])) {
            return true;
        }
        
        return false;
    }
}
