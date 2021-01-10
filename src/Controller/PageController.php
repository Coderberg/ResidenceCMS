<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\FeedbackDto;
use App\Entity\Page;
use App\Form\Type\FeedbackType;
use App\Message\SendFeedback;
use App\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class PageController extends BaseController
{
    /**
     * @Route("/info/{slug}", methods={"GET|POST"}, name="page")
     */
    public function pageShow(Request $request, string $slug, MessageBusInterface $messageBus, PageRepository $pageRepository): Response
    {
        $locale = $request->isMethod(Request::METHOD_POST) ? $request->request->get('locale') : $request->getLocale();

        $page = $pageRepository->findOneBy(['locale' => $locale, 'slug' => $slug]);

        if ($page->getAddContactForm() && '' !== $page->getContactEmailAddress()) {
            $feedback = new FeedbackDto();
            $feedback->setToEmail($page->getContactEmailAddress());

            $form = $this->createForm(FeedbackType::class, $feedback);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $messageBus->dispatch(new SendFeedback($feedback));
                $this->addFlash('success', 'message.was_sent');

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
