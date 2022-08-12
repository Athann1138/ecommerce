<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('admin/user')]
class UserController extends AbstractController
{
  #[Route('/', name: 'app_user_index', methods: ['GET'])]
  public function index(UserRepository $userRepository): Response
  {
    return $this->render('user/index.html.twig', [
      'users' => $userRepository->findAll(),
    ]);
  }

  #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
  public function new(
    Request $request,
    UserRepository $userRepository,
    UserPasswordHasherInterface $userPasswordHasher,
    MailerInterface $mailer
  ): Response {
    $user = new User();
    $form = $this->createForm(
      RegistrationFormType::class,
      $user,
      [
        'nom' => true,
        'prenom' => true,
        'email' => true,
        'roles' => true,
      ]
    );
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

      $mdp = '123456';

      $user->setPassword(
        $userPasswordHasher->hashPassword(
          $user,
          $mdp
        )
      );
      $userRepository->add($user, true);

      $email = (new TemplatedEmail())
        ->from(new Address('no-reply@ecommerce.com', 'Ecommerce - No Reply'))
        ->to($user->getEmail())
        ->subject('Inscription sur le Ecommerce')
        ->htmlTemplate('user/email.html.twig')
        ->context([
          'mdp' => $mdp,
          'user' => $user
        ]);

      $mailer->send($email);

      return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/new.html.twig', [
      'user' => $user,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
  public function show(User $user): Response
  {
    return $this->render('user/show.html.twig', [
      'user' => $user,
    ]);
  }

  #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, User $user, UserRepository $userRepository): Response
  {
    $form = $this->createForm(
      RegistrationFormType::class,
      $user,
      [
        'roles' => true,
      ]
    );
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $userRepository->add($user, true);

      return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/edit.html.twig', [
      'user' => $user,
      'form' => $form,
    ]);
  }

  #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
  public function delete(Request $request, User $user, UserRepository $userRepository): Response
  {
    if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
      $acces = false;
      $userCo = $this->getUser();

      if ($userCo == $user) {
        $this->container->get('security.token_storage')->setToken(null);
        $acces = true;
      }
      // supprimer les foreign keys de user
      $userRepository->remove($user, true);

      if ($acces)
        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);
    }

    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
  }
}
