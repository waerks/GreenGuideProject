<?php

namespace App\Controller;

use App\Entity\Element;
use App\Repository\ElementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepertoireController extends AbstractController
{
    private $doctrine;
    private $elementRepository;

    public function __construct(ManagerRegistry $doctrine, ElementRepository $elementRepository)
    {
        $this->doctrine = $doctrine;
        $this->elementRepository = $elementRepository;
    }
    
    #[Route('/repertoire', name: 'repertoire')]
    public function index(): Response
    {
        $elements = $this->doctrine->getRepository(Element::class)->findAll();

        $vars = [
            'elements' => $elements
        ];

        return $this->render('repertoire/repertoire.html.twig', $vars);
    }
    #[Route('/repertoire/{category}/{nom}', name: 'repertoire_detail', requirements: ['category' => 'fruit|legume|plante'])]
    public function detail(string $category, string $nom): Response
    {
        $element = $this->doctrine->getRepository(Element::class)->findOneBy(['nom' => $nom]);

        $vars = [
            'element' => $element,
        ];

        return $this->render('repertoire/repertoire_detail.html.twig', $vars);
    }

    #[Route('/repertoire/filter', name: 'repertoire_filter')]
    public function filter(Request $request): Response
    {
        $category = $request->query->get('category');

        if ($category === 'all') {
            $elements = $this->elementRepository->findAll();
        } else {
            $elements = $this->elementRepository->findByCategory($category);
        }

        $vars = [
            'elements' => $elements,
        ];

        return $this->render('repertoire/_element.html.twig', $vars);
    }

}