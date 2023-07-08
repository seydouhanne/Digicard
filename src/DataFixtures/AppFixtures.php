<?php

namespace App\DataFixtures;

use App\Entity\Links;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        

        $listLink = [];
        for ($i = 0; $i < 30; $i++) {
            // Création de lien fictif.
            $link = new Links();
            $link->setName("Lien " . $i);
            $link->setLink("link" . $i.".com");
            
        
            $manager->persist($link);
            // On sauvegarde les liens créé dans un tableau.
            $listLink[] = $link;
        }


         // Création d'une vingtaine d'utilisateurs 
         for ($i = 0; $i < 20; $i++) {
            $user = new User;
            $user->setName('Prenom ' . $i);
            $user->setSurname('Nom ' . $i);
            $user->setTitle('Metier' . $i);
            $user->setEmail('email' . $i.'@digicard.com');
            $user->setPhoneNumber('77830706' . $i);
            $user->setAddress('RokhouJinné' . $i);
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, "password"));
            $user->getLinks($listLink[array_rand($listLink)]);
           

            


          

            $manager->persist($user);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
