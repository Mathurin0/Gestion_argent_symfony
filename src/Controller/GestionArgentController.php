<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Participant;
use App\Entity\Soiree;
use App\Form\ParticipantType;
use App\Form\SoireeType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionArgentController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $soirees = $this->getDoctrine()->getRepository(Soiree::class)->findAll();
        $participants = $this->getDoctrine()->getRepository(Participant::class)->findAll();

        return $this->render('gestion_argent/index.html.twig', [
            'soirees' => $soirees,
            'participants' => $participants
        ]);
    }

    #[Route('gestion_argent/soiree_ajouter', name: 'soiree_ajouter')]
    public function soiree_ajouter(Request $request)
    {
        $soiree = new Soiree();

        $form = $this->createForm(SoireeType::class, $soiree);

        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($soiree);

            $em->flush();

            return $this->redirectToRoute("soiree_ajouter");
        }

        return $this->render('gestion_argent/ajouter_soiree.html.twig', [
            'formulaire'=>$form->createView()
        ]);
    }

    #[Route('gestion_argent/participant_ajouter', name: 'participant_ajouter')]
    public function participant_ajouter(Request $request)
    {
        $participant = new Participant();

        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($participant);

            $em->flush();

            return $this->redirectToRoute("participant_ajouter");
        }

        return $this->render('gestion_argent/ajouter_participant.html.twig', [
            'formulaire'=>$form->createView()
        ]);
    }

    #[Route('gestion_argent/resoudre/{id}', name: 'resoudre')]
    public function resoudre($id, Request $request)
    {
        $participants = $this->getDoctrine()->getRepository(Participant::class)->findBy(['soiree' => $id]);

        if (count($participants) < 2)
        {
            return $this->render('gestion_argent/manque_participants.html.twig', [
            ]);
        }

        $string_paiements = array();
        $nb_paiements = 0;

        $total_argent = 0;
        for ($i = 0; $i < Count($participants); $i++)
        {
            $total_argent += $participants[$i]->montant;
        }
        $moyenne = $total_argent / Count($participants);

        //savoir combient doit donner/recevoir chaque personne
        for ($i = 0; $i < Count($participants); $i++)
        {
            $participants[$i]->montant -= $moyenne;
            if ($participants[$i]->montant == 0)
            {
                $participants[$i]->a_faire=0;
            }
            else if ($participants[$i]->montant > 0)
            {
                $participants[$i]->a_faire=1;
            }
            else if ($participants[$i]->montant < 0)
            {
                $participants[$i]->a_faire=-1;
            }
        }

        //assigner les remboursements entre les personnes
        $fin = 0;
        while ($fin == 0)
        {
            $fin = 1;
            for ($i = 0; $i < Count($participants); $i++)
            {
                for ($j = $i+1; $j < Count($participants); $j++)
                {
                    $total1 = $participants[$i]->montant;
                    $total2 = $participants[$j]->montant;
                    $a_faire1 = $participants[$i]->a_faire;
                    $a_faire2 = $participants[$j]->a_faire;

                    if ($a_faire1 != 0 && $a_faire2 != 0 && $a_faire1+$a_faire2 == 0)
                    {
                        $fin = 0;
                        if ($a_faire1 == 1)
                        {
                            if ($total1+$total2 >=0)
                            {
                                $string_paiements[$nb_paiements] = $participants[$j]->prenom." ".$participants[$j]->nom." doit ".(string)(-$total2)." a ".$participants[$i]->prenom." ".$participants[$i]->nom;
                                $participants[$i]->montant += $total2;
                                $participants[$j]->montant = 0;
                                $participants[$j]->a_faire = 0;
                                if ($participants[$i]->montant == 0)
                                {
                                    $participants[$i]->a_faire = 0;
                                }
                            }
                            else if ($total1 + $total2 <= 0)
                            {
                                $string_paiements[$nb_paiements] = $participants[$j]->prenom." ".$participants[$j]->nom." doit ".(string)$total1." a ".$participants[$i]->prenom." ".$participants[$i]->nom;
                                $participants[$i]->montant = 0;
                                $participants[$j]->montant += $total1;
                                $participants[$i]->a_faire = 0;
                                if ($participants[$j]->montant == 0)
                                {
                                    $participants[$j]->a_faire = 0;
                                }
                            }
                        }
                        else if ($a_faire1 == -1)
                        {
                            if ($total1 + $total2 <= 0)
                            {
                                $string_paiements[$nb_paiements] = $participants[$i]->prenom." ".$participants[$i]->nom." doit ".(string)$total2." a ".$participants[$j]->prenom." ".$participants[$j]->nom;
                                $participants[$i]->montant += $total2;
                                $participants[$j]->montant = 0;
                                $participants[$j]->a_faire = 0;
                                if ($participants[$i]->montant == 0)
                                {
                                    $participants[$i]->a_faire = 0;
                                }
                            }
                            else if ($total1 + $total2 >= 0)
                            {
                                $string_paiements[$nb_paiements] = $participants[$i]->prenom." ".$participants[$i]->nom." doit ".(string)(-$total1)." a ".$participants[$j]->prenom." ".$participants[$j]->nom;
                                $participants[$i]->montant = 0;
                                $participants[$j]->montant += $total1;
                                $participants[$i]->a_faire = 0;
                                if ($participants[$j]->montant == 0)
                                {
                                    $participants[$j]->a_faire = 0;
                                }
                            }
                        }
                        $nb_paiements++;
                    }
                }
            }
        }

        return $this->render('gestion_argent/resoudre.html.twig', [
            'paiements'=>$string_paiements
        ]);
    }
}
