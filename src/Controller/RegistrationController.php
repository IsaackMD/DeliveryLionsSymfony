<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
// use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
// use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    private $entityManager;
    private $security;
    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        // Comprobar si el formulario está enviado y es válido
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                try {
                    $foto = $request->files->get('fotito');
                    if ($foto) {
                        $rutaUploads = $this->getParameter('kernel.project_dir') . '/public/imgs/FotosUsu';
                        $fotoName = md5(uniqid()) . '.' . $foto->guessExtension();
                        $foto->move($rutaUploads, $fotoName);
                    }

                    $user->setNombre($form->get('nombre')->getData())
                        ->setEmail($form->get('email')->getData())
                        ->setApellidoP($form->get('ApellidoP')->getData())
                        ->setApellidoM($form->get('ApellidoM')->getData())
                        ->setUsername($form->get('username')->getData())
                        ->setSexo($form->get('Sexo')->getData())
                        ->setTelefono($form->get('Telefono')->getData())
                        ->setFechaNa($form->get('Fecha_Na')->getData())
                        ->setFoto($fotoName)
                        ->setRoles(['Usuario']);

                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );

                    $entityManager->persist($user);
                    $entityManager->flush();

                    return $this->redirectToRoute('app_user');
                } catch (\Exception $e) {
                    // Manejar errores específicos
                    $error = $e->getMessage() . 'Hubo un error al procesar tu registro. Por favor, intenta de nuevo.';
                    return $this->render('registration/register.html.twig', [
                        'registrationForm' => $form,
                        'error' => $error
                    ]);
                }
            } else {
                // Si el formulario no es válido, retornar con error 422
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form,
                    'error' => 'Formulario inválido. Por favor, revisa los campos.'
                ], new Response('', 422));
            }
        }

        // Si el formulario no está enviado, solo renderizar la vista
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'error' => null
        ]);
    }


    // #[Route('/', name: 'app_index')]
    // public function index(): Response
    // {

    //     return $this->render('index.html.twig', [
    //         'controller_name' => 'RegistrationController',
    //     ]);
    // }

    #[Route('/servicios', name: 'app_service')]
    public function service(): Response
    {

        return $this->render('servicios.html.twig', [
            'controller_name' => 'RegistrationController',
        ]);
    }

    // #[Route('/verify/email', name: 'app_verify_email')]
    // public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    // {
    //     $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    //     // validate email confirmation link, sets User::isVerified=true and persists
    //     // try {
    //     //     $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
    //     // } catch (VerifyEmailExceptionInterface $exception) {
    //     //     $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

    //     //     return $this->redirectToRoute('app_register');
    //     // }

    //     // @TODO Change the redirect on success and handle or remove the flash message in your templates
    //     // $this->addFlash('success', 'Your email address has been verified.');

    //     return $this->redirectToRoute('app_register');
    // }
}
