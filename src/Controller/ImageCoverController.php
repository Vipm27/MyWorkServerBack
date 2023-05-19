<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\Image;
use App\Service\FileUploader;

#[AsController]
final class ImageCoverController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader): Image
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $superhero = new Image();
        $superhero->name = $request->get('name');
        $superhero->created_at = $request->get('created_at');

        $superhero->cover = $fileUploader->upload($uploadedFile);

        return $superhero;
    }
}