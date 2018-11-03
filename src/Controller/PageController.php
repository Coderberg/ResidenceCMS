<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends BaseController
{
    /**
     * @Route("/info/{slug}", methods={"GET"}, name="page")
     */
    public function pageShow(Page $page): Response
    {
        return $this->render('page/show.html.twig',
            [
                'site' => $this->site(),
                'page' => $page,
            ]
        );
    }
}
