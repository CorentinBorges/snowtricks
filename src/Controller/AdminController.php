<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangeUserDataFormType;
use App\Form\ImageFormType;
use App\Repository\UserRepository;
use App\Service\CheckUserEdit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_user_edit")
     */
    public function editUser(Request $request, EntityManagerInterface $entityManager, CheckUserEdit $checkUserEdit, UserRepository $userRepository)
    {
        /** @var  User $user */
        $user = $this->getUser();
        $imageForm = $this->createForm(ImageFormType::class);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted()) {
            $image = $imageForm['image']->getData();
            $userRepository->editAvatar($image, $user);
            return $this->redirectToRoute('admin_user_edit');
        }

        if (!empty($request->request->get('username')) && !empty($request->request->get('email'))) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $token = $request->request->get('_csrf_token');
            if ($checkUserEdit->isValid($user,$username,$email,$token)) {
                $userRepository->editUser($user, $request);
                $this->addFlash("success","Vos modifications ont bien été enregistrée");
            }
            else{
                foreach ($checkUserEdit->getErrors() as $error) {
                    $this->addFlash("error",$error);
                }
            }
            return $this->redirectToRoute('admin_user_edit');
        }


        return $this->render('admin/editUser.html.twig', [
            'user'=>$user,
            'imageForm' => $imageForm->createView(),
        ]);
    }
}
