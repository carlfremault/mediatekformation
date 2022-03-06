<?php

namespace App\Controller;

use App\Repository\FormationRepository;
use App\Repository\NiveauRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la page qui liste les formations, côté front.
 *
 * @author emds
 */
class FormationsController extends AbstractController {

    private const PAGEFORMATIONS = "pages/formations.html.twig";

    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * @var NiveauRepository
     */
    private $niveauRepository;

    /**
     *
     * @var Niveaux[]
     */
    private $niveaux;

    /**
     * Constructeur. Valorise $formationRepository, $niveauRepository. Remplit le tableau $niveaux avec les niveaux de la base de données.
     * @param FormationRepository $formationRepository
     */
    function __construct(FormationRepository $formationRepository, NiveauRepository $niveauRepository) {
        $this->formationRepository = $formationRepository;
        $this->niveauRepository = $niveauRepository;
        $this->niveaux = $this->niveauRepository->findAll();
    }

    /**
     * Route principale pour la page front des formations. Affiche tous les formations disponibles. Permet de trier sur le titre et la date de parution, et de filtrer sur le titre ou le niveau.
     * @Route("/formations", name="formations")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->formationRepository->findAllOrderBy('publishedAt', 'DESC');
        return $this->render(self::PAGEFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * Route qui permet de trier la liste des formations par rapport à un des champs par ordre ascendant ou descendant. 
     * @Route("/formations/tri/{champ}/{ordre}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
        return $this->render(self::PAGEFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * Route qui permet de filtrer les formations par rapport à un des champs et pour une valeur saisie. Utilisé par le menu déroulant qui filtre les niveaux.
     * @Route("/formations/filter/{champ}/{valeur}", name="formations.filter")
     * @param type $champ
     * @param type $valeur
     * @return Response
     */
    public function filter($champ, $valeur = null): Response {
        $formations = $this->formationRepository->findByEqualValue($champ, $valeur);
        return $this->render(self::PAGEFORMATIONS, [
                    'formations' => $formations,
                    'niveaux' => $this->niveaux
        ]);
    }

    /**
     * Route qui permet de filtrer les formations par rapport à un champ contenant une valeur saisie. Utilisé pour filtrer sur les titres des formations.
     * @Route("/formations/recherche/{champ}", name="formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @return Response
     */
    public function findAllContain($champ, Request $request): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            return $this->render(self::PAGEFORMATIONS, [
                        'formations' => $formations,
                        'niveaux' => $this->niveaux
            ]);
        }
        return $this->redirectToRoute("formations");
    }

    /**
     * Route qui permet d'afficher les détails d'une formation sélectionnée.
     * @Route("/formations/formation/{id}", name="formations.showone")
     * @param type $id
     * @return Response
     */
    public function showOne($id): Response {
        $formation = $this->formationRepository->find($id);
        return $this->render("pages/formation.html.twig", [
                    'formation' => $formation
        ]);
    }

}
