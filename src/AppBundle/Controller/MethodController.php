<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Types\JsonArrayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MethodController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/post", name="post")
     */
    public function postAction()
    {
        if(!file_exists(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json')){
            mkdir(realpath($this->getParameter('kernel.root_dir')).'/Resources/json');
            $file = fopen(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json','w');
            fclose($file);
        }

        if (!empty($_POST)){
            $array = file_get_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json');
            $array = json_decode($array,true);

            $array[] = $_POST;
            $json = json_encode($array);

            file_put_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json',$json);
        }


        return JsonResponse::create($json);
    }

    /**
     * @Route("/get", name="getAll")
     */
    public function getAction()
    {
        $records = file_get_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json');
        $records = json_decode($records,true);

        return new JsonResponse($records);
    }

    /**
     * @Route("/get/{id}", name="getId")
     */
    public function getIdAction($id)
    {
        $records = file_get_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json');
        $records = json_decode($records,true);
            foreach ($records as $record)
            {
                if ($record['id'] == $id){
                    $data = array($id,$name = $record['name'],$record['surname']);
                }
            }

        return new JsonResponse($data);
    }


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction($id){
        $records = file_get_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json');
        $records = json_decode($records,true);
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE'){
            foreach ($records as $i => $record)
            {
                if ($record['id'] == $id){
                    unset ($records[$i]);
                    $records = json_encode($records);
                    file_put_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json',$records);
                    break;
                }
            }
        }
        return new JsonResponse($record);
    }

    /**
     * @Route("/put/{id}", name="put")
     */
    public function putAction($id){
        $records = file_get_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json');
        $records = json_decode($records,true);
        if ($_SERVER['REQUEST_METHOD'] == 'PUT'){
            $request = file_get_contents("php://input","r");
            parse_str($request,$input);
            foreach ($records as $i => $record)
            {
                if ($record['id'] == $id){
                    $record['name'] = $input['name'];
                    $record['surname'] = $input['surname'];
                    $records[$i] = $record;
                    $records = json_encode($records);
                    file_put_contents(realpath($this->getParameter('kernel.root_dir')).'/Resources/json/file.json',$records);
                    break;
                }
            }
        }
        return new JsonResponse($record);
    }
}
