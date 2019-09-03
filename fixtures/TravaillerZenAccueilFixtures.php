<?php
/**
 * Created by PhpStorm.
 * User: nadege
 * Date: 2019-08-05
 * Time: 09:30
 */

namespace Theme\travaillerzen\fixtures;


use App\Entity\Bloc;
use App\Entity\Categorie;
use App\Entity\Configuration;
use App\Entity\GroupeBlocs;
use App\Entity\Langue;
use App\Entity\Page;
use App\Entity\SEO;
use App\Entity\TypeCategorie;
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

        //Titre principal
        $titrePrincipal = new Bloc();
        $titrePrincipal->setType('Titre')
            ->setPage($accueil)
            ->setPosition(0)
            ->setContenu([
                'texte' => 'La solution pour<br> travailler en équipe !',
                'balise' => 'h2'
            ]);
        $manager->persist($titrePrincipal);

        //Édito
        $edito = new Bloc();
        $edito->setType('Grille')
            ->setPage($accueil)
            ->setClass('edito')
            ->setPosition(1)
            ->setContenu([
                'nbColonnes' => 3,
                'cases' => [
                    [
                        'position' => 0,
                        'texte' => '<p><i class="fas fa-map-marker-alt"></i></p><p>Trouvez le lieu qui vous convient pour travailler</p>'
                    ],
                    [
                        'position' => 1,
                        'texte' => '<p><i class="fas fa-phone"></i></p><p>Réservez votre bureau, open space ou salle de réunion</p>'
                    ],
                    [
                        'position' => 2,
                        'texte' => '<p><i class="fas fa-users"></i></p><p>Retrouvez vos collègues à votre lieu de travail</p>'
                    ]
                ]
            ]);
        $manager->persist($edito);

        //Titre services
        $titreServices = new Bloc();
        $titreServices->setType('Titre')
            ->setClass('services-titre')
            ->setPage($accueil)
            ->setPosition(2)
            ->setContenu([
                'texte' => 'Vos services en ligne',
                'balise' => 'h2'
            ]);
        $manager->persist($titreServices);

        //Services
        $lien = [
            'lien' => '#',
            'texte' => 'En savoir plus'
        ];

        $services = new Bloc();
        $services->setType('Grille')
            ->setPage($accueil)
            ->setClass('services')
            ->setPosition(3)
            ->setContenu([
                'nbColonnes' => 2,
                'cases' => [
                    [
                        'position' => 0,
                        'image' => [
                            'image' => '/theme/img/projet.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Proposer un projet',
                        'lien' => $lien
                    ],
                    [
                        'position' => 1,
                        'image' => [
                            'image' => '/theme/img/espace.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Réserver un espace de travail',
                        'lien' => $lien
                    ],
                    [
                        'position' => 2,
                        'image' => [
                            'image' => '/theme/img/reunion.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Organiser une réunion',
                        'lien' => $lien
                    ],
                    [
                        'position' => 3,
                        'image' => [
                            'image' => '/theme/img/localisation.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Nous localiser',
                        'lien' => $lien
                    ]
                ]
            ]);
        $manager->persist($services);

        //Actualités
        $groupeBlocsActus = new GroupeBlocs();
        $groupeBlocsActus->setNom('Actualités')
            ->setLangue($langue)
            ->setIdentifiant('actus');
        $manager->persist($groupeBlocsActus);
        $manager->flush();

        $titreActus = new Bloc();
        $titreActus->setType('Titre')
            ->setGroupeBlocs($groupeBlocsActus)
            ->setPosition(0)
            ->setContenu([
                'texte' => 'Nos actualités',
                'balise' => 'h2'
            ]);
        $manager->persist($titreActus);

        $lienActu = [
            'texte' => '<i class="fas fa-plus"></i>',
            'titre' => 'En savoir plus',
            'lien' => '#'
        ];

        $actus = new Bloc();
        $actus->setType('Grille')
            ->setGroupeBlocs($groupeBlocsActus)
            ->setPosition(1)
            ->setContenu([
                'nbColonnes' => 3,
                'cases' => [
                    [
                        'position' => 0,
                        'image' => [
                            'image' => '/theme/img/methodes.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Les bonnes méthodes de travail',
                        'lien' => $lienActu
                    ],
                    [
                        'position' => 1,
                        'image' => [
                            'image' => '/theme/img/pause.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'À quel moment s’accorder une pause ?',
                        'lien' => $lienActu
                    ],
                    [
                        'position' => 2,
                        'image' => [
                            'image' => '/theme/img/yeux.jpg',
                            'description' => 'Proposer un projet'
                        ],
                        'titre' => 'Devant les écrans, protégez-vos yeux !',
                        'lien' => $lienActu
                    ]
                ]
            ]);
        $manager->persist($actus);

        $boutonActus = new Bloc();
        $boutonActus->setType('Bouton')
            ->setGroupeBlocs($groupeBlocsActus)
            ->setPosition(2)
            ->setContenu([
                'lien' => '#',
                'texte' => 'Toutes les actualités'
            ]);
        $manager->persist($boutonActus);

        $blocActus = new Bloc();
        $blocActus->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(4)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsActus->getId()
            ]);
        $manager->persist($blocActus);

        //Carte
        $carte = new Bloc();
        $carte->setType('Image')
            ->setPage($accueil)
            ->setPosition(5)
            ->setContenu([
                'image' => '/theme/img/carte.jpg',
                'description' => 'Nous localiser'
            ]);
        $manager->persist($carte);

        //Formulaire de réservation
        $groupeBlocsFormulaire = new GroupeBlocs();
        $groupeBlocsFormulaire->setNom('Réservation')
            ->setLangue($langue)
            ->setIdentifiant('reservation');
        $manager->persist($groupeBlocsFormulaire);
        $manager->flush();

        $titreFormulaire = new Bloc();
        $titreFormulaire->setType('Titre')
            ->setGroupeBlocs($groupeBlocsFormulaire)
            ->setPosition(0)
            ->setContenu([
                'texte' => "Réservation d'un espace de travail",
                'balise' => 'h2'
            ]);
        $manager->persist($titreFormulaire);

        $repoConfig = $manager->getRepository(Configuration::class);
        $mailContact = $repoConfig->find(1)->getEmailContact();

        $formulaire = new Bloc();
        $formulaire->setType('Formulaire')
            ->setGroupeBlocs($groupeBlocsFormulaire)
            ->setPosition(1)
            ->setContenu([
                'destinataires' => [
                    $mailContact
                ],
                'objet' => "Réservation d'un espace de travail",
                'messageConfirmation' => "Merci pour votre message, nous vous recontacterons dans les meilleurs délais.",
                'submit' => "Envoyer",
                'champs' => [
                    [
                        'type' => 'text',
                        'position' => 0,
                        'label' => 'Votre nom'
                    ],
                    [
                        'type' => 'text',
                        'position' => 1,
                        'label' => 'Votre prénom'
                    ],
                    [
                        'type' => 'text',
                        'position' => 2,
                        'label' => 'Votre mail'
                    ],
                    [
                        'type' => 'select',
                        'position' => 3,
                        'label' => 'Nombre de personnes',
                        'choix' => [
                            '1', '2', '3', '5', '10', '+ de 15'
                        ]
                    ],
                    [
                        'type' => 'select',
                        'position' => 4,
                        'label' => 'Un espace préféré',
                        'choix' => [
                            'Salle de réunion 1 rue du chemin de fer',
                            'Salle de réunion 2 rue du chemin de fer'
                        ]
                    ],
                    [
                        'type' => 'textarea',
                        'position' => 4,
                        'label' => 'Un message particulier ?',
                    ]
                ]
            ]);
        $manager->persist($formulaire);

        $blocFormulaire = new Bloc();
        $blocFormulaire->setType('GroupeBlocs')
            ->setPage($accueil)
            ->setPosition(6)
            ->setContenu([
                'groupeBlocs' => $groupeBlocsFormulaire->getId()
            ]);
        $manager->persist($blocFormulaire);

        $manager->flush();
    }
}