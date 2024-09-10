<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class GerenteController extends Controller
{
    /**
     * @OA\Info(
     *   title="Gerente Api",
     *   version="1.0",
     * )
     */
    public function index(){
        
    /**
     * @OA\Get(
     *     path="/api/gerentes",
     *     summary="Listar todos os gerentes",
     *     tags={"Gerentes"},
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="name", type="string", example="Nome 1"),
     *                 @OA\Property(property="foto", type="string", example="/imagens/exemplo.png"),
     *                 @OA\Property(property="status", type="integer", example=200)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Nenhum registro encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nenhum registro encontrado"),
     *             @OA\Property(property="status", type="integer", example=204)
     *         )
     *     )
     * )
     */
        $gerentes=User::query()->where('cargo','gerente')->get(['name','foto']);
        if(sizeOf($gerentes)===0){
            return response()->json(['message'=>'Nenhum registro encontrado',
            'status'=>204]);
        }
        $paginas=(int)($gerentes->count()/5);
        
        if($gerentes->count()%5!==0)
           $paginas++;
        return response()->json([
            'dados'=>$gerentes,
            'pages'=>$paginas,
            'status'=>200
        ]);
    }
}
