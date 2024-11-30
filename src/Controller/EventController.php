<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/event')]
class EventController extends AbstractController
{
    #[Route('/', name: 'app_event')]
    public function listEvents(EventRepository $er): Response
    {
        $listEvents = $er->findAll();
        return $this->render('event/listEvents.html.twig', [
            'listEvents' => $listEvents,
        ]);
    }

    #[Route('/new', name: 'app_new')]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_event');
        }
        return $this->render('event/new.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'event_delete')]
    public function delete(EntityManagerInterface $em, EventRepository $er, $id)
    {
        $event = $er->find($id);
        $em->remove($event);
        $em->flush();
        return $this->redirectToRoute('app_event');
    }

    #[Route('/edit/{id}', name: 'event_update')]
    public function edit(Request $request, EntityManagerInterface $em, EventRepository $er, $id)
    {
        $event = $er->find($id);
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('app_event');
        }
        return $this->render('event/edit.html.twig', [
            'formEvent' => $form->createView(),
        ]);
    }
}
