<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\User;
use App\Form\ImageFormType;
use App\Repository\UserRepository;
use App\Service\CheckUserEdit;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AdminController
 * @package App\Controller
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_user_edit")
     * @param Request $request
     * @param CheckUserEdit $checkUserEdit
     * @param UserRepository $userRepository
     * @return RedirectResponse|Response
     */
    public function editUser(Request $request, CheckUserEdit $checkUserEdit, UserRepository $userRepository,EntityManagerInterface $entityManager)
    {
        /** @var  User $user */
        $user = $this->getUser();
        $imageForm = $this->createForm(ImageFormType::class,null,['is_user_edit'=>true]);

        $imageForm->handleRequest($request);
        if ($imageForm->isSubmitted() && $imageForm->isValid()) {
            $image=$imageForm->getData();
            $imageFile = $imageForm['image']->getData();
            $userRepository->editAvatar($image,$imageFile, $user);
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
            'imgForm' => $imageForm->createView(),
        ]);
    }

}
