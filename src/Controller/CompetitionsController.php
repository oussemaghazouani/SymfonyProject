<?php

namespace App\Controller;

use App\Entity\Competitions;
use App\Form\Competitions1Type;
use App\Repository\CompetitionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\InvolvedEventsRepository;
use App\Entity\InvolvedEvents;






#[Route('/competitions')]
final class CompetitionsController extends AbstractController
{
    #[Route(name: 'app_competitions_index')]
public function index(CompetitionsRepository $competitionsRepository): Response
{
    $competitions = $competitionsRepository->findAll();
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('competitions/index.html.twig', [
        'competitions' => $competitions,
        'percentages' => $percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}


#[Route('/new', name: 'app_competitions_new')]
public function new(Request $request, ManagerRegistry $doctrine): Response
{
    $competition = new Competitions();
    $form = $this->createForm(Competitions1Type::class, $competition);
    $form->handleRequest($request);
    

    if ($form->isSubmitted()) {
        $em=$doctrine->getManager();
        $em->persist($competition);
        $em->flush();
        return $this->redirectToRoute('app_competitions_index');
    }
    return $this->renderForm('competitions/new.html.twig', [
        'form' => $form,
        
        
    ]);
}

    #[Route('/{id}', name: 'app_competitions_show')]
    public function show(Competitions $competition): Response
    {
        return $this->render('competitions/show.html.twig', [
            'competition' => $competition,
            
        ]);
    }

    #[Route('/{id}/edit', name: 'app_competitions_edit')]
    public function edit($id, CompetitionsRepository $rep, ManagerRegistry $doctrine, Request $request): Response
    {
        
        $competition = $rep->find($id);
        $form = $this->createForm(Competitions1Type::class, $competition);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $em = $doctrine->getManager();
            $em->flush();
            return $this->redirectToRoute('app_competitions_index');
        }
    
        return $this->renderForm('competitions/edit.html.twig', [
            'form' => $form,
        ]);
    }
    

    #[Route('/{id}/delete', name: 'app_competitions_delete')]
    public function delete($id, CompetitionsRepository $rep, ManagerRegistry $doctrine): Response
    {
        $competition = $rep->find($id);
        $em = $doctrine->getManager();
        $em->remove($competition);
        $em->flush();
        return $this->redirectToRoute('app_competitions_index');
    }
    

    #[Route('/competitions/search', name: 'app_competitions_search')]
    public function search(Request $request, CompetitionsRepository $competitionsRepository): Response
    {
        $query = $request->query->get('q', ''); 
        $competitions = $competitionsRepository->searchByQuery($query); 
        $monthPercentages = $this->month($competitions);
        $percentages = $this->calculatePercentages($competitions);
        $percentagesmornm=$this->calculatePercentages2($competitions);  
        return $this->render('competitions/index.html.twig', [
            'competitions' => $competitions,
            'percentages'=>$percentages,
            'month'=> $monthPercentages  ,
            'percentagesmornm'=>$percentagesmornm ,
        ]);
    }
    #[Route('/competitions/trier', name: 'app_competitions_trier')]
public function trier(CompetitionsRepository $competitionsRepository): Response
{
    $competitions = $competitionsRepository->findBy([], ['dateC' => 'ASC']); 
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('competitions/index.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,

    ]);
}
#[Route('/competitions/triername', name: 'app_competitions_trier_name')]
public function triername(CompetitionsRepository $competitionsRepository): Response
{
    $competitions = $competitionsRepository->findBy([], ['name' => 'ASC']); 
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);
    $monthPercentages = $this->month($competitions);
    return $this->render('competitions/index.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}

public function calculatePercentages(array $competitions): array
{
    $totalCompetitions = count($competitions);

    if ($totalCompetitions === 0) {
        return ['bodybuilding' => 0, 'kickboxing' => 0, 'boxing' => 0];
    }
    $typeCounts = ['bodybuilding' => 0, 'kickboxing' => 0, 'boxing' => 0];

    foreach ($competitions as $competition) {
        $type = $competition->getType();
        if (array_key_exists($type, $typeCounts)) {
            $typeCounts[$type]++;
        }
    }
    $percentages = [];
    foreach ($typeCounts as $type => $count) {
        $percentages[$type] = ($count / $totalCompetitions) * 100;
    }

    return $percentages;
}
public function calculatePercentages2(array $competitions): array
{
    $totalCompetitions = count($competitions);

    if ($totalCompetitions === 0) {
        return ['martial art' => 0, 'non martial art' => 0];
    }
    $typeCounts =  ['martial art' => 0, 'non martial art' => 0];

    foreach ($competitions as $competition) {
        $type = $competition->getTypeCompetition()?->getName();
        if (array_key_exists($type, $typeCounts)) {
            $typeCounts[$type]++;
        }
    }
    $percentagesmornm = [];
    foreach ($typeCounts as $type => $count) {
        $percentagesmornm[$type] = ($count / $totalCompetitions) * 100;
    }

    return $percentagesmornm;
}
public function month(array $competitions) {
    $monthcounts = [
        'janvier' => 0, 'fevrier' => 0, 'mars' => 0, 'avril' => 0,
        'mai' => 0, 'juin' => 0, 'juillet' => 0, 'aout' => 0,
        'septembre' => 0, 'octobre' => 0, 'novembre' => 0, 'decembre' => 0
    ];

    
    $mtn = [
        'janvier' => 1, 'fevrier' => 2, 'mars' => 3, 'avril' => 4,
        'mai' => 5, 'juin' => 6, 'juillet' => 7, 'aout' => 8,
        'septembre' => 9, 'octobre' => 10, 'novembre' => 11, 'decembre' => 12
    ];

    
    foreach ($competitions as $competition) {
        $date = $competition->getDateC();  
        $monthnumber = $date->format('n');  
        foreach ($mtn as $month => $number) {
            if ($number == $monthnumber) {
                $monthcounts[$month]++;
                break; 
            }
        }
    }


    $max = max($monthcounts);
    $min = min($monthcounts);

    $maxmonth = array_search($max, $monthcounts);
    $minmonth = array_search($min, $monthcounts);
    return [
        'monthcounts' => $monthcounts,
        'maxmonth' => $maxmonth,
        'maxcount' => $max,
        'minmonth' => $minmonth,
        'mincount' => $min,
    ];

}
#[Route('/competitions/list', name: 'app_competitions_index2')]
public function index2(CompetitionsRepository $competitionsRepository,InvolvedEventsRepository $involvedEventsRepository): Response
{
    $competitions = $competitionsRepository->findAll();
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);
     $participatedCompetitions = $involvedEventsRepository->findByParticipation(true);

     $competitionsParticipated = [];
     foreach ($participatedCompetitions as $event) {
         $competitionsParticipated[] = $event->getCompetition();
     }

    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'percentages' => $percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
        'competitionsParticipated' => $competitionsParticipated,
    ]);
}
#[Route('/{id}/detailfront', name: 'app_competitions_show2')]
public function show2(Competitions $competition): Response
{
    return $this->render('showdash.html.twig', [
        'competition' => $competition,
        
    ]);
}
#[Route('/competitions/List/search2', name: 'app_competitions_search2')]
public function search2(Request $request, CompetitionsRepository $competitionsRepository): Response
{
    $query = $request->query->get('q', ''); 
    $competitions = $competitionsRepository->searchByQuery($query); 
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);  
    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}
#[Route('/competitions/compete/{id}', name: 'app_competitions_compete')]
public function compete(int $id, EntityManagerInterface $entityManager): Response
{
    
    $competition = $entityManager->getRepository(Competitions::class)->find($id);

    if (!$competition) {
        throw $this->createNotFoundException('Competition not found');
    }

    
    $involvedEvent = $entityManager->getRepository(InvolvedEvents::class)
        ->findOneBy(['competition' => $competition]);

    if ($involvedEvent && $involvedEvent->isParticipating()) {
        
        return $this->redirectToRoute('app_competitions_index2'); 
    }

    if (!$involvedEvent) {
        $involvedEvent = new InvolvedEvents();
        $involvedEvent->setCompetition($competition);
    }

    $involvedEvent->setIsParticipating(true); 

    
    $entityManager->persist($involvedEvent);
    $entityManager->flush();

  
    return $this->redirectToRoute('app_competitions_index2');
}

#[Route('/competitions/annuler/{id}', name: 'app_competitions_annuler')]
public function annuler(int $id, EntityManagerInterface $entityManager): Response
{
    
    $competition = $entityManager->getRepository(Competitions::class)->find($id);

    if (!$competition) {
        throw $this->createNotFoundException('Competition not found');
    }

    $involvedEvent = $entityManager->getRepository(InvolvedEvents::class)
        ->findOneBy(['competition' => $competition]);

    if (!$involvedEvent || !$involvedEvent->isParticipating()) {
        return $this->redirectToRoute('app_competitions_index2'); 
    }

    $involvedEvent->setIsParticipating(false);

    $entityManager->flush();

    
    return $this->redirectToRoute('app_competitions_index2');
}
#[Route('/competitions/list1', name: 'app_competitions_index3')]
public function index3(CompetitionsRepository $competitionsRepository,InvolvedEventsRepository $involvedEventsRepository): Response
{
   
     $participatedCompetitions = $involvedEventsRepository->findByParticipation(true);

     $competitionsParticipated = [];
     foreach ($participatedCompetitions as $event) {
         $competitionsParticipated[] = $event->getCompetition();
     }

    return $this->render('competitionspart.html.twig', [
       
        'competitionsParticipated' => $competitionsParticipated,
    ]);
}
#[Route('/competitions/martial-art', name: 'app_competitions_martial_art')]
public function filterMartialArt(
    CompetitionsRepository $competitionsRepository
): Response {
    $competitions = $competitionsRepository->findMartialArtCompetitions();
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}

#[Route('/competitions/non-martial-art', name: 'app_competitions_non_martial_art')]
public function filterNonMartialArt(
    CompetitionsRepository $competitionsRepository
): Response {
    $competitions = $competitionsRepository->findNonMartialArtCompetitions();
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}
// src/Controller/CompetitionsController.php

#[Route('/competitions/filter77', name: 'app_competitions_filter77')]
public function filterByType(
    Request $request,
    CompetitionsRepository $competitionsRepository
): Response {
    $type = $request->query->get('type');

    $competitions = $type 
        ? $competitionsRepository->findByType($type)
        : $competitionsRepository->findAll();
        $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'types' => ['Bodybuilding', 'Kickboxing', 'Boxing'], 
        'selected_type' => $type,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,
    ]);
}
#[Route('/competitions/trier88', name: 'app_competitions_trier88')]
public function trier88(CompetitionsRepository $competitionsRepository): Response
{
    $competitions = $competitionsRepository->findBy([], ['dateC' => 'ASC']); 
    $monthPercentages = $this->month($competitions);
    $percentages = $this->calculatePercentages($competitions);
    $percentagesmornm=$this->calculatePercentages2($competitions);

    return $this->render('dashaffiche.html.twig', [
        'competitions' => $competitions,
        'percentages'=>$percentages,
        'month'=> $monthPercentages  ,
        'percentagesmornm'=>$percentagesmornm ,

    ]);
}


}




    













    

