<?php

namespace AppBundle\Model;



class MethodModel
{
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


        return JsonResponse::create($_POST);
    }
}