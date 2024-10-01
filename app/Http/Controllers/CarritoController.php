<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Models\ProductoCarrito;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    public function getCarrito($idUsuario) {
        try {
            $carrito = User::with(['carrito.productos.producto'])->find($idUsuario);
            $carrito->makeHidden('first_name', 'last_name', 'nickname', 'saldo', 'url_avatar');
            $carrito->carrito->makeHidden('usuario_id');

            if ($carrito->carrito->productos) {
                foreach($carrito->carrito->productos as $productos) {
                    $productos->makeHidden('producto_id', 'carrito_id');
                    $productos->producto->makeHidden('descripcion', 'calificacion_final', 'stock');
                }
            }
            return response()->json($carrito, 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }

    public function addProducto(Request $request, $idCarrito) {
        try {
            //request body -> producto_id, cantidad
            DB::beginTransaction();
            $carrito = Carrito::find($idCarrito)->first();
            $producto = Producto::find($request->producto_id)->first();

            if ($producto->stock < $request->cantidad) return response()->json(['error' => 'La cantidad seleccionada excede al stock de este producto.'], 400);

            $productoCarrito = ProductoCarrito::updateOrCreate([
                'producto_id' => $producto->id,
                'carrito_id' => $carrito->id,
            ], [
                'cantidad' => $request->cantidad,
                'precio' => $producto->precio * $request->cantidad
            ]);

            $carrito->precio_total = $productoCarrito->sum('precio');
            $carrito->save();          
            DB::commit();
            return response()->json(['msg' => 'Producto agregado al carrito'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''. $e], 500);
        }
    }

    public function quitarProducto($idCarritoProducto) {
        try {
            DB::beginTransaction();
            $productoCarrito = ProductoCarrito::find($idCarritoProducto)->first();
            $carrito = Carrito::find($productoCarrito->carrito_id)->first();
            $productoCarrito->delete();
            $carrito->precio_total = ProductoCarrito::where('carrito_id', $carrito->id)->get()->sum('precio');
            $carrito->save();
            DB::commit();
            return response()->json(['msg' => 'Producto removido del carrito'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''. $e], 500);
        }
    }
}
