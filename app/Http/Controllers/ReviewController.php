<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Review;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function agregarComentario(Request $request, $idProducto) {
        try {
            DB::beginTransaction();
            Review::create([
                'usuario_id' => $request->usuario_id,
                'producto_id' => $idProducto,
                'calificacion' => $request->calificacion,
                'comentario' => $request->comentario
            ]);

            $producto = Producto::find($idProducto)->first();

            $reviews = Review::where('producto_id', $idProducto)->get();

            $calificacionTotal = $reviews->sum('calificacion');
            $producto->calificacion_final = $calificacionTotal / count($reviews);

            $producto->save();
            DB::commit();
            return response()->json(['message' => 'comentario agregado con exito'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => ''. $e], 500);
        }
    }
}
