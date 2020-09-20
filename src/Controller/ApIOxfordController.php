<?php

namespace App\Controller;

use App\Entity\Oxford;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ApIOxfordController extends AbstractController
{
    /**
     * @Route("/", name="oxford")
     */
    public function OxfordData(Request $request, ValidatorInterface $validator)
    {
        $word = $request->get('word');
        if (!empty($word)) {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', "https://od-api.oxforddictionaries.com/api/v2/entries/en-gb/{$word}", [
                'headers' => [
                    'app_id' => '5de77d25',
                    'app_key' => 'f63a0a48fb89cd0e7d01a5acb0dfead9',
                ]
            ]);
            $entityManager = $this->getDoctrine()->getManager();

            $model = new Oxford();
            $model->setName($word);
            $entityManager->persist($model);

            $entityManager->flush();

            $data = json_decode($response->getBody()->getContents());
        }

        $history = $this->getDoctrine()
            ->getRepository(Oxford::class)
            ->findAllUniqueName();
        return $this->render('base.html.twig',
            [
                'data' => !empty($data) ? $data : null,
                'histories' => !empty($history) ? $history : null,
            ]
        );
    }

}