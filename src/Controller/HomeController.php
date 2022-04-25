<?php

namespace App\Controller;
use App\Entity\Newsletter;
use App\Form\NewsLetterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
	public function index(Request $request, EntityManagerInterface $manager): Response
	{
		$newsletter = new Newsletter();
		$form = $this->createForm(NewsLetterType::class, $newsletter);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$newsletter->setEmail($form->getData()->getEmail());
			$manager->persist($newsletter);
			$manager->flush();
			$this->addFlash(
				'success',
				"Vous recevrez prochainement notre newsletter"
			);
			return $this->redirect('/#newsletter-part');
		}
		return $this->render('home/index.html.twig',[
			'newsletter' => $newsletter,
			'form' => $form->createView()
		]);
    }
}
