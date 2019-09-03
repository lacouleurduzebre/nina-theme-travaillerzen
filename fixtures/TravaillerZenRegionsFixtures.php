<?php
/**
 * Created by PhpStorm.
 * User: nadege
 * Date: 2019-08-05
 * Time: 09:30
 */

namespace Theme\travaillerzen\fixtures;


use App\Entity\Bloc;
use App\Entity\GroupeBlocs;
use App\Entity\Langue;
use App\Entity\Menu;
use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TravaillerZenRegionsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Langue par défaut
        $repoLangue = $manager->getRepository(Langue::class);
        $langue = $repoLangue->findOneBy(['defaut' => 1]);

        //Menu principal
        $repoMenu = $manager->getRepository(Menu::class);
        $menu = $repoMenu->findOneBy(['defaut' => 1, 'langue' => $langue]);

        if(!$menu){
            $menu = new Menu();
            $menu->setNom('Menu principal (Travailler zen)')
                ->setLangue($langue);
            $manager->persist($menu);
            $manager->flush();
        }

        //Régions
        $repoRegions = $manager->getRepository(Region::class);
        $regions = $repoRegions->findAll();
        foreach($regions as $region){
            if($region->getIdentifiant() == 'contenu'){
                $region->setPosition(1);
                $manager->persist($region);
            }else{
                $manager->remove($region);
            }
        }

        $header = new Region();
        $header->setNom('En-tête')
            ->setIdentifiant('header')
            ->setPosition(0);
        $manager->persist($header);

        $footer = new Region();
        $footer->setNom('Pied de page')
            ->setIdentifiant('footer')
            ->setPosition(2);
        $manager->persist($footer);

        //Header
        $groupeBlocHeader = new GroupeBlocs();
        $groupeBlocHeader->setNom('Header')
            ->setLangue($langue)
            ->setRegion($header)
            ->setIdentifiant('header')
            ->setPosition(0);
        $manager->persist($groupeBlocHeader);

            //Logo
        $blocLogo = new Bloc();
        $blocLogo->setType('LogoSite')
            ->setPosition(0)
            ->setGroupeBlocs($groupeBlocHeader)
            ->setContenu([
                'logo' => [1],
                'nom' => [0]
            ]);
        $manager->persist($blocLogo);

            //Recherche
        $blocRecherche = new Bloc();
        $blocRecherche->setType('Recherche')
            ->setPosition(1)
            ->setGroupeBlocs($groupeBlocHeader);
        $manager->persist($blocRecherche);

            //Menu
        $blocMenuPrincipal = new Bloc();
        $blocMenuPrincipal->setType('Menu')
            ->setPosition(2)
            ->setGroupeBlocs($groupeBlocHeader)
            ->setContenu([
                'menu' => $menu->getId()
            ]);
        $manager->persist($blocMenuPrincipal);

        //Footer
        $groupeBlocFooter = new GroupeBlocs();
        $groupeBlocFooter->setNom('Footer')
            ->setLangue($langue)
            ->setRegion($footer)
            ->setIdentifiant('footer')
            ->setPosition(0);
        $manager->persist($groupeBlocFooter);

            //Titre
        $titre = new Bloc();
        $titre->setType('Titre')
            ->setGroupeBlocs($groupeBlocFooter)
            ->setPosition(0)
            ->setContenu([
                'texte' => 'Service de Co-working<br>en ligne',
                'balise' => 'h2'
            ]);
        $manager->persist($titre);

            //Texte
        $coordonnees = new Bloc();
        $coordonnees->setType('Texte')
            ->setGroupeBlocs($groupeBlocFooter)
            ->setPosition(1)
            ->setContenu([
                'texte' => '<p><strong>Coordonnées</strong></p><p>7 rue du chemin de Fer<br>67 000 STRASBOURG</p><p>tel. : 03 88 76 78 65<br>mail@mail.fr</p>'
            ]);
        $manager->persist($coordonnees);

            //Texte
        $concept = new Bloc();
        $concept->setType('Texte')
            ->setGroupeBlocs($groupeBlocFooter)
            ->setPosition(2)
            ->setContenu([
                'texte' => '<p>Notre concept vous plait ?<br> Partagez-le avec vos réseaux !</p>'
            ]);
        $manager->persist($concept);

            //Réseaux sociaux
        $rs = new Bloc();
        $rs->setType('ReseauxSociaux')
            ->setGroupeBlocs($groupeBlocFooter)
            ->setPosition(3)
            ->setContenu([
                'facebook' => [1],
                'facebookUrl' => '#',
                'instagram' => [1],
                'instagramUrl' => '#',
                'twitter' => [1],
                'twitterUrl' => '#'
            ]);
        $manager->persist($rs);

            //Menu
        $menuFooter = new Menu();
        $menuFooter->setNom('Menu du pied de page (Travailler zen)')
            ->setLangue($langue);
        $manager->persist($menuFooter);
        $manager->flush();

        $blocMenuFooter = new Bloc();
        $blocMenuFooter->setType('Menu')
            ->setPosition(4)
            ->setGroupeBlocs($groupeBlocFooter)
            ->setContenu([
                'menu' => $menuFooter->getId()
            ]);
        $manager->persist($blocMenuFooter);

        $manager->flush();
    }
}