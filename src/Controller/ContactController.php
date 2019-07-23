<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Departement;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer)
    {

        $sended = false;

        // selectionner les departements pour les afficher dans la liste
        $departs = $this->getDoctrine()->getRepository(Departement::class)->findAll();

        // creation de formulaire contact
        $form = $this->createFormBuilder()
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("mail", EmailType::class )
            ->add("message", TextareaType::class)
            ->add("departement", ChoiceType::class ,[
                        'choices'  => $departs,
                        'choice_label' => function(Departement $departement) {
                            return $departement->getNom();
                        },
            ])
            ->add("save", SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();




        // recuperer les info saisie dans le formulaire
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            $objectManager = $this->getDoctrine()->getManager();

            $data = $form->getData();

            // creer et ajouter conatct dans la base donnees
            $contact = new Contact();
            $contact->setNom($data['nom']);
            $contact->setPrenom($data['prenom']);
            $contact->setMail($data['mail']);
            $contact->setMessage($data['message']);
            $objectManager->persist($contact);
            $objectManager->flush();

            // envoyer l 'email
            $message = (new \Swift_Message('Nouveau demande de contact'))
                ->setFrom($data['mail'])
                ->setTo($data['departement']->getResponsable()->getEmail())
                ->setBody(
                    $this->renderView(
                        'contact/contact.html.twig',
                        ['contact' => $contact]
                    ),
                    'text/html'
                );
            if (!$mailer->send($message, $failures))
            {
                $sended = false;
            }
            else {
                $sended = true;
            }
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'sended' => $sended,
        ]);
    }

}
