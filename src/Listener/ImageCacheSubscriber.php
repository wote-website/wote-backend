<?php

namespace App\Listener;

use App\Entity\Criterion;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    
	private $cacheManager;
	private $helper;

	public function __construct(CacheManager $cacheManager, UploaderHelper $helper)
	{
		$this->cacheManager = $cacheManager;
		$this->helper = $helper;
	}


    public function getSubscribedEvents()
    {
        // return the subscribed events, their methods and priorities
        return [
            	'preUpdate', 
                'preRemove',
        ];
    }


    public function preRemove(LifecycleEventArgs $args) {
        $criterion = $args->getEntity();
        if (!$criterion instanceof Criterion) {
            return;
        }
        $this->cacheManager->remove($this->helper->asset($criterion, 'logoFile'));
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $criterion = $args->getEntity();
        
        if (!$criterion instanceof Criterion){
            return;
        }

        $changes = $args->getEntityChangeSet();


        if (array_key_exists('logoFilename', $changes) && $criterion->getLogoFile() instanceof UploadedFile){

            $oldLogoFilename = $changes['logoFilename'][0];
            $criterion->setLogoFilename($oldLogoFilename);
            $this->helper->asset($criterion, 'logoFile');
            $this->cacheManager->remove($this->helper->asset($criterion, 'logoFile'));
            $criterion->setLogoFilename(null);
        }
        
    }


}