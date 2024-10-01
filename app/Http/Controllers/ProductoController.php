<?php

namespace App\Http\Controllers;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function getProductos () {
        try {
            $categorias = CategoriaProducto::whereHas('productos', function($query) {
                $query->where('stock', '>', 0);
            })->with(['productos' => function($query) {
                $query->where('stock', '>', 0);
            }])->get();

            foreach ($categorias as $categoria) {
                foreach ($categoria->productos as $producto) {
                    $producto->makeHidden('descripcion', 'calificacion_final');
                }
            }
            return response()->json(['data' => $categorias, 'size' => count($categorias)], 200);
        } catch (Exception $e) {
            return response()->json(['error' => ''. $e], 500);
        }
    }

    public function agregarProducto(Request $request, $idCategoria) {
        try {
            DB::beginTransaction();
            $producto = Producto::create([
                'categoria_id' => $idCategoria,
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'precio' => $request->precio,
                'stock' => $request->stock,
            ]);

            if ($request->producto_img) {
                $archivo = $request->producto_img;
                $timestamp = Carbon::now()->format('Y-m-d-H-i-s-u');
                $fileName = "{$timestamp}-{$idCategoria}.{$archivo->extension()}";
                $archivo->storeAs('producto/'.$producto->id, $fileName, 'public');
                $producto->producto_img = $fileName;
                $producto->save();
            }

            DB::commit();
            return response()->json(['message' => 'Producto agregado con exito.'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '' . $e], 500);
        }
    }

    public function agregarStock(Request $request, $idProducto) {
        try {
            DB::beginTransaction();
            $producto = Producto::find($idProducto)->first();
            $producto->stock += $request->stock;
            $producto->save();
            DB::commit();
            return response()->json(['message' => 'Stock agregado con exito.'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => '' . $e], 500);
        }
    }

    public function detalleProducto($idProducto) {
        try {
            $producto = Producto::with('comentarios.usuario')->where('id', $idProducto)->get();
            foreach ($producto as $detalle) {
                foreach ($detalle->comentarios as $comentario) {
                    $comentario->makeHidden('usuario_id', 'producto_id');
                    $comentario->usuario->makeHidden('first_name', 'last_name', 'email', 'saldo');
                }
            }
            return response()->json($producto, 200);
        } catch (Exception $e) {
            return response()->json(['error' => '' . $e], 500);
        }
    }
}
