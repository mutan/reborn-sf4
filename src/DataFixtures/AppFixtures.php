<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Edition;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $parameterBag;
    private $dataDir;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        $this->dataDir = $this->parameterBag->get('kernel.root_dir')
            . DIRECTORY_SEPARATOR . 'DataFixtures'
            . DIRECTORY_SEPARATOR . 'data'
            . DIRECTORY_SEPARATOR;
    }

    public function getJsonAsObj($fileName)
    {
        return json_decode(file_get_contents($this->dataDir . $fileName));
    }

    public function load(ObjectManager $manager)
    {
        /* Artists */
        foreach ($this->getJsonAsObj('artists.json') as $item) {
            $artist = new Artist();
            $artist->setName($item->name);
            $manager->persist($artist);
        }
        $manager->flush();

        /* Editions */
        foreach ($this->getJsonAsObj('editions.json') as $item) {
            $edition = new Edition();
            $edition->setNumber($item->number);
            $edition->setName($item->name);
            $edition->setImage($item->image);
            $edition->setCode($item->code);
            $edition->setQuantity($item->quantity);
            $edition->setDescription($item->description);
            $manager->persist($edition);
        }
        $manager->flush();
    }
}
