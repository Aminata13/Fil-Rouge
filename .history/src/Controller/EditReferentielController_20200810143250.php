<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EditReferentielController extends AbstractController
{
    /**
     * @Route(
     *     name="edit_referentiel",
     *     path="/api/admin/referentiels/{id}",
     *     methods={"POST"}
     * )
     */
    public function editReferentiel(Request $request)
    {
        dd($request->request->h());
    }
}
