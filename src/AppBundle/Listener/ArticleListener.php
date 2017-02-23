<?php

namespace AppBundle\Listener;


use AppBundle\Entity\Article;
use AppBundle\Service\UnlinkService;
use AppBundle\Service\UploadService;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;




class ArticleListener
{
	


     public function prePersist(Article $entity, LifecycleEventArgs $args)
    {
        $createdAt = new \DateTime('now');
        $entity->setDateArticle($createdAt);
               

        /*recuperation de l'image
        $image = $entity->getImage();
       //die(dump($image));
        if(empty($image))
        {
            $filename = "nomfichier.png" ;
        }
        else
        {
            $filename = $this->uploadService->upload($image);
        }


        //non unique ds la BDD
        $entity->setImage($filename);*/
    }


}