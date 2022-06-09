<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Commande;
use App\Entity\CommandeProduit;
use App\Entity\Produit;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->encoder = $userPasswordHasherInterface;
    }

    public function load(ObjectManager $manager): void
    {
        $categories = [];
        for ($i = 1; $i <= 5; $i++) {
            $categorie = new Categorie();
            $categorie->setNom('Categorie ' . $i);
            $manager->persist($categorie);
            $categories[]=$categorie;
        }

        $produits = [];
        for ($i = 1; $i <= 3; $i++){
            $produit = new Produit();
            $produit->setCategorie($categories[random_int(0, count($categories) -1)]);
            $produit->setDescription('Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ratione ducimus illo veniam repudiandae assumenda, id consequatur perspiciatis iure, voluptates nemo dolor voluptas obcaecati error voluptatibus, unde excepturi explicabo tempora blanditiis.');
            $produit->setNom('Produit' . $i);
            $produit->setImgSrc('produit1.jpg');
            $produit->setPrix($i + ($i/10));
            $manager->persist($produit);
            $produits[]=$produit;
        }

        
        $lila = new User();
        $lila-> setNom('Lila');
        $lila-> setEmail('lila@lila.com');
        $hashedPassword = $this->encoder->hashPassword($lila, 'lila');
        $lila->setPassword($hashedPassword);
        $manager->persist($lila);

        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setEtat(0);
        $commande->setUser($lila);
        $manager->persist($commande);

        $cp1 = new CommandeProduit();
        $cp1->setCommande($commande);
        $cp1->setProduit($produits[0]);
        $cp1->setPrixVente($produits[0]->getPrix());
        $cp1->setQuantite(2);
        $manager->persist($cp1);

        $cp2 = new CommandeProduit();
        $cp2->setCommande($commande);
        $cp2->setProduit($produits[1]);
        $cp2->setPrixVente($produits[1]->getPrix());
        $cp2->setQuantite(1);
        $manager->persist($cp2);

        $manager->flush();
    }
}
