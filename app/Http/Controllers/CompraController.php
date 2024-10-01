<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Compra;
use App\Models\CompraProducto;
use App\Models\Producto;
use App\Models\ProductoCarrito;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    public function getCompra($idUsuario) {
        try {
            $compra = User::with(['compras.productos.producto'])->where('id', $idUsuario)->get();
            return response()->json($compra, 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''.$e], 500);
        }
    }

    public function addCompra($idCarrito) {
        try {
            DB::beginTransaction();
    
            $usuario = User::find($idCarrito);
            $carrito = Carrito::find($idCarrito);
    
            if ($usuario->saldo < $carrito->precio_total) {
                return response()->json(['error' => 'Saldo insuficiente.'], 400);
            }
    
            $carritoProducto = ProductoCarrito::where('carrito_id', $idCarrito)->get();
    
            foreach ($carritoProducto as $itemCarrito) {
                $producto = Producto::find($itemCarrito->producto_id);
    
                if ($producto->stock < $itemCarrito->cantidad) {
                    return response()->json(['message' => 'No hay suficiente stock para el producto: ' . $producto->nombre], 400);
                }
            }
    
            $compra = Compra::create([
                'usuario_id' => $usuario->id,
                'precio_final' => $carrito->precio_total
            ]);
    
            foreach ($carritoProducto as $itemCarrito) {
                CompraProducto::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $itemCarrito->producto_id,  
                    'cantidad' => $itemCarrito->cantidad,
                    'precio' => $itemCarrito->precio
                ]);
    
                $producto = Producto::find($itemCarrito->producto_id);
                $producto->stock -= $itemCarrito->cantidad;
                $producto->save();
            }
    
            $usuario->saldo -= $carrito->precio_total;
            $usuario->save();
    
            ProductoCarrito::where('carrito_id', $idCarrito)->delete();
            $carrito->precio_total = 0;
            $carrito->save();
    
            DB::commit();
    
            return response()->json(['message' => 'Compra realizada con éxito'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function cancelCompra($idCompra) {
        try {
            DB::beginTransaction();
            $compra = Compra::find($idCompra);
            if (!$compra) return response()->json(['error' => 'Compra no encontrada.'], 404);
            $usuario = User::find($compra->usuario_id); 
            if (!$usuario) return response()->json(['error' => 'Usuario no encontrado para esta compra.'], 404);
            $productosCompra = CompraProducto::where('compra_id', $idCompra)->get();
            foreach ($productosCompra as $productoCompra) {
                $producto = Producto::find($productoCompra->producto_id);
                if ($producto) {
                    $producto->stock += $productoCompra->cantidad; 
                    $producto->save();
                }
            }
            $usuario->saldo += $compra->precio_final;
            $usuario->save();
            CompraProducto::where('compra_id', $idCompra)->delete();

            $compra->delete();
    
            DB::commit();
    
            return response()->json(['message' => 'Compra cancelada exitosamente.'], 200);
    
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''.$e], 500);
        }
    }
    
    
    
}
