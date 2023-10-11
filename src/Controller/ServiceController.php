<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Country;
use App\Entity\Mission;
use App\Entity\User;
use App\Form\ServiceType;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service", name="service_")
 * @IsGranted("ROLE_USER")
 * @method User getUser()
 */
class ServiceController extends AbstractController
{
    /** @Route("/", name="new") */
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UploaderHelper $uploaderHelper
    ): Response
    {
        $service = new Mission();
        $form = $this->createForm(ServiceType::class, $service);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $countryExist = null;
            if ($service->getCountry()) {
                $countryExist = $entityManager->getRepository(Country::class)->findOneBy(['reference' => $service->getCountry()->getReference()]);

                if (!$countryExist) {
                    $entityManager->persist($service->getCountry());
                } else {
                    $service->setCountry($countryExist);
                }
            }

            if ($service->getCity()) {
                $cityExist = $entityManager->getRepository(City::class)->findOneBy(['reference' => $service->getCity()->getReference()]);

                if (!$cityExist) {
                    $entityManager->persist($service->getCity());
                } else {
                    $service->setCity($cityExist);
                }

                if ($service->getCountry()) {
                    $service->getCity()->setCountry($service->getCountry());
                }
            }

            if ($service->getCountry()) {
                $countryExist = $entityManager->getRepository(Country::class)->findOneBy(['reference' => $service->getCountry()->getReference()]);

                if (!$countryExist) {
                    $entityManager->persist($service->getCountry());
                } else {
                    $service->setCountry($countryExist);
                }
            }

            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $form->get('image')->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadMissionImage($uploadedFile, null);
                $service->setImageFile($newFilename);
            }

            $service->setUser($this->getUser());

            $this->addFlash('success', 'Votre service a été créé avec succès. Il sera examiné et publié dans les plus brefs délais.');

            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('service/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
