<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jogo;
use OpenApi\Annotations as OA;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *   title="API Laravel Swagger Documentation",
 *   version="1.0.0",
 *   contact={
 *     "email": "heningtonfrota@gmail.com"
 *   }
 * )
 * @OA\SecurityScheme(
 *  type="http",
 *  description="Acess token obtido na autenticação",
 *  name="Authorization",
 *  in="header",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 *  securityScheme="bearerToken"
 * )
 */

class JogoController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/games/todos",
     *     summary="Lista todos os jogos",
     *     tags={"Jogos"},
     *     security={ {"bearerToken":{}} },
     *     @OA\Response(
     *         response="200",
     *         description="Lista de jogos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Jogo")
     *         )
     *     )
     * )
     */
    /**
    * @OA\Schema(
    *     schema="Jogo",
    *     type="object",
    *     @OA\Property(property="id", type="integer"),
    *     @OA\Property(property="nome", type="string"),
    *     @OA\Property(property="genero", type="string"),
    *     @OA\Property(property="preco", type="number", format="float")
    * )
    */
    public function index()
    {
        $jogos = Jogo::all();
        return response()->json($jogos);
    }

    /**
     * @OA\POST(
     *     tags={"Jogos"},
     *     security={ {"bearerToken":{}} },
     *     summary="Cria um novo jogo",
     *     path="/api/games/salvar",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/JogoInput")
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="Jogo criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Jogo")
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Erro de validação ou requisição inválida"
     *     )
     * )
     */

    /**
    * @OA\Schema(
    *     schema="JogoInput",
    *     type="object",
    *     required={"nome", "genero", "preco"},
    *     @OA\Property(property="nome", type="string"),
    *     @OA\Property(property="genero", type="string"),
    *     @OA\Property(property="preco", type="number", format="float")
    * )
    */
    public function store(Request $request)
    {
        $jogo = Jogo::create($request->all());
        return response()->json($jogo, 200);
    }

    /**
     * @OA\Get(
     *     path="/api/games/{id}",
     *     summary="Obtém informações de um jogo específico",
     *     tags={"Jogos"},
     *     security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Informações do jogo",
     *         @OA\JsonContent(ref="#/components/schemas/Jogo")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Jogo não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        $jogo = Jogo::findOrFail($id);
        return response()->json($jogo);
    }

    /**
     * @OA\Put(
     *     path="/api/games/{id}",
     *     summary="Atualiza um jogo existente",
     *     tags={"Jogos"},
     *     security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/JogoInput")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Jogo atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/Jogo")
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Jogo não encontrado"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $jogo = Jogo::findOrFail($id);
        $jogo->update($request->all());
        return response()->json($jogo);
    }

    /**
     * @OA\Delete(
     *     path="/api/games/{id}",
     *     summary="Exclui um jogo",
     *     tags={"Jogos"},
     *     security={ {"bearerToken":{}} },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do jogo",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="204",
     *         description="Jogo excluído com sucesso"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Jogo não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $jogo = Jogo::findOrFail($id);
        $jogo->delete();
        return response()->json(null, 204);
    }
}

