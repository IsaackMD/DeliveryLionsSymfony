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
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    #[Route('/Det/Com/', name: 'app_det_comida')]
    public function DetComida(Request $request): Response
    {
        $id = $request->get('id');
        // dump($id);
        // die;
        $comida = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $id]);
        $negocio = $comida->getNegocio();
        $comidas = $this->entityManager->getRepository(Menu::class)->findBy(['cat_comida' => $comida->getCatComida()]);
        // dump($comida);
        // dump($negocio);
        // die;
        $data = [
            'id' => $comida->getId(),
            'nombre' => $comida->getNomMenu(),
            'imagen' => $comida->getImagen() ,
            'precio' => $comida->getPrecio(),
            'descuento' => $comida->getDescuento(),
            'preciofinal' => $comida->getPrecio()- ($comida->getPrecio()*(0.01 *$comida->getDescuento())),
            'Negocio' => $negocio->getNegocio(),
            'descripcion' => $comida->getDescrip(),
            'complemento' => $comida->getComplemento(),
        ];
        // dump($data);
        // die;
        return new JsonResponse($data);
    }

    #[Route('/carrito', name :'app_carrito')]
    public function Carrito(SessionInterface $session): JsonResponse{
        $user = $this->getUser();
        if(!$user){
            return new JsonResponse(['error' => 'No se ha iniciado sesión']);
        }
        $pedido = $this->entityManager->getRepository(Pedido::class)->findOneBy(['Usuario' => $user, 'Estatus' => 'Pendiente']);
        $pedidoMenu = $this->entityManager->getRepository(PedidoMenu::class)->findBy(['Pedido' => $pedido, 'Estatus' => true]);
        $carritoMenu = [];
        foreach ($pedidoMenu as $menu) {
            $carritoMenu[] = 
            [
                'id' => $menu->getMenu()->getId(),
                'nombre' => $menu->getMenu()->getNomMenu(),
                'imagen' => $menu->getMenu()->getImagen(),
                'precio' => $menu->getMenu()->getPrecio() - ($menu->getMenu()->getPrecio() * (0.01 * $menu->getMenu()->getDescuento())),
                'cantidad' => $menu->getCantidad(),
                'pedidomenuId' => $menu->getId(),
            ];
        }
        $carrito[] = [
            'id' => $pedido->getId(),
            'total' => $pedido->getPrecio(),
            'menus' => $carritoMenu
        ];
        
        // dump($carrito);
        // die;
        
        return new JsonResponse( $carrito);
    }
    
    #[Route('/add-to-carrito',name: 'add_to_carrito')]
    public function AddCarrito(Request $request): JsonResponse
    {
        $idProductos = $request->request->get('id');
        $cantidad = $request->request->get('cantidad');

        // verificar si el usuario esta logueado
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Usuario no autenticado'], Response::HTTP_FORBIDDEN);
        }
        
        // verificar si el usuario ya tiene un pedido en estado pendiente
        $menu = $this->entityManager->getRepository(Menu::class)->findOneBy(['id' => $idProductos]);
        if (!$menu) {
            return new JsonResponse(['error' => 'Producto no encontrado'], Response::HTTP_NOT_FOUND);
        }

        $precioProducto = $menu->getPrecio() - ($menu->getPrecio() * 0.01 * $menu->getDescuento());
        $precioProductoTotal = $precioProducto * $cantidad;


        $pedido = $this->entityManager->getRepository(Pedido::class)->findOneBy(['Usuario' => $user, 'Estatus' => 'Pendiente']);

        
        iF($pedido){
            // Modificar el precio del pedido existente
            $pedido->setPrecio($pedido->getPrecio() + $precioProductoTotal);
            $this->entityManager->persist($pedido);
            $this->entityManager->flush();

        }else{
            $pedido = new Pedido();
            $pedido->setUsuario($user)
            ->setEstatus('Pendiente')
            ->setPrecio($precioProductoTotal);
            $this->entityManager->persist($pedido);
        }
        // verificar si el pedido existe, si no existe inicializar uno nuevo
       
        $pedidoMenuExist = $this->entityManager->getRepository(PedidoMenu::class)->findOneBy(['Pedido' => $pedido, 'Menu' => $menu, 'Estatus' => true]);
        
        if($pedidoMenuExist) {
            // Si el menú ya existe en el pedido, actualizar la cantidad y calcula el precio total
            $pedidoMenuExist->setCantidad($pedidoMenuExist->getCantidad() + $cantidad);
            $this->entityManager->persist($pedidoMenuExist);
            // $this->entityManager->flush();
            // return new JsonResponse(['success' => true], Response::HTTP_OK);
        }else{
            $pedidoMenu = new PedidoMenu();
            $pedidoMenu->setMenu($menu);
            $pedidoMenu->setCantidad($cantidad);
            $pedidoMenu->setPedido($pedido);
            // Persistir en la base de datos
            $this->entityManager->persist($pedidoMenu);
        }

        $this->entityManager->flush();
        return new JsonResponse(['success' => true], Response::HTTP_OK);
    }
    


    #[Route('/Delete/', name:'app_delete')]
    public function Delete(Request $request): JsonResponse
    {   
        $id = $request->request->get('id');
        $pedidoMenu = $this->entityManager->getRepository(PedidoMenu::class)->findOneBy(['id' => $id]);
        if (!$pedidoMenu) {
            return new JsonResponse(['error' => 'Pedido no encontrado'], Response::HTTP_NOT_FOUND);    
        }
        $pedidoMenu->setEstatus(false);
        $this->entityManager->persist($pedidoMenu);
        $this->entityManager->flush();
        return new JsonResponse(Response::HTTP_OK);
    }

    #[Route('/DtCompra', name: 'app_detalle_compra')]
    public function DtCompra(): Response {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'No se ha iniciado sesión']);
        }
    
        $pedido = $this->entityManager->getRepository(Pedido::class)->findOneBy([
            'Usuario' => $user,
            'Estatus' => 'Pendiente'
        ]);
    
        if (!$pedido) {
            return $this->render('user/detalleCompra.html.twig', ['pedidos' => null]);
        }
    
        $pedidoMenus = $this->entityManager->getRepository(PedidoMenu::class)->findBy([
            'Pedido' => $pedido,
            'Estatus' => true
        ]);
    
        $carritoMenu = [];
        $total = 0;
        $descuento = 0;
    
        foreach ($pedidoMenus as $menu) {
            $precioOriginal = $menu->getMenu()->getPrecio();
            $porcentajeDescuento = $menu->getMenu()->getDescuento();
            $cantidad = $menu->getCantidad();
    
            $descuentoAplicado = $precioOriginal * (0.01 * $porcentajeDescuento);
            $precioFinal = $precioOriginal - $descuentoAplicado;
    
            $carritoMenu[] = [
                'nombre' => $menu->getMenu()->getNomMenu(),
                'imagen' => $menu->getMenu()->getImagen(),
                'Precio' => $precioFinal,
                'cantidad' => $cantidad,
            ];
    
            $total += $precioFinal * $cantidad;
            $descuento += $descuentoAplicado * $cantidad; // se multiplica por la cantidad
        }
    
        $carrito[] = [
            'id' => $pedido->getId(),
            'Total' => $total,
            'descuento' => $descuento,
            'menus' => $carritoMenu
        ];
    
        return $this->render('user/detalleCompra.html.twig', [
            'pedidos' => $carrito[0],
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
