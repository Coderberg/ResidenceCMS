<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Page;
use App\Form\Type\ContactType;
use App\Mailer\Emailer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends BaseController
{
    /**
     * @Route("/info/{slug}", methods={"GET|POST"}, name="page")
     */
    public function pageShow(Request $request, Page $page, Emailer $emailer): Response
    {
        if ($page->getAddContactForm() && '' !== $page->getContactEmailAddress()) {
            $form = $this->createForm(ContactType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $emailer->sendEmail($form, $page->getContactEmailAddress());

                return $this->redirectToRoute('page', ['slug' => $page->getSlug()]);
            }
        }

        return $this->render('page/show.html.twig',
            [
                'site' => $this->site(),
                'page' => $page,
                'form' => (!empty($form) ? $form->createView() : []),
            ]
        );
    }
}
