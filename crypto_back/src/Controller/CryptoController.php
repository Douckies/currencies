<?php
namespace App\Controller;

use App\Entity\Crypto;
use App\Repository\CryptoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CryptoController extends ApiController
{
    /**
    * @Route("/cryptos", methods="GET")
    */
    public function index(CryptoRepository $cryptoRepository)
    {
        //Fetch all the crypto currancies in the database.
        $cryptos = $cryptoRepository->findAll();

        //Serialize the array in JSON to return.
        return new Response($this->serializer->serialize($cryptos, 'json'),
            Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    /**
    * @Route("/crypto/orderCurrency", methods="POST")
    */
    public function sell(Request $request, CryptoRepository $cryptoRepository, EntityManagerInterface $em)
    {
        $parameters = json_decode($request->getContent(), true);

        $alreadyExists = $cryptoRepository->checkIfAlreadyExists($parameters['currencyToBuy'], $parameters['exchange']);

        //Check if the new currency already exists
        $cryptoRepository->sellCurrency($parameters['currencyToSell'], $parameters['exchange'], $parameters['quantityToSell']);

        //If already exists, update the database, otherwise create the entry
        if($alreadyExists) {
            $cryptoRepository->buyCurrency($parameters['currencyToBuy'], $parameters['exchange'], $parameters['quantityToBuy'], $parameters['investment']);
        } else {
            $crypto = new Crypto;
            $crypto->setPlateforme($parameters['exchange']);
            $crypto->setNom($parameters['currencyToBuy']);
            $crypto->setQtt($parameters['quantityToBuy']);
            $crypto->setInvestissement(0); 
            $em->persist($crypto);
        }

        $em->flush();

        return $this->respondCreated();
    }
}