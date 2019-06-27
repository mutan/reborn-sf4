<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Edition;
use App\Entity\Element;
use App\Entity\Liquid;
use App\Entity\Rarity;
use App\Entity\Subtype;
use App\Entity\Supertype;
use App\Entity\Type;
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

        /* Elements */
        foreach ($this->getJsonAsObj('elements.json') as $item) {
            $element = new Element();
            $element->setName($item->name);
            $element->setImage($item->image);
            $manager->persist($element);
        }
        $manager->flush();

        /* Liquids */
        foreach ($this->getJsonAsObj('liquids.json') as $item) {
            $liquid = new Liquid();
            $liquid->setName($item->name);
            $liquid->setImage($item->image);
            $manager->persist($liquid);
        }
        $manager->flush();

        /* Rarities */
        foreach ($this->getJsonAsObj('rarities.json') as $item) {
            $rarity = new Rarity();
            $rarity->setName($item->name);
            $rarity->setImage($item->image);
            $manager->persist($rarity);
        }
        $manager->flush();

        /* Subtypes */
        foreach ($this->getJsonAsObj('subtypes.json') as $item) {
            $subtype = new Subtype();
            $subtype->setName($item->name);
            $manager->persist($subtype);
        }
        $manager->flush();

        /* Supertypes */
        foreach ($this->getJsonAsObj('supertypes.json') as $item) {
            $supertype = new Supertype();
            $supertype->setName($item->name);
            $manager->persist($supertype);
        }
        $manager->flush();

        /* Types */
        foreach ($this->getJsonAsObj('types.json') as $item) {
            $type = new Type();
            $type->setName($item->name);
            $manager->persist($type);
        }
        $manager->flush();
    }
}
