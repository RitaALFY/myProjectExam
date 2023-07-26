<?php

namespace App\Controller\Front;

use App\Entity\Listing;
use App\Form\ListingType;
use App\Repository\BrandRepository;
use App\Repository\ListingRepository;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListingController extends AbstractController
{
    public function __construct(
        private ListingRepository $listingRepository,
        private ModelRepository $modelRepository,
        private BrandRepository $brandRepository
    )
    {
    }

    #[Route('/listing/{id}', name: 'app_front_listing_show')]
    public function show(string $id): Response
    {
        $listing = $this->listingRepository->find($id);

        if (!$listing) {
            throw $this->createNotFoundException('The listing does not exist.');
        }

        return $this->render('front/pages/listing/show.html.twig', [
            'controller_name' => 'ListingController',
            'listing' => $listing,
        ]);
    }

    #[Route('/new', name: 'app_front_listing_add')]
    public function addListing(Request $request): Response
    {
        $listing = new Listing();

        $form = $this->createForm(ListingType::class, $listing);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->listingRepository->save($listing, true);

            return $this->redirectToRoute('app_front_listing_show', ['id' => $listing->getId()]);
        }

        return $this->renderForm('front/pages/listing/add_listing.html.twig', [
            'form' => $form,
            'listing' => $listing,
        ]);
    }





}
