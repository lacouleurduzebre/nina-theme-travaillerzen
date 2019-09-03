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
use App\Entity\Page;
use App\Entity\SEO;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TravaillerZenAccueilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $date = new \DateTime();

        //Langue par défaut
        $repoLangue = $manager->getRepository(Langue::class);
        $langue = $repoLangue->findOneBy(['defaut' => 1]);

        //Page d'accueil
        $accueil = $langue->getPageAccueil();

        if(!$accueil){
            $seo = new SEO();
            $seo->setUrl('accueil')
                ->setMetaTitre("Accueil")
                ->setMetaDescription("Accueil");
            $manager->persist($seo);

            $accueil = new Page();
            $accueil->setTitre("Accueil")
                ->setTitreMenu("Accueil")
                ->setSEO($seo)
                ->setDateCreation($date)
                ->setDatePublication($date)
                ->setLangue($langue);

            $manager->persist($accueil);

            $langue->setPageAccueil($accueil);
            $manager->persist($langue);
        }

        $blocs = $accueil->getBlocs();
        foreach($blocs as $bloc){
            $accueil->removeBloc($bloc);
        }

        //Édito
        $edito = new Bloc();
        $edito->setType('Texte')
            ->setPage($accueil)
            ->setClass('edito')
            ->setPosition(0)
            ->setContenu([
                'texte' => file_get_contents(getcwd().'/themes/festival/fixtures/edito.html')
            ]);
        $manager->persist($edito);

        //Bouton découvrir
        $contenuBouton = [
            'lien' => '#',
            'texte' => 'Découvrir',
            'titre' => 'Découvrir'
        ];

        $bouton = new Bloc();
        $bouton->setType('Bouton')
            ->setPage($accueil)
            ->setClass('decouvrir')
            ->setPosition(1)
            ->setContenu($contenuBouton);
        $manager->persist($bouton);

        //Tarifs
        $groupeBlocsTarifs = new GroupeBlocs();
        $groupeBlocsTarifs->setNom('Tarifs')
            ->setLangue($langue)
            ->setIdentifiant('tarifs');
        $manager->persist($groupeBlocsTarifs);

        $contenuTitreTarifs = [
            'texte' => 'Tarifs',
            'balise' => 'h2'
        ];

        $titreTarifs = new Bloc();
        $titreTarifs->setType('Titre')
            ->setGroupeBlocs($groupeBlocsTarifs)
            ->setPosition(0)
            ->setContenu($contenuTitreTarifs);
        $manager->persist($titreTarifs);

        $lienTarif = [
            'lien' => '#',
            'texte' => 'Réserver mon billet',
            'titre' => 'Réserver mon billet'
        ];

        $contenuTarifs = [
            'nbColonnes' => 3,
            'cases' => [
                [
                    'position' => 0,
                    'titre' => '1 jour /20€',
                    'texte' => '(vendredi, samedi ou dimanche)',
                    'lien' => $lienTarif
                ],
                [
                    'position' => 1,
                    'titre' => 'Samedi et Dimanche /35€',
                    'texte' => '',
                    'lien' => $lienTarif
                ],
                [
                    'position' => 2,
                    'titre' => 'Pass 3 jours /50€',
                    'texte' => '(vendredi, samedi et dimanche)',
                    'lien' => $lienTarif
                ]
            ]
        ];

        $tarifs = new Bloc();
        $tarifs->setType('Grille')
            ->setGroupeBlocs($groupeBlocsTarifs)
            ->setPosition(1)
            ->setContenu($contenuTarifs);
        $manager->persist($tarifs);
        $manager->flush();

        $blocTarifs = new Bloc();
        $blocTarifs->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(2)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsTarifs->getId()
            ]);
        $manager->persist($blocTarifs);

        //Réseaux sociaux des artisans
        $groupeBlocsArtisans = new GroupeBlocs();
        $groupeBlocsArtisans->setNom('Artisans')
            ->setLangue($langue)
            ->setIdentifiant('artisans');
        $manager->persist($groupeBlocsArtisans);

        $contenuTitreArtisans = [
            'texte' => 'Suivez les artisans sur leurs réseaux sociaux !',
            'balise' => 'h2'
        ];

        $titreArtisans = new Bloc();
        $titreArtisans->setType('Titre')
            ->setGroupeBlocs($groupeBlocsArtisans)
            ->setPosition(0)
            ->setContenu($contenuTitreArtisans);
        $manager->persist($titreArtisans);

        $imageArtisans = [
            'image' => '/theme/img/artisans.jpg',
            'description' => 'pseudo_artisan'
        ];

        $texteArtisans = "<p>pseudo_artisan</p><p>@pseudo_artisan</p>";

        $contenuArtisans = [
            'nbColonnes' => 4,
            'cases' => [
                [
                    'position' => 0,
                    'texte' => $texteArtisans,
                    'image' => $imageArtisans
                ],
                [
                    'position' => 1,
                    'texte' => $texteArtisans,
                    'image' => $imageArtisans
                ],
                [
                    'position' => 2,
                    'texte' => $texteArtisans,
                    'image' => $imageArtisans
                ],
                [
                    'position' => 3,
                    'texte' => $texteArtisans,
                    'image' => $imageArtisans
                ]
            ]
        ];

        $artisans = new Bloc();
        $artisans->setType('Grille')
            ->setGroupeBlocs($groupeBlocsArtisans)
            ->setPosition(1)
            ->setContenu($contenuArtisans);
        $manager->persist($artisans);
        $manager->flush();

        $boutonArtisans = new Bloc();
        $boutonArtisans->setType('Bouton')
            ->setGroupeBlocs($groupeBlocsArtisans)
            ->setPosition(2)
            ->setContenu([
                'lien' => '#',
                'texte' => 'Voir + d’artisans'
            ]);
        $manager->persist($boutonArtisans);

        $blocArtisans = new Bloc();
        $blocArtisans->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(3)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsArtisans->getId()
            ]);
        $manager->persist($blocArtisans);

        //Galerie
        $groupeBlocsGalerie = new GroupeBlocs();
        $groupeBlocsGalerie->setNom('Galerie 2018')
            ->setLangue($langue)
            ->setIdentifiant('galerie-2018');
        $manager->persist($groupeBlocsGalerie);

        $contenuTitreGalerie = [
            'texte' => 'Les ateliers du festival des artisans 2018',
            'balise' => 'h2'
        ];

        $titreGalerie = new Bloc();
        $titreGalerie->setType('Titre')
            ->setGroupeBlocs($groupeBlocsGalerie)
            ->setPosition(0)
            ->setContenu($contenuTitreGalerie);
        $manager->persist($titreGalerie);

        $contenuGalerie = [
            'affichage' => 'lightbox',
            'images' => [
                [
                    'image' => [
                        'image' => 'theme/img/galerie1.jpg',
                    ],
                    'position' => 0,
                ],
                [
                    'image' => [
                        'image' => 'theme/img/galerie2.jpg',
                    ],
                    'position' => 1,
                ],
                [
                    'image' => [
                        'image' => 'theme/img/galerie3.jpg',
                    ],
                    'position' => 2,
                ],
                [
                    'image' => [
                        'image' => 'theme/img/galerie4.jpg',
                    ],
                    'position' => 3,
                ]
            ]
        ];

        $galerie = new Bloc();
        $galerie->setType('Galerie')
            ->setGroupeBlocs($groupeBlocsGalerie)
            ->setPosition(1)
            ->setContenu($contenuGalerie);
        $manager->persist($galerie);
        $manager->flush();

        $blocGalerie = new Bloc();
        $blocGalerie->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(4)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsGalerie->getId()
            ]);
        $manager->persist($blocGalerie);

        //Prévente
        $groupeBlocsPrevente = new GroupeBlocs();
        $groupeBlocsPrevente->setNom('Prévente en ligne')
            ->setLangue($langue)
            ->setIdentifiant('prevente');
        $manager->persist($groupeBlocsPrevente);

        $textePrevente = new Bloc();
        $textePrevente->setType('Texte')
            ->setGroupeBlocs($groupeBlocsPrevente)
            ->setPosition(0)
            ->setContenu([
                'texte' => '<h2>Prévente en ligne</h2><p>Tarif 1, 2 ou 3 jours</p>'
            ]);
        $manager->persist($textePrevente);

        $boutonPrevente = new Bloc();
        $boutonPrevente->setType('Bouton')
            ->setGroupeBlocs($groupeBlocsPrevente)
            ->setPosition(1)
            ->setContenu([
                'lien' => '#',
                'texte' => 'Acheter mon billet'
            ]);
        $manager->persist($boutonPrevente);
        $manager->flush();

        $blocPrevente = new Bloc();
        $blocPrevente->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(5)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsPrevente->getId()
            ]);
        $manager->persist($blocPrevente);

        $manager->flush();
    }
}