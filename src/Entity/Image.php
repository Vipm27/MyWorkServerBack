<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\ImageCoverController;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="Image")
 * @ApiResource(
 *   normalizationContext={"groups" = {"read"}},
 *   denormalizationContext={"groups" = {"write"}},
 *   collectionOperations={
 *     "get",
 *     "post" = {
 *       "controller" = ImageCoverController::class,
 *       "deserialize" = false,
 *       "openapi_context" = {
 *         "requestBody" = {
 *           "description" = "File upload",
 *           "required" = true,
 *           "content" = {
 *             "multipart/form-data" = {
 *               "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                   "name" = {
 *                     "description" = "The name of the Image",
 *                     "type" = "string",
 *                     "example" = "Leonardo Da Vinchy",
 *                   },
 *                   "file" = {
 *                     "type" = "string",
 *                     "format" = "binary",
 *                     "description" = "Upload a cover image of Image",
 *                   },
 *                 },
 *               },
 *             },
 *           },
 *         },
 *       },
 *     },
 *   },
 *   itemOperations={
 *     "get",
 *     "patch",
 *     "delete",
 *     "put",
 *   }
 * )
 */
#[ApiFilter(NumericFilter::class, properties: ['id'])]
#[ApiFilter(DateFilter::class, properties: ['created_at'])]
#[ApiFilter(OrderFilter::class, properties: ['id', 'created_at'], arguments: ['orderParameterName' => 'order'])]
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(length=70)
     * @Assert\NotBlank()
     * @Groups({"read", "write"})
     */
    public string $name;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    public ?\DateTime $created_at = null;

    /**
     * @param string $cover
     *
     * @ORM\Column()
     * @Groups({"read", "write"})
     * @ApiProperty(
     *   iri="http://schema.org/image",
     *   attributes={
     *     "openapi_context"={
     *       "type"="string",
     *     }
     *   }
     * )
     */
    public ?string $cover = null;


    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->created_at == null) {
            $this->created_at = new \DateTime('now');
        }
    }

    public function __toString()
    {
        return $this->name;
    }

}