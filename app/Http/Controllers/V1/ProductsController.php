<?php
namespace App\Http\Controllers\V1;
use App\Models\Product;
use Illuminate\Http\Request;
use Dflydev\DotAccessData\Data;
//use JWTAuth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    protected $user;
    public function __construct(Request $request)
    {
        $token = $request->header('Authorization');
        if($token != '')
            //En caso de que requiera autentifiación la ruta obtenemos el usuario y lo almacenamos en una variable, nosotros no lo utilizaremos.
            $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $where="";
        
        $where.=isset($request->nombre) && !empty($request->nombre) ? "nombre="."'".$request->nombre."'"." AND " :"";
        $where.=isset($request->precio) && !empty($request->precio) ? "precio="."'".$request->precio."'"." AND " :"";
        $where.=isset($request->stock) && !empty($request->stock) ? "stock="."'".$request->stock."'"." AND " :"";
        $where.=isset($request->categoria) && !empty($request->categoria) ? "categoria="."'".$request->categoria."'"." AND " :"";
        $where.=isset($request->tag) && !empty($request->tag) ? "tag="."'".$request->tag."'"." AND " :"";
        $where.=isset($request->valoracion) && !empty($request->valoracion) ? "valoracion="."'".$request->valoracion."'"." AND " :"";
        $where.=isset($request->sku) && !empty($request->sku) ? "sku="."'".$request->sku."'"." AND " :"";
        $where=substr($where, 0, -5);
        if($where==="")
        $result=DB::select("SELECT * FROM products");
        else
        $result=DB::select("SELECT * FROM products WHERE ".$where);
        
        return response()->json($result);
        //return Product::get();
    }

    public function indexcant(Request $request)
    {
        $where="";
        
        $where.=isset($request->nombre) && !empty($request->nombre) ? "nombre="."'".$request->nombre."'"." AND " :"";
        $where.=isset($request->precio) && !empty($request->precio) ? "precio="."'".$request->precio."'"." AND " :"";
        $where.=isset($request->stock) && !empty($request->stock) ? "stock="."'".$request->stock."'"." AND " :"";
        $where.=isset($request->categoria) && !empty($request->categoria) ? "categoria="."'".$request->categoria."'"." AND " :"";
        $where.=isset($request->tag) && !empty($request->tag) ? "tag="."'".$request->tag."'"." AND " :"";
        $where.=isset($request->valoracion) && !empty($request->valoracion) ? "valoracion="."'".$request->valoracion."'"." AND " :"";
        $where.=isset($request->sku) && !empty($request->sku) ? "sku="."'".$request->sku."'"." AND " :"";
        $where=substr($where, 0, -5);
        if($where==="")
        $result=DB::select("SELECT count(*) FROM products");
        else
        $result=DB::select("SELECT count(*) FROM products WHERE ".$where);
        
        return response()->json($result);
        //return Product::get();
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validamos los datos
        $data = $request->only('name', 'description', 'stock');
        $validator = Validator::make($data, [
            'name' => 'required|max:50|string',
            'description' => 'required|max:50|string',
            'stock' => 'required|numeric',
        ]);
        //Si falla la validación
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 400);
        }
        //Creamos el producto en la BD
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'stock' => $request->stock,
        ]);
        //Respuesta en caso de que todo vaya bien.
        return response()->json([
            'message' => 'Product created',
            'data' => $product
        ], Response::HTTP_OK);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Bucamos el producto
        $product = Product::find($id);
        //Si el producto no existe devolvemos error no encontrado
        if (!$product) {
            return response()->json([
                'message' => 'Product not found.'
            ], 404);
        }
        //Si hay producto lo devolvemos
        return $product;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateventas($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->roles=='admin')
        {
          $product = Product::findOrfail($id);
          if($product['stock']==0)
           return response()->json([
            'message' => 'El producto no tiene existencia en stock',
            'data' => $product
            ], Response::HTTP_OK);
          else
           {$stock=$product['stock'];
            $stock--;
            $vend=$product['vendidos'];
            $vend++;
            $product->update([
            'stock' => $stock,
            'vendidos' => $vend,
            ]);
            //Devolvemos los datos actualizados.
            return response()->json([
            'message' => 'Producto vendido',
            'data' => $product
            ], Response::HTTP_OK);
           }
        }
        else
        return response()->json([
            'message' => 'Usted no tiene permisos de venta',
            
        ], Response::HTTP_OK);
    }
    
    public function productsvendidos(){
        $result=DB::select("SELECT * FROM products WHERE vendidos>0");
        
        return response()->json($result);
    }

    public function ganancia(){
        $sum=0;
        $results=Product::where('vendidos','>',0)->get();
        foreach($results as $result){
        $sum+=$result['precio']*$result['vendidos'];
        }
        
        return response()->json([
            'ganancia' => $sum
            ], Response::HTTP_OK);
    }

    public function nostock(){
         
        $results=Product::where('stock','=',0)->get();
        return response()->json($results);
    }



    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if($user->roles=='admin')
        {
        $product = Product::findOrfail($id);
        //Eliminamos el producto
        $product->delete();
        //Devolvemos la respuesta
        return response()->json([
            'message' => 'Producto eliminado correctamente'
        ], Response::HTTP_OK);
        }
        else
        return response()->json([
            'message' => 'Usted no tiene permiso para esta operacion'
        ], Response::HTTP_OK);
    }
}
