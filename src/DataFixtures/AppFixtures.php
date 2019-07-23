<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\Responsable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $departs = ["RH","DEV","DIRECTION","COM","APRES VENTE"];
        $responsables = ["chaymae.abourri@gmail.com","email2@gmail.com",
            "email2@gmail.com","email3@gmail.com","email4@gmail.com"];
        for ($i =0 ; $i < 5; $i++) {

            $dep = new Departement();
            $dep->setNom($departs[$i]);

            $resp = new Responsable();
            $resp->setNom("res".$i);
            $resp->setEmail($responsables[$i]);

            $dep->setResponsable($resp);
            $resp->setDepartement($dep);

            $manager->persist($dep);
            $manager->persist($resp);

        }
        $manager->flush();
    }
}
