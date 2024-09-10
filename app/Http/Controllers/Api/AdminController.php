<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * @OA\Info(
     *   title="Admin Api",
     *   version="1.0",
     * )
     */
    public function index(){
        
    /**
     * @OA\Get(
     *     path="/api/administradores",
     *     summary="Listar todos os admins",
     *     tags={"Amins"},
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
        $adms=User::query()->where('cargo','administrador')->get(['name','foto']);
        if(sizeOf($adms)===0){
            return response()->json(['message'=>'Nenhum registro encontrado',
            'status'=>204]);
        }
        $paginas=(int)($adms->count()/5);
        
        if($adms->count()%5!==0)
           $paginas++;
        return response()->json([
            'dados'=>$adms,
            'pages'=>$paginas,
            'status'=>200
        ]);
    }
}
