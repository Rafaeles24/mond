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
    public function getCarrito($idCarrito) {
        try {
            $carrito = Carrito::with(['productos.producto'])->find($idCarrito);
            $carrito->makeHidden('usuario_id');

            if ($carrito->productos) {
                foreach($carrito->productos as $productos) {
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
            DB::beginTransaction();
            $carrito = Carrito::find($idCarrito);
            if (!$carrito) {
                return response()->json(['error' => 'Carrito no encontrado.'], 404);
            }
            $producto = Producto::find($request->producto_id);
            if (!$producto) {
                return response()->json(['error' => 'Producto no encontrado.'], 404);
            }
            $productoCarrito = ProductoCarrito::where('producto_id', $producto->id)
                ->where('carrito_id', $idCarrito)
                ->first();
            $cantidadActual = $productoCarrito ? $productoCarrito->cantidad : 0;
            if ($producto->stock < ($cantidadActual + $request->cantidad)) {
                return response()->json(['error' => 'La cantidad seleccionada excede al stock de este producto.'], 400);
            }
            $productoCarrito = ProductoCarrito::updateOrCreate(
                [
                    'producto_id' => $producto->id,
                    'carrito_id' => $carrito->id,
                ],
                [
                    'cantidad' => $cantidadActual + $request->cantidad,
                    'precio' => $producto->precio * ($cantidadActual + $request->cantidad)
                ]
            );
            $carrito->precio_total = ProductoCarrito::where('carrito_id', $idCarrito)->sum('precio');
            $carrito->save();
            DB::commit();
            return response()->json(['message' => 'Producto agregado al carrito'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''.$e], 500);
        }
    }
    

    public function quitarProducto($idCarritoProducto) {
        try {
            DB::beginTransaction();
            $productoCarrito = ProductoCarrito::find($idCarritoProducto);
            $carrito = Carrito::find($productoCarrito->carrito_id);
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
