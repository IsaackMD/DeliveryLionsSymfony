<?php

namespace App\Controller;

use App\Entity\CatComida;
use App\Entity\Menu;
use App\Entity\Negocio;
use App\Entity\Pedido;
use App\Entity\PedidoMenu;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request; 
use PHPUnit\Util\Json;

#[Route('/user')]
class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        $comidas = $this->entityManager->getRepository(Menu::class)->findAll();
        $Negocios = $this->entityManager->getRepository(Negocio::class)->findBy(['Estatus' => true]);
        // dump($Negocios);
        // die;
    
        // Obtener el carrito asociado al usuario (aquí necesitas adaptar esta parte según cómo se almacenan los carritos en tu aplicación)
        

        $categorias = $this->entityManager->getRepository(CatComida::class)->findAll();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'comidas' => $comidas,
            'Negocios' => $Negocios,
            'categorias' => $categorias

        ]);
    }
    #[Route('/Det/Com/{id}', name: 'app_det_comida')]
    public function DetComida($id): Response
    {
        $comida = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $id]);
        $negocio = $comida->getNegocio();
        $comidas = $this->entityManager->getRepository(Menu::class)->findBy(['cat_comida' => $comida->getCatComida()]);
        // dump($comidas);
        // // dump($negocio);
        // die;
        return $this->render('user/detcomida.html.twig', [
            'comida' => $comida,
            'negocio' => $negocio,
            'comidas' => $comidas
        ]);
    }

    #[Route('/carrito', name :'app_carrito')]
    public function Carrito(): JsonResponse{
        $user = $this->getUser();
        $carrito = $this->entityManager->getRepository(Pedido::class)->findById($user,1);
        $datos = [];
        $total = 0;
        if ($carrito != null){
            foreach($carrito as $pedido){
                $pedidoMenus = $pedido->getPedidoMenus();
                foreach($pedidoMenus  as $pedidoMenu){
                    $menu = $pedidoMenu->getMenu();
                    $menus[] = [
                        'id' => $menu->getId(),
                        'nombre' => $menu->getNomMenu(),
                        'imagen' => $menu->getImagen(),
                        'precio' => $menu->getPrecio(),
                        'complemento' => $menu->getComplemento(),
                        'cantidad' => $pedidoMenu->getCantidad()
                    ];
                    $total += $pedidoMenu->getCantidad() * $menu->getPrecio();
                }
                
            }
            $datos[] = [
                'id' => $pedido->getId(),
                'menus' => $menus,
                'total' => $total
            ];
        }

        return new JsonResponse($datos);

    }
    #[Route('/add/carrito', name: 'app_add_carrito')]
    public function AddCarrito(Request $request): JsonResponse
    {
        $cantidad = (int) $request->request->get('cantidad');
        if ($cantidad <= 0) {
            return new JsonResponse(['error' => 'Cantidad no válida'], Response::HTTP_BAD_REQUEST);
        }
        $menu = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $request->request->get('MenuID')]);
        if (!$menu) {
            return new JsonResponse(['error' => 'Menú no encontrado'], Response::HTTP_NOT_FOUND);
        }
        // Obtener el usuario
        $user = $this->getUser();
        // Crear la entidad Pedido y PedidoMenu
        $pedido = new Pedido();
        $pedidoMenu = new PedidoMenu();
        $pedido
                ->addUsuario($user)
               ->setEstatus('Pendiente')
               ->setPrecio($menu->getPrecio() * $cantidad); // Ajuste del precio según la cantidad

        $pedidoMenu->setMenu($menu)
                   ->setCantidad($cantidad);
        $pedido->addPedidoMenu($pedidoMenu);
        // Persistir en la base de datos
        $this->entityManager->persist($pedido);
        $this->entityManager->persist($pedidoMenu);
        $this->entityManager->flush();
    
        return new JsonResponse(['message' => 'Carrito añadido'], Response::HTTP_OK);
    }
    


    #[Route('/Delete/c{cid}/p{id}', name:'app_delete')]
    public function Delete($cid,$id): JsonResponse
    {    
        $carrito = $this->entityManager->getRepository(Pedido::class)->findOneBy(['id'=> (int) $cid]);
        if(!$carrito){
            return new JsonResponse(['message' => 'Carrito no encontrado'], Response::HTTP_NOT_FOUND);
        }
        $carrito->setEstatus('Cancelado');
        $this->entityManager->persist($carrito);
        $this->entityManager->flush();
        // regresar un resultado de exito un 200
        return new JsonResponse(Response::HTTP_OK);
    }

    #[Route('/DtCompra', name: 'app_detalle_compra')]
    public function DtCompra(): Response {
        $user = $this->getUser();
        $carrito = $this->entityManager->getRepository(Pedido::class)->findById($user,1);
        $total = 0.0;
        $datos = [];
        if ($carrito != null){
            foreach($carrito as $pedido){
                $pedidoMenus = $pedido->getPedidoMenus();
                foreach($pedidoMenus  as $pedidoMenu){
                    $menu = $pedidoMenu->getMenu();
                    $menus[] = [
                        'nombre' => $menu->getNomMenu(),
                        'imagen' => $menu->getImagen(),
                        'cantidad' => $pedidoMenu->getCantidad(),
                        'Precio' => $pedidoMenu->getCantidad() * $menu->getPrecio()
                    ];
                    $total += $pedidoMenu->getCantidad() * $menu->getPrecio();
                }
            }
            $datos[] = [
                'id' => $pedido->getId(),
                'Total' =>$total,
                'menus' => $menus,
            ];
        }
        if (empty($datos)){
            return $this->render('user/detalleCompra.html.twig', [
                'pedidos' => null
            ]);
        }
        return $this->render('user/detalleCompra.html.twig',
        [
            'pedidos' => $datos[0],

        ]);
    }

    #[Route('/conf/pedido', name: 'app_conf')]
    public function confirmar(Request $request): Response{
        $idp = $request->request->get('idp');
        $total = $request->request->get('total');
        $pedido = $this->entityManager->getRepository(Pedido::class)->findOneBy(['id'=> $idp]);
        if (!$pedido){
            return new JsonResponse(Response::HTTP_FORBIDDEN);
        }
        $pedido->setEstatus('Confirmado')
            ->setPrecio($total);
        $this->entityManager->persist($pedido);
        $this->entityManager->flush();
        return new JsonResponse(Response::HTTP_OK);
    }
    
    #[Route('/D/N{id}', name:'app_Negocio')]
    public function Negocio($id): Response{
        $negocio = $this->entityManager->getRepository(Negocio::class)->findOneBy(['id' => $id]);
        $categoriaComida = $negocio->getCatComida()[0];
        if(!$negocio){
            return new JsonResponse(['error' => 'Negocio no encontrado'], Response::HTTP_NOT_FOUND);
        }
        // dd($negocio);
        // die;
        return $this->render('user/detalleNegocios.html.twig',
        [
            'negocio' => $negocio,
            'catComida' => $categoriaComida
        ]);
    }
    #[Route('/Pedidos', name: 'app_pedido')]
    public function pedidos(): Response {
        $user = $this->getUser();
        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        
        $pedidos = $this->entityManager->getRepository(Pedido::class)->findById($user, 2);
        // // Verificar si $pedidos es un array y no null
        // if ($pedidos === null) {
        //     $this->addFlash('error', 'No se encontraron pedidos.');
        //     return $this->redirectToRoute('app_home'); // Redirige a una página de inicio o error
        // }
    
        return $this->render('user/pedidos.html.twig', [
            'pedidos' => $pedidos,
        ]);
    }
    

}
