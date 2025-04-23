<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Tender;
use App\Entity\Status;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $statusRepository = $manager->getRepository(Status::class);
        $statuses = $statusRepository->findAll();

        $steps = 0;
        $headerString = true;
        $handle = \fopen(__DIR__ . "\\test_task_data.csv", "r");
        while (($row = \fgetcsv($handle)) !== FALSE) {
            if ($headerString) {
                $headerString = false;
                continue;
            }
            $tender = new Tender();
            $tender->setCode($row[0]);
            $tender->setNumber($row[1]);
            $tender->setStatus(null);  // Значение по умолчанию
            foreach ($statuses as $status) {
                if ($row[2] == $status->getName()) {
                    $tender->setStatus($status);
                }
            }
            $tender->setName($row[3]);
            $tender->setUpdatedAt(new \DateTime($row[4]));
            $manager->persist($tender);
            // Сохраняем записи в БД пачками по 100 штук
            ++$steps;
            if ($steps >= 100) {
                $manager->flush();
                $steps = 0;
            }
        }
        $manager->flush();  // Сохраняем в БД оставшиеся записи (не кратные 100)
        \fclose($handle);
    }
}
