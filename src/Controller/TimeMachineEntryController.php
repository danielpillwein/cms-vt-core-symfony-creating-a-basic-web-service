<?php

namespace App\Controller;

use App\Entity\TimeMachineEntry;
use App\Form\TimeMachineEntryType;
use App\Repository\TimeMachineEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/time/machine/entry')]
class TimeMachineEntryController extends AbstractController
{
    #[Route('/', name: 'app_time_machine_entry_index', methods: ['GET'])]
    public function index(TimeMachineEntryRepository $timeMachineEntryRepository): Response
    {
        return $this->render('time_machine_entry/index.html.twig', [
            'time_machine_entries' => $timeMachineEntryRepository->findAll(),
        ]);
    }
    #[Route('/random', name: 'app_time_machine_entry_random', methods: ['GET'])]
    public function random(TimeMachineEntryRepository $timeMachineEntryRepository): Response
    {
        $allEntries = $timeMachineEntryRepository->findAll();

        $entryCount = $timeMachineEntryRepository->count([]);


        return $this->render('time_machine_entry/random.html.twig', [
            'time_machine_entry' => $allEntries[rand(0,$entryCount -1)],
        ]);
    }

    #[Route('/new', name: 'app_time_machine_entry_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $timeMachineEntry = new TimeMachineEntry();
        $form = $this->createForm(TimeMachineEntryType::class, $timeMachineEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timeMachineEntry);
            $entityManager->flush();

            return $this->redirectToRoute('app_time_machine_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        $json = json_decode($request->getContent(), true);
        if ($json !== null){
            $timeMachineEntry->setName($json['name']);
            $timeMachineEntry->setResourceURL($json['resource_url']);
            $entityManager->persist($timeMachineEntry);
            $entityManager->flush();


            return $this->redirectToRoute('app_time_machine_entry_index');
        }

        return $this->render('time_machine_entry/new.html.twig', [
            'time_machine_entry' => $timeMachineEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_machine_entry_show', methods: ['GET'])]
    public function show(TimeMachineEntry $timeMachineEntry): Response
    {
        return $this->render('time_machine_entry/show.html.twig', [
            'time_machine_entry' => $timeMachineEntry,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_time_machine_entry_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TimeMachineEntry $timeMachineEntry, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimeMachineEntryType::class, $timeMachineEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_time_machine_entry_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('time_machine_entry/edit.html.twig', [
            'time_machine_entry' => $timeMachineEntry,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_time_machine_entry_delete', methods: ['POST'])]
    public function delete(Request $request, TimeMachineEntry $timeMachineEntry, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$timeMachineEntry->getId(), $request->request->get('_token'))) {
            $entityManager->remove($timeMachineEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_time_machine_entry_index', [], Response::HTTP_SEE_OTHER);
    }
}
