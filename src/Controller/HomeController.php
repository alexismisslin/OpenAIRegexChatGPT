<?php

namespace App\Controller;

use App\Form\RegexType;
use App\Service\OpenAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, OpenAIService $openAIService): Response
    {
        $form = $this->createForm(RegexType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $json = $openAIService->getHistory($data['regex']); // on passe au service la regex récupérée à la validation du formulaire
            dd($json);
        }

        return $this->render('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
