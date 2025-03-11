<?php

namespace App\Http\Controllers;

use App\Models\Uniforme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UniformeController extends Controller
{
    public function index()
    {
        try {
            Log::info('Iniciando consulta de uniformes en PostgreSQL');
            Log::info('Configuración de DB:', config('database.connections.pgsql'));

            // Verifica la conexión a la base de datos
            if (!\DB::connection()->getPdo()) {
                throw new \Exception('No se pudo conectar a la base de datos PostgreSQL');
            }

            $uniformes = Uniforme::all();
            Log::info('Uniformes obtenidos de PostgreSQL:', $uniformes->toArray());

            // Verifica si hay datos
            if ($uniformes->isEmpty()) {
                Log::warning('No se encontraron uniformes en la base de datos');
                return response()->json([]); // Devuelve un array vacío
            }

            $uniformes = $uniformes->map(function ($uniforme) {
                Log::debug('Mapeando uniforme:', $uniforme->toArray());
                return [
                    'id' => $uniforme->id,
                    'nombre' => $uniforme->nombre,
                    'descripcion' => $uniforme->descripcion,
                    'categoria' => $uniforme->categoria,
                    'tipo' => $uniforme->tipo ?? 'Sin tipo',
                    'foto_path' => $uniforme->foto_path ?? null, // Solo foto_path
                    'created_at' => $uniforme->created_at,
                    'updated_at' => $uniforme->updated_at,
                ];
            });

            Log::info('Uniformes mapeados:', $uniformes->toArray());
            return response()->json($uniformes);
        } catch (\Exception $e) {
            Log::error('Error en index con PostgreSQL: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno al obtener uniformes', 'details' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            Log::info('Obteniendo uniforme con ID: ' . $id);
            $uniforme = Uniforme::findOrFail($id);
            return response()->json([
                'id' => $uniforme->id,
                'nombre' => $uniforme->nombre,
                'descripcion' => $uniforme->descripcion,
                'categoria' => $uniforme->categoria,
                'tipo' => $uniforme->tipo ?? 'Sin tipo',
                'foto_path' => $uniforme->foto_path ?? null, // Solo foto_path
                'created_at' => $uniforme->created_at,
                'updated_at' => $uniforme->updated_at,
            ]);
        } catch (\Exception $e) {
            Log::error('Error en show con PostgreSQL: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener el uniforme', 'details' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('Iniciando almacenamiento de uniforme en PostgreSQL', $request->all());
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'categoria' => 'required|in:Industriales,Médicos,Escolares,Corporativos|string|max:255',
                'tipo' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Solo una foto
            ]);

            $uniforme = new Uniforme();
            $uniforme->nombre = $validatedData['nombre'];
            $uniforme->descripcion = $validatedData['descripcion'];
            $uniforme->categoria = $validatedData['categoria'];
            $uniforme->tipo = $validatedData['tipo'];

            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('public/uploads');
                $uniforme->foto_path = str_replace('public/', '', $path);
            }

            $uniforme->save();

            Log::info('Uniforme almacenado con éxito en PostgreSQL', $uniforme->toArray());
            return response()->json($uniforme, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validación fallida en store con PostgreSQL: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en store con PostgreSQL: ' . $e->getMessage());
            return response()->json(['error' => 'Error al guardar el uniforme', 'details' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Log::info('Iniciando actualización de uniforme con ID: ' . $id, $request->all());
            $validatedData = $request->validate([
                'nombre' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'categoria' => 'required|in:Industriales,Médicos,Escolares,Corporativos|string|max:255',
                'tipo' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Solo una foto
            ]);

            $uniforme = Uniforme::findOrFail($id);
            $uniforme->nombre = $validatedData['nombre'];
            $uniforme->descripcion = $validatedData['descripcion'];
            $uniforme->categoria = $validatedData['categoria'];
            $uniforme->tipo = $validatedData['tipo'];

            if ($request->hasFile('foto')) {
                if ($uniforme->foto_path) {
                    Storage::delete('public/' . $uniforme->foto_path);
                }
                $path = $request->file('foto')->store('public/uploads');
                $uniforme->foto_path = str_replace('public/', '', $path);
            }

            $uniforme->save();

            Log::info('Uniforme actualizado con éxito en PostgreSQL', $uniforme->toArray());
            return response()->json($uniforme);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validación fallida en update con PostgreSQL: ' . json_encode($e->errors()));
            return response()->json([
                'message' => 'Validación fallida',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en update con PostgreSQL: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el uniforme', 'details' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Iniciando eliminación de uniforme con ID: ' . $id);
            $uniforme = Uniforme::findOrFail($id);

            if ($uniforme->foto_path) {
                Storage::delete('public/' . $uniforme->foto_path);
            }

            $uniforme->delete();

            Log::info('Uniforme eliminado con éxito en PostgreSQL');
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error en destroy con PostgreSQL: ' . $e->getMessage());
            return response()->json(['error' => 'Error al eliminar el uniforme', 'details' => $e->getMessage()], 500);
        }
    }
}