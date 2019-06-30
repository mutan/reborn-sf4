<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Card;
use App\Entity\Edition;
use App\Entity\Element;
use App\Entity\Liquid;
use App\Entity\Rarity;
use App\Entity\Subtype;
use App\Entity\Supertype;
use App\Entity\Type;
use App\Repository\ArtistRepository;
use App\Repository\EditionRepository;
use App\Repository\ElementRepository;
use App\Repository\LiquidRepository;
use App\Repository\RarityRepository;
use App\Repository\SubtypeRepository;
use App\Repository\SupertypeRepository;
use App\Repository\TypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    private $parameterBag;
    private $dataDir;
    private $artistRepository;
    private $editionRepository;
    private $elementRepository;
    private $liquidRepository;
    private $rarityRepository;
    private $subtypeRepository;
    private $supertypeRepository;
    private $typeRepository;

    public function __construct(
        ParameterBagInterface $parameterBag,
        ArtistRepository $artistRepository,
        EditionRepository $editionRepository,
        ElementRepository $elementRepository,
        LiquidRepository $liquidRepository,
        RarityRepository $rarityRepository,
        SubtypeRepository $subtypeRepository,
        SupertypeRepository $supertypeRepository,
        TypeRepository $typeRepository
    )
    {
        $this->parameterBag = $parameterBag;
        $this->dataDir = $this->parameterBag->get('kernel.root_dir')
            . DIRECTORY_SEPARATOR . 'DataFixtures'
            . DIRECTORY_SEPARATOR . 'data'
            . DIRECTORY_SEPARATOR;
        $this->artistRepository = $artistRepository;
        $this->editionRepository = $editionRepository;
        $this->elementRepository = $elementRepository;
        $this->liquidRepository = $liquidRepository;
        $this->rarityRepository = $rarityRepository;
        $this->subtypeRepository = $subtypeRepository;
        $this->supertypeRepository = $supertypeRepository;
        $this->typeRepository = $typeRepository;
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

        /* Cards */
        foreach ($this->getJsonAsObj('cards.json') as $item) {
            $card = new Card();
            foreach ($item->artists as $artist) {
                $card->addArtist($this->artistRepository->findOneBy(['name' => $artist], []));
            }
            foreach ($item->elements as $element) {
                $card->addElement($this->elementRepository->findOneBy(['name' => $element], []));
            }
            foreach ($item->liquids as $liquid) {
                $card->addLiquid($this->liquidRepository->findOneBy(['name' => $liquid], []));
            }
            foreach ($item->supertypes as $supertype) {
                $card->addSupertype($this->supertypeRepository->findOneBy(['name' => $supertype], []));
            }
            foreach ($item->subtypes as $subtype) {
                $card->addSubtype($this->subtypeRepository->findOneBy(['name' => $subtype], []));
            }
            foreach ($item->types as $type) {
                $card->addType($this->typeRepository->findOneBy(['name' => $type], []));
            }
            $card->setEdition($this->editionRepository->findOneBy(['name' => $item->edition], []));
            $card->setRarity($this->rarityRepository->findOneBy(['name' => $item->rarity], []));
            $card->setCost($item->cost);
            $card->setName($item->name);
            $card->setImage($item->image);
            $card->setLives($item->lives);
            $card->setFlying($item->flying);
            $card->setMovement($item->movement);
            $card->setPowerWeak($item->power_weak);
            $card->setPowerMedium($item->power_medium);
            $card->setPowerStrong($item->power_strong);
            $card->setText($item->text);
            $card->setFlavor($item->flavor);
            $card->setNumber($item->number);
            $card->setErratas($item->erratas);
            $card->setComments($item->comments);
            $manager->persist($card);
        }
        $manager->flush();
    }
}
