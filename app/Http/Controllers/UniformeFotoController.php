<?php

namespace App\Http\Controllers;

use App\Models\UniformeFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UniformeFotoController extends Controller
{
    public function destroy($id)
    {
        try {
            Log::info('Iniciando eliminación de foto con ID: ' . $id);
            $foto = UniformeFoto::findOrFail($id);
            Storage::disk('public')->delete($foto->foto_path);
            $foto->delete();
            Log::info('Foto eliminada exitosamente');
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error al eliminar la foto: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar la foto', 'details' => $e->getMessage()], 500);
        }
    }
}
